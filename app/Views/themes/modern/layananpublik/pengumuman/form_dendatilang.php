<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light"><h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4></div>
    <div class="card-body">
        <form action="<?= site_url('layananpublik//simpanDendaTilang') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $tilang['id'] ?? '' ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Tanggal Sidang</label>
                    <input type="date" name="tanggal_sidang" class="form-control" value="<?= old('tanggal_sidang', $tilang['tanggal_sidang'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Nama Pelanggar</label>
                    <input type="text" name="nama" class="form-control" value="<?= old('nama', $tilang['nama'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Nomor Tilang</label>
                    <input type="text" name="nomor_tilang" class="form-control" value="<?= old('nomor_tilang', $tilang['nomor_tilang'] ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Nomor Polisi</label>
                    <input type="text" name="nomor_polisi" class="form-control" value="<?= old('nomor_polisi', $tilang['nomor_polisi'] ?? '') ?>">
                </div>
            </div>

             <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Barang Bukti</label>
                    <input type="text" name="barang_bukti" class="form-control" value="<?= old('barang_bukti', $tilang['barang_bukti'] ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Jumlah Denda (Rp)</label>
                    <input type="number" name="denda" class="form-control" placeholder="Contoh: 50000" value="<?= old('denda', $tilang['denda'] ?? '') ?>" required>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="<?= site_url('layananpublik//dendaTilang') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>