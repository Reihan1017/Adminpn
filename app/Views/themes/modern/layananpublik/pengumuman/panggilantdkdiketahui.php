<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Panggilan Tidak Diketahui</h4>
        <a href="<?= site_url('layananpublik/formPanggilanTdkDiketahui') ?>" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Panggilan</a>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Judul Panggilan</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($panggilan_list as $panggilan): ?>
                <tr>
                    <td><?= esc($panggilan['judul_panggilan']) ?></td>
                    <td><?= esc($panggilan['deskripsi']) ?></td>
                    <td>
                        <a href="<?= base_url('uploads/dokumen/' . esc($panggilan['file_panggilan'])) ?>" target="_blank">
                           <i class="fas fa-file-alt"></i> <?= esc($panggilan['file_panggilan']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= site_url('layananpublik/formPanggilanTdkDiketahui/' . $panggilan['id']) ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Edit</a>
                        <a href="<?= site_url('layananpublik/hapusPanggilanTdkDiketahui/' . $panggilan['id']) ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i> Delete</a>
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
    $(document).ready(function () { $('#dataTable').DataTable(); });
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