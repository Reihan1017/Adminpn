<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/editIntroLaporanSurvey') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Konten Teks Pengantar</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="15" required><?= old('isi_halaman', $intro['isi_halaman'] ?? '') ?></textarea>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Teks</button>
            <a href="<?= site_url('layananpublik/laporanSurvey') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#isi_halaman',
    height: 400,
    menubar: false,
    plugins: 'lists link wordcount autoresize',
    toolbar: 'undo redo | bold italic | bullist numlist'
  });
  document.querySelector('form').addEventListener('submit', function(e) { tinymce.triggerSave(); });
</script>