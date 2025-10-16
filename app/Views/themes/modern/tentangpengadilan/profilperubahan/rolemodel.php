<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (!empty($pesan_gagal)): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul>
            <?php foreach ($pesan_gagal as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0">Pengaturan Halaman Profil Role Model</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/roleModel') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="poster_image" class="font-weight-bold">Ganti Gambar Poster Role Model</label>
                <input type="file" id="poster_image" name="poster_image" class="form-control-file">
                <small class="form-text text-muted">Format: JPG, PNG. Ukuran maks 3MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                 <?php if (!empty($rolemodel['poster_image'])): ?>
                    <div class="mt-3">
                        <p><strong>Poster Saat Ini:</strong></p>
                        <img src="<?= base_url('uploads/profil/' . esc($rolemodel['poster_image'])) ?>" 
                             alt="Poster Role Model" 
                             class="img-fluid img-thumbnail" 
                             style="max-width: 400px;">
                    </div>
                <?php endif; ?>
            </div>
            
            <hr>
            <h5 class="mt-4">Detail Informasi</h5>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= esc($rolemodel['nama'] ?? '') ?>">
                </div>
                 <div class="form-group col-md-6">
                    <label for="nip">NIP</label>
                    <input type="text" id="nip" name="nip" class="form-control" value="<?= esc($rolemodel['nip'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="pangkat_gol">Pangkat/Gol</label>
                    <input type="text" id="pangkat_gol" name="pangkat_gol" class="form-control" value="<?= esc($rolemodel['pangkat_gol'] ?? '') ?>">
                </div>
                 <div class="form-group col-md-6">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" value="<?= esc($rolemodel['jabatan'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="<?= esc($rolemodel['tempat_lahir'] ?? '') ?>">
                </div>
                 <div class="form-group col-md-6">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="<?= esc($rolemodel['tanggal_lahir'] ?? '') ?>">
                </div>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>