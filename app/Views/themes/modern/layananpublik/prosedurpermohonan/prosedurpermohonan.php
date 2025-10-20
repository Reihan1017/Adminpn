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
        <h4 class="mb-0">Pengaturan Halaman Prosedur Permohonan Informasi</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/prosedurPermohonanInformasi') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Teks Prosedur</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="20" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
            </div>

            <hr>

            <div class="form-group">
                <label for="file_upload" class="font-weight-bold">Ganti Gambar/PDF Alur</label>
                <input type="file" id="file_upload" name="file_upload" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Format: PDF, JPG, PNG. Ukuran maksimal 5MB.</small>
            </div>

            <?php if (!empty($halaman['gambar_halaman'])): ?>
                <div class="mt-4">
                    <p><strong>File/Gambar Saat Ini:</strong></p>
                    <?php $filePath = 'uploads/dokumen/' . esc($halaman['gambar_halaman']); ?>
                    <?php $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); ?>
                    
                    <?php if ($ext === 'pdf'): ?>
                        <a href="<?= base_url($filePath) ?>" target="_blank" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat File PDF: <?= esc($halaman['gambar_halaman']) ?>
                        </a>
                    <?php else: ?>
                        <img src="<?= base_url($filePath) ?>" 
                             alt="Alur Prosedur" 
                             class="img-fluid img-thumbnail" 
                             style="max-width: 500px;">
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada file/gambar alur yang diunggah.</div>
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
    height: 500, // Tingginya disesuaikan
    menubar: false,
    plugins: 'lists link image table code help wordcount autoresize',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link | code'
  });

  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>