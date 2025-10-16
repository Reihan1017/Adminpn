<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Manajemen Slider</h3>
  <a href="<?= site_url('slider/create') ?>" class="btn btn-primary">Tambah Slider</a>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Gambar</th>
      <th>Judul</th>
      <th>Urutan</th>
      <th>Status</th>
      <th width="160">Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($sliders as $s): ?>
    <tr>
      <td><img src="<?= base_url('uploads/slider/'.$s['gambar']) ?>" alt="" style="height:60px"></td>
      <td>
        <div class="fw-bold"><?= esc($s['judul']) ?></div>
        <small><?= esc($s['deskripsi']) ?></small>
      </td>
      <td><?= (int)$s['urutan'] ?></td>
      <td><?= $s['status'] ? 'Aktif' : 'Nonaktif' ?></td>
      <td>
        <a class="btn btn-sm btn-warning" href="<?= site_url('slider/edit/'.$s['id']) ?>">Edit</a>
        <a class="btn btn-sm btn-danger" href="<?= site_url('slider/delete/'.$s['id']) ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
