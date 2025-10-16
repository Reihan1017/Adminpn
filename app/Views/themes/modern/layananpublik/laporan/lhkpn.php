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
        <h4 class="mb-0">Pengaturan Halaman LHKPN</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/lhkpn') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Teks Pengantar</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="8" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
            </div>

            <hr>

            <div class="form-group">
                <label for="external_link" class="font-weight-bold">Link Laporan (URL Google Drive)</label>
                <input type="url" id="external_link" name="external_link" class="form-control form-control-lg" 
                       placeholder="https://drive.google.com/..."
                       value="<?= old('external_link', $halaman['gambar_halaman'] ?? '') ?>" required>
                <small class="form-text text-muted">Masukkan URL lengkap ke file LHKPN di Google Drive atau sumber lainnya.</small>
            </div>
            
            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/9dbv1pgxxzp507v0r0oxg8sresmaztgtf10ru9c8zwmqfhq7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#isi_halaman',
    height: 300,
    menubar: false,
    plugins: 'lists link wordcount autoresize',
    toolbar: 'undo redo | bold italic | bullist numlist'
  });

  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>