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
                            <a href="<?= site_url('tentangpengadilan/hapusProfil/' . $profil['id']) ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus profil ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>