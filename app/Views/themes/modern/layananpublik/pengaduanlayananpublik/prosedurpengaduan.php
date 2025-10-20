<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (!empty($pesan_gagal)): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach ($pesan_gagal as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0">Pengaturan Halaman Prosedur Pengaduan</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/prosedurPengaduan') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Konten Halaman Prosedur</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="20" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
            </div>

            <hr>

            <h5 class="mt-4 mb-3">Pengaturan Tombol "Pengaduan Lapor Disini"</h5>
            <div class="form-group">
                <label for="pengaduan_url" class="font-weight-bold">URL Tujuan Link</label>
                <input type="url" id="pengaduan_url" name="pengaduan_url" class="form-control form-control-lg" 
                       placeholder="https://siwas.mahkamahagung.go.id/"
                       value="<?= old('pengaduan_url', $setting_url['nilai_pengaturan'] ?? '') ?>" required>
                <small class="form-text text-muted">URL lengkap tujuan ketika tombol diklik.</small>
            </div>
            
            <div class="form-group">
                <label for="pengaduan_image" class="font-weight-bold">Ganti Gambar Tombol</label>
                <input type="file" id="pengaduan_image" name="pengaduan_image" class="form-control-file" accept=".jpg,.jpeg,.png,.gif">
                <small class="form-text text-muted">Format: JPG, PNG, GIF. Ukuran maks 1MB. Kosongkan jika tidak ingin mengubah.</small>
                 <?php if (!empty($setting_image['nilai_pengaturan'])): ?>
                    <div class="mt-3">
                        <p><strong>Gambar Tombol Saat Ini:</strong></p>
                        <img src="<?= base_url('uploads/images/' . esc($setting_image['nilai_pengaturan'])) ?>" 
                             alt="Tombol Pengaduan" 
                             class="img-thumbnail" 
                             style="max-height: 80px;">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#isi_halaman',
    height: 500, 
    menubar: false,
    plugins: 'lists link image table code help wordcount autoresize',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link | code'
  });

  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>