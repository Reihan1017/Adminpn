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
        <h4 class="mb-0">Pengaturan Halaman Struktur Organisasi</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/struktur') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="judul_halaman" class="font-weight-bold">Judul Halaman</label>
                <input type="text" id="judul_halaman" name="judul_halaman" class="form-control" 
                       value="<?= esc($halaman['judul_halaman'] ?? '') ?>" required>
            </div>

            <hr>

            <div class="form-group">
                <label for="gambar_halaman" class="font-weight-bold">Ganti Gambar Struktur Organisasi</label>
                <input type="file" id="gambar_halaman" name="gambar_halaman" class="form-control-file">
                <small class="form-text text-muted">Format yang diizinkan: JPG, PNG. Ukuran maksimal 3MB. Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>

            <?php if (!empty($halaman['gambar_halaman'])): ?>
                <div class="mt-4">
                    <p><strong>Gambar Saat Ini:</strong></p>
                    
                    <img src="<?= base_url('uploads/profil/' . esc($halaman['gambar_halaman'])) ?>" 
                         alt="Struktur Organisasi" 
                         class="img-fluid img-thumbnail" 
                         style="max-width: 800px;">
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada gambar struktur organisasi yang diunggah.</div>
            <?php endif; ?>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>