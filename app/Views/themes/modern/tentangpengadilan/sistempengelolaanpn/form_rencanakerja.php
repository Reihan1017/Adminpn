<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger shadow-sm">
        <strong>Gagal menyimpan!</strong>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h4 class="mb-0"><?= esc($current_module['judul_module']) ?></h4>
    </div>
    <div class="card-body">
        <form action="<?= site_url('tentangpengadilan/simpanRencanaKerja') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $dokumen['id'] ?? '' ?>">

            <div class="form-group">
                <label class="font-weight-bold">Judul Dokumen</label>
                <input type="text" name="judul_dokumen" class="form-control" placeholder="Contoh: DIPA 01 Badan Urusan Administrasi (BUA)" value="<?= old('judul_dokumen', $dokumen['judul_dokumen'] ?? '') ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Tahun Anggaran</label>
                    <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2025" value="<?= old('tahun', $dokumen['tahun'] ?? date('Y')) ?>">
                </div>
                 <div class="form-group col-md-6">
                    <label class="font-weight-bold">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $dokumen['urutan'] ?? '100') ?>">
                    <small class="form-text text-muted">Angka lebih kecil akan tampil lebih dulu.</small>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="font-weight-bold">Upload File PDF</label>
                <input type="file" name="file_dokumen" class="form-control-file" accept=".pdf" <?= empty($dokumen['id']) ? 'required' : '' ?>>
                <small class="form-text text-muted">Format: .pdf. Ukuran maks 10MB. Wajib diisi saat menambah data baru.</small>
                <?php if (!empty($dokumen['file_dokumen'])): ?>
                    <div class="alert alert-info mt-3">
                        File saat ini: <a href="<?= base_url('uploads/dokumen/' . esc($dokumen['file_dokumen'])) ?>" target="_blank"><?= esc($dokumen['file_dokumen']) ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
            <a href="<?= site_url('tentangpengadilan/rencanaKerja') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Tangkap semua tombol yang memiliki class '.btn-hapus'
    const deleteButtons = document.querySelectorAll('.btn-hapus');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // 2. Hentikan aksi default link agar tidak langsung pindah halaman
            event.preventDefault(); 
            
            const deleteUrl = this.getAttribute('href'); // Ambil URL hapus dari link

            // 3. Tampilkan konfirmasi SweetAlert2
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                // 4. Jika pengguna menekan tombol "Ya, hapus!"
                if (result.isConfirmed) {
                    // Arahkan browser ke URL hapus
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>

</body>