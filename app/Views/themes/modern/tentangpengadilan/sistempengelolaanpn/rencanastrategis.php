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
        <h4 class="mb-0">Pengaturan Halaman Rencana Strategis</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/rencanaStrategis') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="judul_halaman" class="font-weight-bold">Judul Halaman</label>
                <input type="text" id="judul_halaman" name="judul_halaman" class="form-control" 
                       value="<?= esc($halaman['judul_halaman'] ?? '') ?>" required>
            </div>

            <hr>

            <div class="form-group">
                <label for="file_pdf" class="font-weight-bold">Ganti File PDF Rencana Strategis</label>
                <input type="file" id="file_pdf" name="file_pdf" class="form-control-file" accept=".pdf">
                <small class="form-text text-muted">Hanya format .pdf yang diizinkan. Ukuran maksimal 10MB.</small>
            </div>

            <?php if (!empty($halaman['gambar_halaman'])): ?>
                <div class="alert alert-info mt-4">
                    <h5 class="alert-heading">File Saat Ini</h5>
                    <p>Sebuah file sudah terunggah. Anda dapat melihatnya dengan mengklik link di bawah ini.</p>
                    <hr>
                    <p class="mb-0">
                        <a href="<?= base_url('uploads/dokumen/' . esc($halaman['gambar_halaman'])) ?>" target="_blank" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat File: <?= esc($halaman['gambar_halaman']) ?>
                        </a>
                    </p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">Belum ada file Rencana Strategis yang diunggah.</div>
            <?php endif; ?>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>