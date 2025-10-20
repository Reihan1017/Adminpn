<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4>
        <a href="<?= site_url('tentangpengadilan/formProfil/' . $kategori) ?>" class="btn btn-primary">Tambah Profil PPPK</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Urutan</th>
                        <th>Foto</th>
                        <th>Nama & Jabatan</th>
                        <th>NIP & Pangkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($profil_list as $profil): ?>
                    <tr>
                        <td><?= esc($profil['urutan']) ?></td>
                        <td>
                            <img src="<?= base_url('uploads/pegawai/' . esc($profil['foto_pegawai'])) ?>" class="img-thumbnail" width="100">
                        </td>
                        <td>
                            <strong><?= esc($profil['nama_pegawai']) ?></strong><br>
                            <small><?= esc($profil['jabatan']) ?></small>
                        </td>
                        <td>
                            NIP: <?= esc($profil['nip']) ?><br>
                            Pangkat: <?= esc($profil['pangkat_gol']) ?>
                        </td>
                        <td>
                            <a href="<?= site_url('tentangpengadilan/formProfil/' . $profil['kategori'] . '/' . $profil['id']) ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <a href="<?= site_url('tentangpengadilan/hapusProfil/' . $profil['id']) ?>" class="btn btn-sm btn-danger mb-1 btn-hapus">Hapus</a>
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