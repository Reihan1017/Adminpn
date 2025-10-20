<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4>
        <a href="<?= site_url('tentangpengadilan/formProfil/' . $kategori) ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Profil <?= ucfirst($kategori) // Menampilkan nama kategori ?>
        </a>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%;">Urutan</th>
                    <th style="width: 15%;">Foto</th>
                    <th>Nama & Jabatan</th>
                    <th>NIP & Pangkat/Gol</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($profil_list as $profil): ?>
                <tr>
                    <td class="text-center align-middle"><?= esc($profil['urutan']) ?></td>
                    <td class="text-center align-middle">
                        <img src="<?= base_url('uploads/pegawai/' . esc($profil['foto_pegawai'])) ?>"
                             alt="Foto <?= esc($profil['nama_pegawai']) ?>"
                             class="img-thumbnail rounded-circle"
                             style="height: 80px; width: 80px; object-fit: cover;">
                    </td>
                    <td class="align-middle">
                        <strong class="d-block"><?= esc($profil['nama_pegawai']) ?></strong>
                        <small class="text-muted"><?= esc($profil['jabatan']) ?></small>
                    </td>
                    <td class="align-middle">
                        <span class="d-block">NIP: <?= esc($profil['nip']) ?></span>
                        <small class="text-muted">Pangkat: <?= esc($profil['pangkat_gol']) ?></small>
                    </td>
                    <td class="text-center align-middle">
                        <a href="<?= site_url('tentangpengadilan/formProfil/' . $profil['kategori'] . '/' . $profil['id']) ?>"
                           class="btn btn-sm btn-success mb-1" title="Edit">
                           <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= site_url('tentangpengadilan/hapusProfil/' . $profil['id']) ?>"
                           class="btn btn-sm btn-danger mb-1 btn-hapus" title="Hapus">
                           <i class="fas fa-trash"></i> Delete
                        </a>
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