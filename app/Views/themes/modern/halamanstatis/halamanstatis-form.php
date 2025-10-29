<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
    </div>

    <div class="card-body">
        <?php
        helper('html');

        if (!empty($message)) {
            show_message($message);
        }

        $formAction = isset($halaman['id'])
            ? base_url('halamanstatis/update/' . $halaman['id'])
            : base_url('halamanstatis/store');

        ?>

        <form method="post" action="<?= $formAction ?>" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="control-label mb-2">Judul Halaman <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="judul"
                            value="<?= set_value('judul', @$halaman['judul']) ?>" required />
                    </div>

                    <div class="mb-3">
                        <label class="control-label mb-2">Konten</label>
                        <textarea name="konten" class="form-control tinymce"
                            rows="25"><?= set_value('konten', @$halaman['konten']) ?></textarea>
                    </div>

                </div>

                <div class="col-md-4">
                    
                    <div class="card card-body mb-3">
                        <h6 class="mb-3">Pengaturan Data</h6>

                        <div class="mb-3">
                            <label class="control-label mb-2">Label Pencarian</label>
                            <input class="form-control" type="text" name="label"
                                value="<?= set_value('label', @$halaman['label']) ?>" />
                            <small>Contoh: 'kontak-kami', 'footer-kolom-1'.</small>
                        </div>
                    </div>


                    <div class="card card-body mb-3">
                        <h6 class="mb-3">Pengaturan Penerbitan</h6>

                        <div class="mb-3">
                            <label class="control-label mb-2">Tanggal Terbit</label>
                            <input class="form-control" type="datetime-local" name="tgl_terbit"
                                value="<?= set_value('tgl_terbit', @$halaman['tgl_terbit'] ? date('Y-m-d\TH:i', strtotime($halaman['tgl_terbit'])) : '') ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="control-label mb-2">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="publish" <?= (isset($halaman['status']) && $halaman['status'] == 'publish') ? 'selected' : '' ?>>Publish</option>
                                <option value="draft" <?= (isset($halaman['status']) && $halaman['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="card card-body mb-3">
                        <h6 class="mb-3">Foto Unggulan</h6>

                        <?php if (isset($halaman['foto']) && !empty($halaman['foto'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('public/uploads/halaman/' . $halaman['foto']) ?>" alt=""
                                    class="img-fluid" style="max-height: 200px; width: auto;" />
                            </div>
                        <?php endif; ?>

                        <input class="form-control" type="file" name="foto" />
                        <small>Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('halamanstatis') ?>" class="btn btn-light">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>