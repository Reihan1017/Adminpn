<?php if (!empty($pesan_sukses)): ?>
    <div class="alert alert-success shadow-sm"><?= esc($pesan_sukses) ?></div>
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
        <h4 class="mb-0">Pengaturan Halaman Pengantar Ketua</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/pengantarKetua') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nama_ketua" class="font-weight-bold">Nama Lengkap Ketua</label>
                <input type="text" id="nama_ketua" name="nama_ketua" class="form-control" 
                       value="<?= old('nama_ketua', $pengantar['nama_ketua'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="jabatan_ketua" class="font-weight-bold">Jabatan</label>
                <input type="text" id="jabatan_ketua" name="jabatan_ketua" class="form-control" 
                       value="<?= old('jabatan_ketua', $pengantar['jabatan_ketua'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="isi_pengantar" class="font-weight-bold">Isi Kata Pengantar</label>
                <textarea id="isi_pengantar" name="isi_pengantar" class="form-control" 
                          rows="12" required><?= old('isi_pengantar', $pengantar['isi_pengantar'] ?? '') ?></textarea>
            </div>

            <hr>

            <div class="form-group">
                <label for="foto_ketua" class="font-weight-bold">Ganti Foto Ketua</label>
                <input type="file" id="foto_ketua" name="foto_ketua" class="form-control-file">
                <small class="form-text text-muted">Format: JPG, PNG. Ukuran maksimal 2MB. Kosongkan jika tidak ingin mengganti foto.</small>
                
                <?php if (!empty($pengantar['foto_ketua'])): ?>
                    <div class="mt-3">
                        <p><strong>Foto Saat Ini:</strong></p>
                        
                        <img src="<?= base_url('uploads/profil/' . esc($pengantar['foto_ketua'])) ?>" 
                             alt="Foto Ketua" 
                             class="img-thumbnail" 
                             style="max-width: 200px;">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>