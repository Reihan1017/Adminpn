<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Agen Perubahan</h4>
        <a href="<?= site_url('tentangpengadilan/formAgenPerubahan') ?>" class="btn btn-primary">Tambah Agen Baru</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 10%;">Urutan</th>
                        <th>Poster</th>
                        <th>Nama & Jabatan</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($agen_list as $agen): ?>
                    <tr>
                        <td><?= esc($agen['urutan']) ?></td>
                        <td>
                            <img src="<?= base_url('uploads/profil/' . esc($agen['poster_image'])) ?>" class="img-thumbnail" width="150">
                        </td>
                        <td>
                            <strong><?= esc($agen['nama']) ?></strong><br>
                            <small><?= esc($agen['jabatan']) ?></small>
                        </td>
                        <td>
                            <a href="<?= site_url('tentangpengadilan/formAgenPerubahan/' . $agen['id']) ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <a href="<?= site_url('tentangpengadilan/hapusAgenPerubahan/' . $agen['id']) ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>