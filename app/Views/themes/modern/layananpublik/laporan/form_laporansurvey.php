<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form action="<?= site_url('layananpublik/simpanLaporanSurvey') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $laporan['id'] ?? '' ?>">

            <div class="form-group">
                <label class="font-weight-bold">Judul Laporan</label>
                <input type="text" name="judul_laporan" class="form-control" placeholder="Contoh: 1. Laporan Hasil Survey..." value="<?= old('judul_laporan', $laporan['judul_laporan'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $laporan['urutan'] ?? '100') ?>" style="width: 120px;">
                <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
            </div>
            <hr>
            <div class="form-group">
                <label class="font-weight-bold">Upload File Laporan (PDF)</label>
                <input type="file" name="file_laporan" class="form-control-file" accept=".pdf" <?= empty($laporan['id']) ? 'required' : '' ?>>
                <?php if (!empty($laporan['file_laporan'])): ?>
                    <div class="alert alert-info mt-3">File saat ini: <a href="<?= base_url('uploads/dokumen/' . esc($laporan['file_laporan'])) ?>" target="_blank"><?= esc($laporan['file_laporan']) ?></a></div>
                <?php endif; ?>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            <a href="<?= site_url('layananpublik/laporanSurvey') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>