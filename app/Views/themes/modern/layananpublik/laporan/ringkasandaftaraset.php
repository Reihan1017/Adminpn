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
        <h4 class="mb-0">Pengaturan Halaman Ringkasan Daftar Aset</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/ringkasanDaftarAset') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="isi_halaman" class="font-weight-bold">Teks Pengantar</label>
                <textarea id="isi_halaman" name="isi_halaman" class="form-control" 
                          rows="5" required><?= old('isi_halaman', $halaman['isi_halaman'] ?? '') ?></textarea>
            </div>

            <hr>

            <div class="form-group">
                <label for="file_pdf" class="font-weight-bold">Ganti File PDF Laporan Aset</label>
                <input type="file" id="file_pdf" name="file_pdf" class="form-control-file" accept=".pdf">
                <small class="form-text text-muted">Hanya format .pdf yang diizinkan. Ukuran maksimal 10MB.</small>
            </div>

            <?php if (!empty($halaman['gambar_halaman'])): ?>
                <div class="alert alert-info mt-4">
                    <p class="mb-0">
                        <a href="<?= base_url('uploads/dokumen/' . esc($halaman['gambar_halaman'])) ?>" target="_blank" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat File Saat Ini: <?= esc($halaman['gambar_halaman']) ?>
                        </a>
                    </p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada file PDF yang diunggah.</div>
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

  // Skrip penting untuk memastikan isi editor tersimpan saat form di-submit
  document.querySelector('form').addEventListener('submit', function(e) {
      tinymce.triggerSave();
  });
</script>