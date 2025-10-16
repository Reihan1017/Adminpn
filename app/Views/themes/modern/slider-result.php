<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	<div class="card-body">
		<?php 
		helper('html');
		echo btn_link([
			'url' => $config->baseURL . 'slider/add',
			'attr' => ['class' => 'btn btn-success btn-xs'],
			'label' => '<i class="fas fa-plus"></i> Tambah Slider'
		]);
		
		echo '<hr/>';
		if (empty($slider)) {
			show_message(['status' => 'error', 'message' => 'Data slider tidak ditemukan']);
		} else {
			?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th width="50">No</th>
							<th>Judul</th>
							<th>Gambar</th>
							<th>Link</th>
							<th>Status</th>
							<th width="120">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$no = 1;
						foreach ($slider as $row): 
							$image_url = base_url('uploads/slider/' . $row['gambar']);
						?>
						<tr>
							<td><?=$no++?></td>
							<td><?=$row['judul']?></td>
							<td>
								<?php if (!empty($row['gambar'])): ?>
									<img src="<?=$image_url?>" class="img-thumbnail" width="100"/>
								<?php endif; ?>
							</td>
							<td>
								<?php if (!empty($row['link'])): ?>
									<a href="<?=$row['link']?>" target="_blank"><?=$row['link']?></a>
								<?php endif; ?>
							</td>
							<td>
								<?php if ($row['status'] == 'aktif'): ?>
									<span class="badge bg-success">Aktif</span>
								<?php else: ?>
									<span class="badge bg-secondary">Nonaktif</span>
								<?php endif; ?>
							</td>
							<td>
								<a href="<?=$config->baseURL . 'slider/edit/' . $row['id_slider']?>" class="btn btn-sm btn-primary">
									<i class="fas fa-edit"></i>
								</a>
								<a href="<?=$config->baseURL . 'slider/delete/' . $row['id_slider']?>" class="btn btn-sm btn-danger" 
								   onclick="return confirm('Yakin ingin menghapus slider ini?')">
									<i class="fas fa-trash"></i>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php
		}
		?>
	</div>
</div>
