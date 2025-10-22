<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): // Gunakan 'error' untuk pesan gagal ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0 text-primary"><i class="fas fa-newspaper mr-2"></i>Manajemen Berita</h5>
            </div>
            <div class="col-auto">
                <a href="<?= site_url('pengumuman/formBerita') ?>" class="btn btn-success rounded-pill">
                    <i class="fas fa-plus mr-1"></i> Tambah Berita
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 15%;">Gambar Cover</th>
                    <th>Judul</th>
                    <th>Slug</th>
                    <th style="width: 18%;">Tanggal Publish</th>
                    <th style="width: 10%;">Status</th> 
<th style="width: 12%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($berita_list)): ?>
                    <?php foreach($berita_list as $berita): ?>
                    <tr>
                        <td class="text-center align-middle p-2">
                            <?php $gambarPath = 'uploads/berita/' . ($berita['gambar'] ?? 'no-image.jpg'); ?>
                            <img src="<?= base_url(is_file(FCPATH . $gambarPath) ? $gambarPath : 'assets/img/placeholder.png') ?>" 
                                 alt="<?= esc($berita['judul'] ?? 'Berita') ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 70px; width: auto; object-fit: contain;">
                        </td>
                        <td class="align-middle"><?= esc($berita['judul'] ?? 'N/A') ?></td>
                        <td class="align-middle"><?= esc($berita['slug'] ?? 'N/A') ?></td>
                        <td class="align-middle">
                            <?= isset($berita['tanggal_publish']) ? esc(date('d M Y, H:i', strtotime($berita['tanggal_publish']))) : 'N/A' ?>
                        </td>
                        
                        <td class="text-center align-middle">
                            <?php
                                // 1. Ambil nilai status dari array $berita.
                                //    Gunakan null coalescing operator (??) untuk memberi nilai default 'published'
                                //    jika $berita['status'] tidak ada atau null (untuk data lama).
                                $status_berita = $berita['status'] ?? 'published';

                                // 2. Tampilkan badge berdasarkan nilai $status_berita.
                                //    Gunakan perbandingan ketat (===) untuk 'draft'.
                                if ($status_berita === 'draft'):
                            ?>
                                <span class="badge badge-secondary px-2 py-1">Draft</span>
                            <?php else: // Semua nilai lain (termasuk 'published') akan dianggap Published ?>
                                <span class="badge badge-success px-2 py-1">Published</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center align-middle">
                            <a href="<?= site_url('pengumuman/formBerita/' . ($berita['id'] ?? '')) ?>" class="btn btn-sm btn-info rounded-pill px-3 mr-1" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="<?= site_url('pengumuman/hapusBerita/' . ($berita['id'] ?? '')) ?>" class="btn btn-sm btn-danger rounded-pill px-3 btn-hapus" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        
<td colspan="6" class="text-center text-muted py-3">Belum ada data berita.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Ini adalah perbaikan manual untuk styling badge Bootstrap.
  Ini akan mengubah warna font dan latar belakang.
*/

.badge {
    display: inline-block;
    padding: .35em .65em; /* Sedikit padding agar terlihat bagus */
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    color: #fff; /* DEFAULT WARNA FONT PUTIH */
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem; /* Membuat sudutnya melengkung */
}

/* Ini untuk status "Published" (Hijau) 
*/
.badge-success {
    background-color: #28a745; /* Latar belakang HIJAU */
    color: #ffffff; /* Font warna PUTIH */
}

/* Ini untuk status "Draft" (Abu-abu) 
  Ini yang akan memperbaiki masalahmu.
*/
.badge-secondary {
    background-color: #6c757d; /* Latar belakang ABU-ABU */
    color: #ffffff; /* Font warna PUTIH */
}
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "order": [[ 3, "desc" ]] // Indeks kolom tanggal publish adalah 3 (0-indexed)
            // ... (konfigurasi bahasa jika ada) ...
        });

        // SweetAlert untuk konfirmasi hapus (pastikan class .btn-hapus ada di tombol hapus)
        $('#dataTable').on('click', '.btn-hapus', function(e) { // Gunakan event delegation
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });
    });

    // Ini harus dijalankan sebelum inisialisasi DataTable Anda
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
        // Anda bisa memilih untuk:
        // 1. Mengabaikan warning secara total (tidak direkomendasikan karena Anda tidak akan tahu jika ada masalah lain)
        //    console.warn("DataTables Warning (suppressed): ", message);
        // 2. Menampilkan notifikasi kustom yang lebih ramah
           Swal.fire({
               icon: 'warning',
               title: 'Peringatan Tabel Data',
               html: 'Terjadi masalah pada tampilan tabel: <br><strong>' + message + '</strong><br>Mohon hubungi administrator jika masalah berlanjut.',
               confirmButtonText: 'Oke',
               footer: '<a href="' + helpPage + '" target="_blank">Lihat bantuan DataTables</a>'
           });
    };

    $(document).ready(function () {
        // Pastikan Anda memuat DataTables Responsive jika Anda menggunakan class dt-responsive
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy(); // Hancurkan inisialisasi sebelumnya jika ada
        }
        
        $('#dataTable').DataTable({
            "order": [[ 2, "desc" ]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            // Tambahkan ini jika Anda ingin DataTables Responsive
            responsive: true 
        });
    });
</script>