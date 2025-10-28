<div class="card">
    <div class="card-body">
        <?php
        if (!empty($message)) {
            show_message($message);
        } ?>

        <a href="<?= base_url('halamanstatis/create') ?>" class="btn btn-success btn-xs">
            <i class="fas fa-plus pe-1"></i> Tambah Halaman</a>

        <hr />
        
        <form method="post" action="<?= base_url('halamanstatis/delete') ?>">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>Judul Halaman</th>
                    <th style="width: 150px;">Label</th> <th style="width: 100px;">Status</th>
                    <th style="width: 150px;">Tgl Update</th>
                    <th style="width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                helper('html');
                
                // Hapus: Fungsi rekursif display_page_rows()
                
                $no = 1;
                // $halaman_list diisi dari Controller yang sudah dirubah
                if (!empty($halaman_list)) { 
                    foreach ($halaman_list as $val) {
                        
                        $foto_display = '-';
                        if (!empty($val['foto'])) {
                            $foto_display = '<img src="'.base_url('public/uploads/halaman/' . $val['foto']).'" style="width: 60px; height: 60px; object-fit: cover;"/>';
                        }
                        
                        // Tampilkan baris tabel
                        echo '<tr>
                            <td>' . $no . '</td>
                            <td>' . $foto_display . '</td>
                            <td><strong>' . $val['judul'] . '</strong></td>
                            <td>' . ($val['label'] ?: '-') . '</td> <td>' . ucfirst($val['status']) . '</td>
                            <td>' . $val['updated_at'] . '</td>
                            <td>' . btn_action([
                                'edit' => ['url' => base_url('halamanstatis/edit/' . $val['id'])],
                                'delete' => ['url' => '#',
                                             'id' => $val['id'],
                                             'delete-title' => 'Hapus halaman: <strong>' . $val['judul'] . '</strong>?']
                            ]) . '</td>
                        </tr>';
                        
                        $no++; 
                    }
                }
                ?>
            </tbody>
        </table>
        </form>

        <?php if (!empty($message_delete)) { show_message($message_delete); }?>
    </div>
</div>