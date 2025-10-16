<?php if (!empty($pesan_sukses)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($pesan_sukses); ?>
    </div>
<?php endif; ?>

<?php if (!empty($pesan_gagal)): ?>
    <div class="alert alert-danger">
        <strong>Gagal menyimpan!</strong>
        <ul>
            <?php foreach ($pesan_gagal as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4>Pengaturan Tautan Sosial Media</h4>
        <p>Masukkan URL lengkap (contoh: https://www.facebook.com/pnciamis)</p>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('hubungikami/sosialMedia'); ?>">
            
            <div class="form-group">
                <label for="facebook">Facebook</label>
                <input type="text" id="facebook" name="facebook" class="form-control" placeholder="URL halaman Facebook" 
                       value="<?= htmlspecialchars($sosmed['facebook'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="instagram">Instagram</label>
                <input type="text" id="instagram" name="instagram" class="form-control" placeholder="URL profil Instagram" 
                       value="<?= htmlspecialchars($sosmed['instagram'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="youtube">Youtube</label>
                <input type="text" id="youtube" name="youtube" class="form-control" placeholder="URL channel Youtube" 
                       value="<?= htmlspecialchars($sosmed['youtube'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Alamat email kontak" 
                       value="<?= htmlspecialchars($sosmed['email'] ?? ''); ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>