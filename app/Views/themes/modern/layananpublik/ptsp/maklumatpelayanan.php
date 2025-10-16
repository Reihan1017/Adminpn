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
        <h4 class="mb-0">Pengaturan Halaman Maklumat Pelayanan</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/maklumatPelayanan') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Teks Pengantar</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="10" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
            </div>

            <hr>

            <div class="form-group">
                <label for="gambar_halaman" class="font-weight-bold">Ganti Gambar Maklumat (Sertifikat)</label>
                <input type="file" id="gambar_halaman" name="gambar_halaman" class="form-control-file">
                <small class="form-text text-muted">Format: JPG, PNG. Ukuran maks 3MB. Kosongkan jika tidak ingin mengubah.</small>
            </div>

            <?php if (!empty($halaman['gambar_halaman'])): ?>
                <div class="mt-4">
                    <p><strong>Gambar Saat Ini:</strong></p>
                    <img src="<?= base_url('uploads/profil/' . esc($halaman['gambar_halaman'])) ?>" 
                         alt="Maklumat Pelayanan" 
                         class="img-fluid img-thumbnail" 
                         style="max-width: 500px;">
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada gambar maklumat yang diunggah.</div>
            <?php endif; ?>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#isi_halaman',
    height: 300,
    menubar: false,
    plugins: 'lists link wordcount autoresize',
    toolbar: 'undo redo | bold italic | bullist numlist'
  });

  // Kode penting untuk memastikan isi editor tersimpan
  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>