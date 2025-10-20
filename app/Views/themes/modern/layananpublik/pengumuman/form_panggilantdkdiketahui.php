<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form action="<?= site_url('layananpublik/simpanPanggilanTdkDiketahui') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $panggilan['id'] ?? '' ?>">

            <div class="form-group">
                <label class="font-weight-bold">Judul Panggilan</label>
                <input type="text" name="judul_panggilan" class="form-control" placeholder="Contoh: Panggilan Sidang Perkara Gugatan No 23" value="<?= old('judul_panggilan', $panggilan['judul_panggilan'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Deskripsi Singkat (Opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= old('deskripsi', $panggilan['deskripsi'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $panggilan['urutan'] ?? '100') ?>" style="width: 120px;">
            </div>
            <hr>
            <div class="form-group">
                <label class="font-weight-bold">Upload File (PDF/Gambar)</label>
                <input type="file" name="file_panggilan" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" <?= empty($panggilan['id']) ? 'required' : '' ?>>
                <?php if (!empty($panggilan['file_panggilan'])): ?>
                    <div class="alert alert-info mt-3">
                        File saat ini: <a href="<?= base_url('uploads/dokumen/' . esc($panggilan['file_panggilan'])) ?>" target="_blank"><?= esc($panggilan['file_panggilan']) ?></a>
                        <?php $ext = pathinfo($panggilan['file_panggilan'], PATHINFO_EXTENSION); ?>
                        <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                            <br><img src="<?= base_url('uploads/dokumen/' . esc($panggilan['file_panggilan'])) ?>" class="img-thumbnail mt-2" style="max-height: 200px;">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Panggilan</button>
            <a href="<?= site_url('layananpublik/panggilanTdkDiketahui') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>