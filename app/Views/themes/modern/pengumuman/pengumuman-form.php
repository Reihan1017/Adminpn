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

        $formAction = isset($pengumuman['id'])
            ? base_url('pengumuman/update/' . $pengumuman['id'])
            : base_url('pengumuman/store');
        ?>

        <form method="post" action="<?= $formAction ?>" enctype="multipart/form-data">
            <div class="row mb-3">
                
                <div class="col-md-8">
                    
                    <div class="mb-3">
                        <label class="control-label mb-2">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="judul" value="<?= set_value('judul', @$pengumuman['judul']) ?>" required />
                    </div>
                    
                    <div class="mb-3">
                        <label class="control-label mb-2">Konten</label>
                        <textarea name="konten" class="form-control tinymce" rows="25"><?= set_value('konten', @$pengumuman['konten']) ?></textarea>
                    </div>

                </div>
                
                <div class="col-md-4">

                    <div class="card card-body mb-3">
                        <h6 class="mb-3">Pengaturan Penerbitan</h6>

                        <div class="mb-3">
                            <label class="control-label mb-2">Tanggal Terbit</LabeL>
                            <?php
                            $tgl_terbit = @$pengumuman['tgl_terbit'] ?: date('Y-m-d H:i:s');
                            ?>
                            <input class="form-control flatpickr" type="text" name="tgl_terbit" value="<?= set_value('tgl_terbit', date('Y-m-d H:i', strtotime($tgl_terbit))) ?>" required/>
                        </div>
                        
                        <div class="mb-3">
                            <label class="control-label mb-2">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="publish" <?= (isset($pengumuman['status']) && $pengumuman['status'] == 'publish') ? 'selected' : '' ?>>Publish</option>
                                <option value="draft" <?= (isset($pengumuman['status']) && $pengumuman['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="card card-body mb-3">
                        <h6 class="mb-3">Gambar Unggulan</h6>
                        
                        <?php if (isset($pengumuman['gambar']) && !empty($pengumuman['gambar'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('public/uploads/pengumuman/gambar/' . $pengumuman['gambar']) ?>" alt="" class="img-fluid" style="max-height: 200px; width: auto;"/>
                            </div>
                        <?php endif; ?>
                        
                        <input class="form-control" type="file" name="gambar" accept="image/png, image/jpeg, image/gif" />
                        <small>Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>

                    <div class="card card-body mb-3">
                        <h6 class="mb-3">File PDF</h6>
                        
                        <?php if (isset($pengumuman['file_pdf']) && !empty($pengumuman['file_pdf'])): ?>
                            <div class="mb-2">
                                <a href="<?= base_url('public/uploads/pengumuman/pdf/' . $pengumuman['file_pdf']) ?>" target="_blank">Lihat File PDF Saat Ini</a>
                            </div>
                        <?php endif; ?>
                        
                        <input class="form-control" type="file" name="file_pdf" accept="application/pdf" />
                        <small>Kosongkan jika tidak ingin mengubah file PDF.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('pengumuman') ?>" class="btn btn-light">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>