<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light"><h4 class="mb-0">Pengaturan Konten Utama</h4></div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/simpanPenyandangDisabilitas') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="font-weight-bold">Teks Pengantar</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" rows="8"><?= esc($halaman['isi_halaman'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Ganti Gambar Alur Pelayanan</label>
                <input type="file" name="gambar_halaman" class="form-control-file">
                <?php if (!empty($halaman['gambar_halaman'])): ?>
                    <div class="mt-3"><p><strong>Gambar Saat Ini:</strong></p><img src="<?= base_url('uploads/profil/' . esc($halaman['gambar_halaman'])) ?>" class="img-thumbnail" style="max-width: 500px;"></div>
                <?php endif; ?>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Konten Utama</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Dokumen SOP (Download)</h4>
        <a href="<?= site_url('layananpublik/formDokumenDisabilitas') ?>" class="btn btn-primary">Tambah Dokumen SOP</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                </table>
        </div>
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

  // Skrip ini akan bekerja pada form pertama di halaman ini
  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>