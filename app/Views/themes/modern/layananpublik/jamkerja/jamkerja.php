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
        <h4 class="mb-0">Pengaturan Halaman Jam Kerja</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('layananpublik/jamKerja') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="file_upload" class="font-weight-bold">Ganti File Jam Kerja (Gambar atau PDF)</label>
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
                             alt="Jam Kerja" 
                             class="img-fluid img-thumbnail" 
                             style="max-width: 500px;">
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada file Jam Kerja yang diunggah.</div>
            <?php endif; ?>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>