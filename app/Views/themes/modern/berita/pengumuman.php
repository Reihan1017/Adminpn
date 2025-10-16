<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Manajemen Pengumuman</h4>
        <a href="<?= site_url('pengumuman/formPengumuman') ?>" class="btn btn-primary">Tambah Pengumuman Baru</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tanggal Publish</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($pengumuman_list as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($item['judul']) ?></td>
                    <td><?= $item['tanggal_publish'] ?></td>
                    <td>
                        <a href="<?= site_url('pengumuman/formPengumuman/' . $item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('pengumuman/hapusPengumuman/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>