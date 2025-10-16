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
        <h4 class="mb-0">Pengaturan Halaman Kepaniteraan Pidana</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/kepaniteraanPidana') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Konten Halaman</label>
                <p class="text-muted"><small>Gunakan editor di bawah ini untuk menulis dan memformat konten. Gunakan fitur "Heading" untuk membuat sub-judul seperti "Meja Pertama" dan "Numbered List" untuk daftar berurutan.</small></p>
                
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="25" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
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
    height: 600,
    menubar: false,
    plugins: 'lists link image table code help wordcount autoresize',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link | code',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif; font-size: 16px; }'
  });
</script>