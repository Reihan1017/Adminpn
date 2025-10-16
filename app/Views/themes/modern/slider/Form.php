<?php
$errors = session('errors') ?? [];
$mode   = $mode ?? 'create';
$action = ($mode === 'edit')
    ? site_url('slider/update/'.$row['id'])
    : site_url('slider/store');
?>

<?php if ($errors): ?>
<div class="alert alert-danger">
  <ul class="mb-0">
    <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<h3><?= $mode === 'edit' ? 'Edit' : 'Tambah' ?> Slider</h3>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label>Judul</label>
    <input type="text" name="judul" class="form-control" value="<?= esc($row['judul'] ?? old('judul')) ?>" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3"><?= esc($row['deskripsi'] ?? old('deskripsi')) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Link (opsional)</label>
    <input type="url" name="link" class="form-control" value="<?= esc($row['link'] ?? old('link')) ?>" placeholder="https://...">
  </div>

  <div class="mb-3">
    <label>Gambar <?= ($mode==='edit' && !empty($row['gambar'])) ? '(biarkan kosong jika tidak diganti)' : '' ?></label>
    <input type="file" name="gambar" class="form-control" <?= $mode==='edit' ? '' : 'required' ?>>
    <?php if ($mode==='edit' && !empty($row['gambar'])): ?>
      <div class="mt-2">
        <img src="<?= base_url('uploads/slider/'.$row['gambar']) ?>" style="height:80px">
      </div>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label>Urutan</label>
    <input type="number" name="urutan" class="form-control" value="<?= esc($row['urutan'] ?? old('urutan', 0)) ?>">
  </div>

  <div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
      <option value="1" <?= isset($row['status']) && $row['status']==1 ? 'selected':'' ?>>Aktif</option>
      <option value="0" <?= isset($row['status']) && $row['status']==0 ? 'selected':'' ?>>Nonaktif</option>
    </select>
  </div>

  <button class="btn btn-primary">Simpan</button>
  <a href="<?= site_url('slider') ?>" class="btn btn-secondary">Kembali</a>
</form>
