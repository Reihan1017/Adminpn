<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manajemen Standar Pelayanan</h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="font-weight-bold">Teks Pengantar</h5>
            <a href="<?= site_url('layananpublik/editIntroStandarPelayanan') ?>" class="btn btn-info btn-sm">Edit Teks Ini</a>
        </div>
        <div class="mb-4 p-3 border rounded bg-light">
            <?= $intro['isi_halaman'] ?? '<span class="text-muted">Teks pengantar belum diisi.</span>' ?>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="font-weight-bold">Daftar Dokumen (Download)</h5>
            <a href="<?= site_url('layananpublik/formStandarPelayanan') ?>" class="btn btn-primary">Tambah Dokumen Baru</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 10%;">Urutan</th>
                        <th>Judul Dokumen (Link)</th>
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
                            <a href="<?= site_url('layananpublik/formStandarPelayanan/' . $dokumen['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('layananpublik/hapusStandarPelayanan/' . $dokumen['id']) ?>" class="btn btn-sm btn-danger btn-hapus">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

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

                </tbody>
            </table>
        </div>
    </div>
</div>