<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Laporan SAKIP</h4>
        <a href="<?= site_url('layananpublik/formSakip') ?>" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Laporan</a>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Judul Laporan</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($laporan_list as $laporan): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('uploads/dokumen/' . esc($laporan['file_laporan'])) ?>" target="_blank">
                            <?= esc($laporan['judul_laporan']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= site_url('layananpublik/formSakip/' . $laporan['id']) ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Edit</a>
                        <a href="<?= site_url('layananpublik/hapusSakip/' . $laporan['id']) ?>" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () { $('#dataTable').DataTable(); });
    // SweetAlert delete confirmation script goes here
</script>