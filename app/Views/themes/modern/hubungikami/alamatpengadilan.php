<?php if (!empty($pesan_sukses)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($pesan_sukses); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4>Pengaturan Alamat & Peta</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('hubungikami/alamatPengadilan'); ?>">
            
            <div class="form-group">
                <label for="nama_kantor">Nama Kantor</label>
                <input type="text" id="nama_kantor" name="nama_kantor" class="form-control" value="<?= htmlspecialchars($kontak['nama_kantor'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3"><?= htmlspecialchars($kontak['alamat'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" id="telepon" name="telepon" class="form-control" value="<?= htmlspecialchars($kontak['telepon'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="fax">Fax</label>
                <input type="text" id="fax" name="fax" class="form-control" value="<?= htmlspecialchars($kontak['fax'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($kontak['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="website">Website</label>
                <input type="text" id="website" name="website" class="form-control" value="<?= htmlspecialchars($kontak['website'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="google_maps_embed">Kode Embed Google Maps</label>
                <textarea id="google_maps_embed" name="google_maps_embed" class="form-control" rows="5"><?= htmlspecialchars($kontak['google_maps_embed'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>