<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>


<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">Manajemen Berita</h5>
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
                            $status_berita = $berita['status'] ?? 'published';
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
.badge {
    display: inline-block;
    padding: .35em .65em;
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
}
.badge-success { background-color: #28a745; color: #ffffff; }
.badge-secondary { background-color: #6c757d; color: #ffffff; }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

        $('#dataTable').DataTable({
            "order": [[3, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                { "defaultContent": "-", "targets": "_all" } // isi kolom kosong dengan tanda "-"
            ]
        });
        // SweetAlert konfirmasi hapus
        $('#dataTable').on('click', '.btn-hapus', function(e) { 
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
</script>
