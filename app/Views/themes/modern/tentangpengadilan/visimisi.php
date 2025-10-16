<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4>Pengaturan Halaman Visi dan Misi</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= site_url('tentangpengadilan/simpanVisiMisi') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <fieldset class="border rounded p-3 mb-4 bg-light">
                <legend class="w-auto px-2 h5">Pengaturan Visi</legend>
                <div class="form-group">
                    <label for="teks_visi">Teks Visi</label>
                    <textarea id="teks_visi" name="teks_visi" class="form-control" rows="3"><?= esc($visi['teks_visi'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="gambar_visi">Ganti Gambar Visi</label>
                    <input type="file" id="gambar_visi" name="gambar_visi" class="form-control-file">
                    <?php if (!empty($visi['gambar_visi'])): ?>
                        <div class="mt-2">
                            <p class="mb-1"><small>Gambar saat ini:</small></p>
                            <img src="/uploads/profil/<?= esc($visi['gambar_visi']) ?>" alt="Gambar Visi" style="max-width: 300px; border: 1px solid #ddd; padding: 5px; background: white;">
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>

            <fieldset class="border rounded p-3 mb-4">
                <legend class="w-auto px-2 h5">Pengaturan Misi</legend>
                
                <?php foreach ($misi_list as $misi): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <p><strong>Ikon Saat Ini</strong></p>
                                <?php if (!empty($misi['gambar_misi'])): ?>
                                    <img src="/uploads/profil/<?= esc($misi['gambar_misi']) ?>" alt="Gambar Misi" class="img-thumbnail" style="max-width: 120px;">
                                <?php else: ?>
                                    <div class="text-muted">[Belum ada gambar]</div>
                                <?php endif; ?>
                                <hr class="d-md-none">
                            </div>

                            <div class="col-md-9">
                                <input type="hidden" name="misi_id[]" value="<?= esc($misi['id']) ?>">
                                
                                <div class="form-group">
                                    <label><strong>Teks Misi #<?= esc($misi['id']) ?></strong></label>
                                    <input type="text" name="teks_misi[]" class="form-control" value="<?= esc($misi['teks_misi']) ?>">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Ganti Ikon/Gambar</label>
                                        <input type="file" name="gambar_misi_<?= esc($misi['id']) ?>" class="form-control-file">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Urutan Tampil</label>
                                        <input type="number" name="urutan_misi[]" class="form-control" value="<?= esc($misi['urutan']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </fieldset>

            <button type="submit" class="btn btn-primary">Simpan Semua Perubahan</button>
        </form>
    </div>
</div>