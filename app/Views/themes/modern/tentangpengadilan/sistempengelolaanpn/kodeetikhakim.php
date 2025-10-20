<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Kode Etik dan Pedoman Perilaku</h4>
        <div>
            <a href="<?= site_url('tentangpengadilan/editIntroKodeEtik') ?>" class="btn btn-info">Edit Teks Pengantar</a>
            
            <a href="<?= site_url('tentangpengadilan/formKodeEtik') ?>" class="btn btn-primary">Tambah Dokumen Baru</a>
        </div>
    </div>
    <div class="card-body">
        
        <div class="mb-4 p-3 border rounded bg-light">
            <h5 class="font-weight-bold">Teks Pengantar:</h5>
            <?= $intro['isi_halaman'] ?? '<span class="text-muted"> </span>' ?>
        </div>
        <hr>

        <h5 class="mb-3">Daftar Dokumen Kode Etik:</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 10%;">Urutan</th>
                        <th>Judul Dokumen</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dokumen_list as $dokumen): ?>
                    <tr>
                        <td><?= esc($dokumen['urutan']) ?></td>
                        <td>
                            <a href="<?= base_url('uploads/dokumen/' . esc($dokumen['file_dokumen'])) ?>" target="_blank">
                                <?= esc($dokumen['judul_dokumen']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= site_url('tentangpengadilan/formKodeEtik/' . $dokumen['id']) ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <a href="<?= site_url('tentangpengadilan/hapusKodeEtik/' . $dokumen['id']) ?>" class="btn btn-sm btn-danger mb-1 btn-hapus">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Aktifkan DataTables pada tabel dengan id="dataTable"
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-hapus');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                // Hentikan link agar tidak langsung berjalan
                event.preventDefault(); 
                const deleteUrl = this.getAttribute('href');

                // Tampilkan konfirmasi
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    // Jika dikonfirmasi, lanjutkan ke URL hapus
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>