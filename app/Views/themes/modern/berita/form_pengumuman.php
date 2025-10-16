<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <strong>Gagal menyimpan!</strong>
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4><?= esc($current_module['judul_module']) ?></h4>
    </div>
    <div class="card-body">
        <form action="<?= site_url('pengumuman/simpanPengumuman') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $pengumuman['id'] ?? '' ?>">

            <div class="form-group">
                <label for="judul">Judul Pengumuman</label>
                <input type="text" id="judul" name="judul" class="form-control" value="<?= old('judul', $pengumuman['judul'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="tanggal_publish">Tanggal Publish</label>
                <input type="datetime-local" id="tanggal_publish" name="tanggal_publish" class="form-control" value="<?= old('tanggal_publish', $pengumuman['tanggal_publish'] ?? date('Y-m-d\TH:i')) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" required><?= old('deskripsi', $pengumuman['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="file_pengumuman">Upload File (Gambar atau PDF)</label>
                <input type="file" id="file_pengumuman" name="file_pengumuman" class="form-control-file">
                <?php if (!empty($pengumuman['file_pengumuman'])): ?>
                    <small class="form-text text-muted">File saat ini: <?= esc($pengumuman['file_pengumuman']) ?>. Kosongkan jika tidak ingin mengubah.</small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
            <a href="<?= site_url('pengumuman/pengumuman') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>