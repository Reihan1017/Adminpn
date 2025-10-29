<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
    </div>
    <div class="card-body">
        <?php
        if (!empty($message)) {
            show_message($message);
        } ?>

        <a href="<?= base_url('pengumuman/create') ?>" class="btn btn-success btn-xs">
            <i class="fas fa-plus pe-1"></i> Tambah Pengumuman</a>

        <hr />
        
        <form method="post" action="<?= base_url('pengumuman/delete') // Action untuk delete all ?>">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 80px;">Gambar</th>
                    <th>Judul Pengumuman</th>
                    <th style="width: 100px;">File PDF</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 150px;">Tgl Terbit</th>
                    <th style="width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                helper('html');
                $no = 1;

                foreach ($pengumuman as $val) {
                    
                    // Tampilan Gambar
                    $gambar_display = '-';
                    if (!empty($val['gambar'])) {
                        $gambar_display = '<img src="'.base_url('public/uploads/pengumuman/gambar/' . $val['gambar']).'" style="width: 60px; height: 60px; object-fit: cover;"/>';
                    }

                    // Tampilan PDF
                    $pdf_display = '-';
                    if (!empty($val['file_pdf'])) {
                        $pdf_display = '<a href="'.base_url('public/uploads/pengumuman/pdf/' . $val['file_pdf']).'" target="_blank">Lihat PDF</a>';
                    }

                    echo '<tr>
                        <td>' . $no . '</td>
                        <td>' . $gambar_display . '</td>
                        <td><strong>' . $val['judul'] . '</strong></td>
                        <td>' . $pdf_display . '</td>
                        <td>' . ucfirst($val['status']) . '</td>
                        <td>' . ($val['tgl_terbit'] ? date('d M Y H:i', strtotime($val['tgl_terbit'])) : '-') . '</td>
                        <td>' . btn_action([
                            'edit' => ['url' => base_url('pengumuman/edit/' . $val['id'])],
                            'delete' => ['url' => '#',
                                         'id' => $val['id'],
                                         'delete-title' => 'Hapus pengumuman: <strong>' . $val['judul'] . '</strong>?']
                        ]) . '</td>
                    </tr>';
                    $no++;
                }
                ?>
            </tbody>
        </table>
        </form>

        <?php if (!empty($message_delete)) { show_message($message_delete); }?>
    </div>
</div>