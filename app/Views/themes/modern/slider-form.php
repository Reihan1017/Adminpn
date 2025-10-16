<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?= $title ?></h5>
	</div>
	
	<div class="card-body">
		<?php helper('html'); ?>
		
		<?php if (!empty($message)) show_message($message); ?>
		
		<form method="post" action="<?= current_url() ?>" enctype="multipart/form-data">
			<div class="row mb-3">
				<div class="col-sm-2">
					<label for="judul">Judul</label>
				</div>
				<div class="col-sm-6">
					<input type="text" name="judul" id="judul" value="<?= @$slider['judul'] ?>" class="form-control" required>
				</div>
			</div>

			<div class="row mb-3">
				<div class="col-sm-2">
					<label for="link">Link</label>
				</div>
				<div class="col-sm-6">
					<input type="text" name="link" id="link" value="<?= @$slider['link'] ?>" class="form-control" placeholder="https://example.com">
				</div>
			</div>

			<div class="row mb-3">
				<div class="col-sm-2">
					<label for="status">Status</label>
				</div>
				<div class="col-sm-6">
					<select name="status" id="status" class="form-control">
						<option value="aktif" <?= (@$slider['status'] == 'aktif') ? 'selected' : '' ?>>Aktif</option>
						<option value="nonaktif" <?= (@$slider['status'] == 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
					</select>
				</div>
			</div>

			<div class="row mb-3">
				<div class="col-sm-2">
					<label for="gambar">Gambar</label>
				</div>
				<div class="col-sm-6">
					<input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" <?= empty($slider['gambar']) ? 'required' : '' ?>>
					<?php if (!empty($slider['gambar'])): ?>
						<div class="mt-2">
							<img src="<?= base_url('images/slider'.$slider['gambar']) ?>" width="150" class="img-thumbnail">
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="row mb-3">
				<div class="col-sm-8 text-end">
					<button type="submit" name="submit" class="btn btn-primary">Simpan</button>
					<a href="<?= base_url('slider') ?>" class="btn btn-secondary">Batal</a>
				</div>
			</div>
		</form>
	</div>
</div>
