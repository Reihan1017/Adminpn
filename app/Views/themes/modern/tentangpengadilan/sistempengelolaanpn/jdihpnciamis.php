<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (!empty($pesan_gagal)): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul>
            <?php foreach ($pesan_gagal as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0">Pengaturan Link JDIH PN Ciamis</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/jdihPnCiamis') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="url" class="font-weight-bold">URL Tujuan JDIH</label>
                <p class="text-muted"><small>Masukkan URL lengkap (termasuk `https://`) dari situs yang akan dituju ketika tombol di halaman depan diklik.</small></p>
                
                <input type="url" id="url" name="url" class="form-control form-control-lg" 
                       placeholder="https://jdih.pn-ciamis.go.id/"
                       value="<?= old('url', $pengaturan['nilai_pengaturan'] ?? '') ?>" required>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan URL</button>
        </form>
    </div>
</div>