<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0">Manajemen Laporan Survey</h4>
    </div>
    <div class="card-body">
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="font-weight-bold">Teks Pengantar</h5>
            <a href="<?= site_url('layananpublik/editIntroLaporanSurvey') ?>" class="btn btn-info btn-sm">
                <i class="fas fa-edit"></i> Edit Teks Ini
            </a>
        </div>
        <div class="mb-4 p-3 border rounded bg-light">
            <?= $intro['isi_halaman'] ?? '<span class="text-muted">Teks pengantar belum diisi.</span>' ?>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
             <h5 class="font-weight-bold">Daftar Dokumen Laporan Survey</h5>
            <a href="<?= site_url('layananpublik/formLaporanSurvey') ?>" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Laporan</a>
        </div>
        
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Judul Laporan</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($laporan_list as $laporan): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('uploads/dokumen/' . esc($laporan['file_laporan'])) ?>" target="_blank">
                            <?= esc($laporan['judul_laporan']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= site_url('layananpublik/formLaporanSurvey/' . $laporan['id']) ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Edit</a>
                        <a href="<?= site_url('layananpublik/hapusLaporanSurvey/' . $laporan['id']) ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

    // Aktifkan konfirmasi hapus modern (SweetAlert2)
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