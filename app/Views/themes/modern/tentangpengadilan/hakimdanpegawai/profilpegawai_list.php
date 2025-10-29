<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-light">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0 text-primary"><i class="fas fa-users mr-2"></i>Manajemen Profil Pegawai</h5>
            </div>
            <div class="col-auto">
                <a href="<?= site_url('tentangpengadilan/formProfil') ?>" class="btn btn-success rounded-pill">
                    <i class="fas fa-plus mr-1"></i> Tambah Profil Baru
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%;">Urutan</th>
                    <th style="width: 10%;">Foto</th>
                    <th>Nama & Jabatan</th>
                    <th>NIP & Pangkat</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($profil_list)): ?>
                    <?php foreach($profil_list as $profil): ?>
                    <tr>
                        <td class="text-center align-middle"><?= esc($profil['urutan']) ?></td>
                        <td class="text-center align-middle p-2">
                            <?php $gambarPath = 'uploads/pegawai/' . ($profil['foto_pegawai'] ?? 'no-image.jpg'); ?>
                            <img src="<?= base_url(is_file(FCPATH . $gambarPath) ? $gambarPath : 'assets/img/placeholder.png') ?>" 
                                 alt="<?= esc($profil['nama_pegawai'] ?? 'Profil') ?>" 
                                 class="img-thumbnail rounded-circle" 
                                 style="height: 60px; width: 60px; object-fit: cover;">
                        </td>
                        <td class="align-middle">
                            <strong><?= esc($profil['nama_pegawai']) ?></strong><br>
                            <small class="text-muted"><?= esc($profil['jabatan']) ?></small>
                        </td>
                        <td class="align-middle">
                            <span class="d-block">NIP: <?= esc($profil['nip']) ?></span>
                            <small class="text-muted">Pangkat: <?= esc($profil['pangkat_gol']) ?></small>
                        </td>
                        
                        <td class="align-middle">
                            <span class="badge badge-pill kategori-badge"><?= esc(ucfirst($profil['kategori'])) ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <a href="<?= site_url('tentangpengadilan/formProfil/' . ($profil['id'] ?? '')) ?>" class="btn btn-sm btn-info rounded-pill px-3 mr-1" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="<?= site_url('tentangpengadilan/hapusProfil/'. ($profil['id'] ?? '')) ?>" class="btn btn-sm btn-danger rounded-pill px-3 btn-hapus" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Belum ada data profil pegawai.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .kategori-badge {
        /* Latar hijau muda */
        background-color: #d4edda; 
        
        /* Teks hijau tua (agar kontras) */
        color: #155724; 
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({ "order": [[ 4, "asc" ], [ 0, "asc" ]] }); // Urutkan berdasarkan Kategori, lalu Urutan
    });
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