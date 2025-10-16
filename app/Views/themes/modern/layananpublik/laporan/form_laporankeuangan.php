<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form action="<?= site_url('layananpublik/simpanLaporanKeuangan') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $laporan['id'] ?? '' ?>">

            <div class="form-group">
                <label class="font-weight-bold">Judul Laporan</label>
                <input type="text" name="judul_laporan" class="form-control" placeholder="Contoh: Laporan Realisasi Anggaran Bulan Januari" value="<?= old('judul_laporan', $laporan['judul_laporan'] ?? '') ?>" required>
            </div>
             <div class="form-group">
                <label class="font-weight-bold">Link Laporan (URL Google Drive, dll)</label>
                <input type="url" name="link_laporan" class="form-control" placeholder="https://drive.google.com/..." value="<?= old('link_laporan', $laporan['link_laporan'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2025" value="<?= old('tahun', $laporan['tahun'] ?? date('Y')) ?>" required>
                </div>
                 <div class="form-group col-md-6">
                    <label class="font-weight-bold">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $laporan['urutan'] ?? '100') ?>">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            <a href="<?= site_url('layananpublik/laporanKeuangan') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>