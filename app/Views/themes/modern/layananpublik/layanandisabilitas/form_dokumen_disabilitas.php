<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form action="<?= site_url('layananpublik/simpanDokumenDisabilitas') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $dokumen['id'] ?? '' ?>">
            <div class="form-group">
                <label class="font-weight-bold">Judul Dokumen</label>
                <input type="text" name="judul_dokumen" class="form-control" value="<?= old('judul_dokumen', $dokumen['judul_dokumen'] ?? 'Download SOP') ?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $dokumen['urutan'] ?? '100') ?>" style="width: 120px;">
            </div>
            <hr>
            <div class="form-group">
                <label class="font-weight-bold">Upload File PDF</label>
                <input type="file" name="file_dokumen" class="form-control-file" accept=".pdf" <?= empty($dokumen['id']) ? 'required' : '' ?>>
                <?php if (!empty($dokumen['file_dokumen'])): ?>
                    <div class="alert alert-info mt-3">File saat ini: <a href="<?= base_url('uploads/dokumen/' . esc($dokumen['file_dokumen'])) ?>" target="_blank"><?= esc($dokumen['file_dokumen']) ?></a></div>
                <?php endif; ?>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
            <a href="<?= site_url('layananpublik/penyandangDisabilitas') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>