<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Kode Etik dan Pedoman Perilaku</h4>
        <div>
            <a href="<?= site_url('tentangpengadilan/editIntroKodeEtik') ?>" class="btn btn-info">Edit Teks Pengantar</a>
            
            <a href="<?= site_url('tentangpengadilan/formKodeEtik') ?>" class="btn btn-primary">Tambah Dokumen Baru</a>
        </div>
    </div>
    <div class="card-body">
        
        <div class="mb-4 p-3 border rounded bg-light">
            <h5 class="font-weight-bold">Teks Pengantar:</h5>
            <?= $intro['isi_halaman'] ?? '<span class="text-muted"> </span>' ?>
        </div>
        <hr>

        <h5 class="mb-3">Daftar Dokumen Kode Etik:</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 10%;">Urutan</th>
                        <th>Judul Dokumen</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dokumen_list as $dokumen): ?>
                    <tr>
                        <td><?= esc($dokumen['urutan']) ?></td>
                        <td>
                            <a href="<?= base_url('uploads/dokumen/' . esc($dokumen['file_dokumen'])) ?>" target="_blank">
                                <?= esc($dokumen['judul_dokumen']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= site_url('tentangpengadilan/formKodeEtik/' . $dokumen['id']) ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <a href="<?= site_url('tentangpengadilan/hapusKodeEtik/' . $dokumen['id']) ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>