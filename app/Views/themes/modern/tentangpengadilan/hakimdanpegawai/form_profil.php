<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4>
    </div>
    <div class="card-body">
        <form action="<?= site_url('tentangpengadilan/simpanProfil') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $profil['id'] ?? '' ?>">
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label class="font-weight-bold">Nama Lengkap</label>
                    <input type="text" name="nama_pegawai" class="form-control" value="<?= old('nama_pegawai', $profil['nama_pegawai'] ?? '') ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold">NIP</label>
                    <input type="text" name="nip" class="form-control" value="<?= old('nip', $profil['nip'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="<?= old('jabatan', $profil['jabatan'] ?? '') ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Pangkat / Golongan</label>
                    <input type="text" name="pangkat_gol" class="form-control" value="<?= old('pangkat_gol', $profil['pangkat_gol'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= old('tempat_lahir', $profil['tempat_lahir'] ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir', $profil['tanggal_lahir'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold" for="kategori">Kategori Pegawai <span class="text-danger">*</span></label>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($kategori_list as $key => $value): ?>
                            <option value="<?= esc($key) ?>" <?= (old('kategori', $profil['kategori'] ?? '') == $key) ? 'selected' : '' ?>>
                                <?= esc($value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $profil['urutan'] ?? '100') ?>" style="width: 120px;">
                    <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="font-weight-bold">Upload Foto</label>
                <input type="file" name="foto_pegawai" class="form-control-file">
                <small class="form-text text-muted">Format: JPG, PNG. Ukuran maks 2MB. Kosongkan jika tidak ingin mengubah.</small>
                <?php if (!empty($profil['foto_pegawai'])): ?>
                    <div class="mt-3">
                        <p><strong>Foto Saat Ini:</strong></p>
                        <img src="<?= base_url('uploads/pegawai/' . esc($profil['foto_pegawai'])) ?>" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Profil</button>
            <a href="<?= site_url('tentangpengadilan/profilPegawai') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>