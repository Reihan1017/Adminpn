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
        <h4 class="mb-0">Pengaturan Link Kebijakan</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/kebijakan') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="url" class="font-weight-bold">URL Tujuan Kebijakan</label>
                <p class="text-muted"><small>Masukkan URL lengkap dari situs Kebijakan yang akan dituju.</small></p>
                
                <input type="url" id="url" name="url" class="form-control form-control-lg" 
                       placeholder="https://website.go.id/kebijakan"
                       value="<?= old('url', $pengaturan['nilai_pengaturan'] ?? '') ?>" required>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan URL</button>
        </form>
    </div>
</div>