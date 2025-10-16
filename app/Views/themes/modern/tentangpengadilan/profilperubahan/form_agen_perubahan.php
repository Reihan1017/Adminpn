<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4>
    </div>
    <div class="card-body">
        <form action="<?= site_url('tentangpengadilan/simpanAgenPerubahan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $agen['id'] ?? '' ?>">

            <div class="form-group">
                <label class="font-weight-bold">Nama (Opsional)</label>
                <input type="text" name="nama" class="form-control" value="<?= old('nama', $agen['nama'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label class="font-weight-bold">Jabatan (Opsional)</label>
                <input type="text" name="jabatan" class="form-control" value="<?= old('jabatan', $agen['jabatan'] ?? '') ?>">
            </div>

             <div class="form-group">
                <label class="font-weight-bold">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $agen['urutan'] ?? '100') ?>" style="width: 120px;">
                <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
            </div>

            <hr>

            <div class="form-group">
                <label class="font-weight-bold">Upload Poster</label>
                <input type="file" name="poster_image" class="form-control-file" <?= empty($agen['id']) ? 'required' : '' ?>>
                <small class="form-text text-muted">Format: JPG, PNG. Ukuran maks 3MB. Wajib diisi saat menambah data baru.</small>
                <?php if (!empty($agen['poster_image'])): ?>
                    <div class="mt-3">
                        <p><strong>Poster Saat Ini:</strong></p>
                        <img src="<?= base_url('uploads/profil/' . esc($agen['poster_image'])) ?>" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('tentangpengadilan/profilPerubahan') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>