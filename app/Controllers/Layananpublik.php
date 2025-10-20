<?php

namespace App\Controllers;

class Layananpublik extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url']);
        $this->db = \Config\Database::connect();
    }

    public function ptspJenisLayanan()
    {
        $nama_halaman = 'jenis-layanan-ptsp';
        $this->data['current_module']['judul_module'] = 'Jenis Layanan PTSP';

        if ($this->request->getMethod() === 'post') {
            $rules = [ 'isi_halaman' => 'required' ];
            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                
                session()->setFlashdata('success', 'Data Jenis Layanan PTSP berhasil diperbarui!');
                return redirect()->to('/layanan-publik/ptsp/jenis-layanan');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        
        $this->view('layananpublik/ptsp/jenislayanan.php', $this->data);
    }

/**
 * Menampilkan halaman utama manajemen Standar Pelayanan.
 */
public function standarPelayanan()
{
    $this->data['current_module']['judul_module'] = 'Standar Pelayanan';

    // 1. Ambil data teks pengantar
    $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'standar-pelayanan-intro'])->getRowArray();

    // 2. Ambil daftar dokumen PDF
    $this->data['dokumen_list'] = $this->db->table('standar_pelayanan_dokumen')->orderBy('urutan', 'ASC')->get()->getResultArray();
    
    // 3. Muat view utama
    $this->view('layananpublik/ptsp/standarpelayanan.php', $this->data);
}

/**
 * Menampilkan form untuk mengedit teks pengantar saja.
 */
public function editIntroStandarPelayanan()
{
    $nama_halaman = 'standar-pelayanan-intro';
    $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Standar Pelayanan';

    if ($this->request->getMethod() === 'post') {
        if ($this->validate(['isi_halaman' => 'required'])) {
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
            session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
            return redirect()->to('/layananpublik/standarPelayanan');
        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
    $this->view('layananpublik/ptsp/edit_intro_standarpelayanan.php', $this->data);
}

/**
 * Menampilkan form untuk menambah/mengedit dokumen PDF.
 */
public function formStandarPelayanan($id = null)
{
    $this->data['dokumen'] = null;
    if ($id) {
        $this->data['dokumen'] = $this->db->table('standar_pelayanan_dokumen')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Dokumen Standar Pelayanan';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Dokumen Standar Pelayanan';
    }

    $this->view('layananpublik/ptsp/form_standarpelayanan.php', $this->data);
}

/**
 * Menyimpan data dokumen PDF.
 */

public function simpanStandarPelayanan()
{
    $id = $this->request->getPost('id');

    // Aturan validasi: judul wajib, file harus PDF maks 10MB
    $rules = [
        'judul_dokumen' => 'required',
        'file_dokumen' => 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]'
    ];
    
    // File hanya WAJIB diunggah saat menambah data baru
    if (!$id) {
        $rules['file_dokumen'] = 'uploaded[file_dokumen]|' . $rules['file_dokumen'];
    }

    // Jika validasi GAGAL, kembali ke form dengan pesan error
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Jika validasi SUKSES, lanjutkan proses
    $data = [
        'judul_dokumen' => $this->request->getPost('judul_dokumen'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    $filePdf = $this->request->getFile('file_dokumen');
    if ($filePdf->isValid() && !$filePdf->hasMoved()) {
        $namaFile = $filePdf->getRandomName();
        $filePdf->move('uploads/dokumen', $namaFile);
        $data['file_dokumen'] = $namaFile;
    }

    if ($id) {
        $this->db->table('standar_pelayanan_dokumen')->where('id', $id)->update($data);
    } else {
        $this->db->table('standar_pelayanan_dokumen')->insert($data);
    }
    
    // Arahkan kembali ke halaman daftar dengan pesan sukses
    return redirect()->to('/layananpublik/standarPelayanan')->with('success', 'Dokumen Standar Pelayanan berhasil disimpan!');
}

/**
 * Menghapus dokumen PDF.
 */
public function hapusStandarPelayanan($id)
{
    $dokumen = $this->db->table('standar_pelayanan_dokumen')->getWhere(['id' => $id])->getRow();
    if ($dokumen) {
        if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
            unlink('uploads/dokumen/' . $dokumen->file_dokumen);
        }
        $this->db->table('standar_pelayanan_dokumen')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}

public function maklumatPelayanan()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'maklumat-pelayanan';
    
    $this->data['current_module']['judul_module'] = 'Maklumat Pelayanan';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman'    => 'required',
            'gambar_halaman' => 'max_size[gambar_halaman,3072]|is_image[gambar_halaman]' // Max 3MB
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload gambar jika ada file baru
            $fileGambar = $this->request->getFile('gambar_halaman');
            if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
                
                // Hapus gambar lama dari server
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/profil/' . $oldData->gambar_halaman)) {
                    unlink('uploads/profil/' . $oldData->gambar_halaman);
                }

                // Simpan gambar baru
                $namaGambar = $fileGambar->getRandomName();
                $fileGambar->move('uploads/profil', $namaGambar);
                $dataUpdate['gambar_halaman'] = $namaGambar;
            }

            // Update data di database
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Maklumat Pelayanan berhasil diperbarui!');
            return redirect()->to('/layananpublik/maklumatPelayanan');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view
    $this->view('layananpublik/ptsp/maklumatpelayanan.php', $this->data);
}


public function kompensasiPelayanan()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'kompensasi-pelayanan';
    
    $this->data['current_module']['judul_module'] = 'Kompensasi Pelayanan';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman' => 'required',
            'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload file PDF jika ada yang diunggah
            $filePdf = $this->request->getFile('file_pdf');
            if ($filePdf->isValid() && !$filePdf->hasMoved()) {
                
                // Hapus file lama dari server
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                // Simpan file baru ke 'public/uploads/dokumen'
                $namaFile = $filePdf->getRandomName();
                $filePdf->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile;
            }

            // Update data di database
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Kompensasi Pelayanan berhasil diperbarui!');
            return redirect()->to('/layananpublik/kompensasiPelayanan');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view
    $this->view('layananpublik/ptsp/kompensasipelayanan.php', $this->data);
}

/**
 * Menampilkan halaman utama manajemen Layanan Disabilitas.
 */
public function penyandangDisabilitas()
{
    $this->data['current_module']['judul_module'] = 'Layanan Disabilitas';

    // 1. Ambil data teks pengantar dan gambar alur
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'layanan-disabilitas'])->getRowArray();

    // 2. Ambil daftar dokumen PDF
    $this->data['dokumen_list'] = $this->db->table('dokumen_disabilitas')->orderBy('urutan', 'ASC')->get()->getResultArray();
    
    // 3. Muat view utama
    $this->view('layananpublik/layanandisabilitas/penyandangdisabilitas.php', $this->data);
}

/**
 * Menyimpan perubahan pada teks pengantar dan gambar alur.
 */
public function simpanPenyandangDisabilitas()
{
    $nama_halaman = 'layanan-disabilitas';
    
    $rules = [
        'isi_halaman' => 'required',
        'gambar_halaman' => 'max_size[gambar_halaman,3072]|is_image[gambar_halaman]'
    ];

    if ($this->validate($rules)) {
        $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];

        $fileGambar = $this->request->getFile('gambar_halaman');
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
            if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/profil/' . $oldData->gambar_halaman)) {
                unlink('uploads/profil/' . $oldData->gambar_halaman);
            }
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/profil', $namaGambar);
            $dataUpdate['gambar_halaman'] = $namaGambar;
        }

        $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
        session()->setFlashdata('success', 'Data Layanan Disabilitas berhasil diperbarui!');
    } else {
        session()->setFlashdata('errors', $this->validator->getErrors());
    }
    
    return redirect()->to('/layananpublik/penyandangDisabilitas');
}


/**
 * Menampilkan form untuk menambah/mengedit dokumen PDF (SOP).
 */
public function formDokumenDisabilitas($id = null)
{
    $this->data['dokumen'] = null;
    if ($id) {
        $this->data['dokumen'] = $this->db->table('dokumen_disabilitas')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Dokumen SOP Disabilitas';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Dokumen SOP Disabilitas';
    }
    $this->view('layananpublik/layanandisabilitas/form_dokumen_disabilitas.php', $this->data);
}

/**
 * Menyimpan data dokumen PDF (SOP).
 */
public function simpanDokumenDisabilitas()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_dokumen' => 'required',
        'file_dokumen' => 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]'
    ];
    if (!$id) {
        $rules['file_dokumen'] = 'uploaded[file_dokumen]|' . $rules['file_dokumen'];
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_dokumen' => $this->request->getPost('judul_dokumen'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    $filePdf = $this->request->getFile('file_dokumen');
    if ($filePdf->isValid() && !$filePdf->hasMoved()) {
        $namaFile = $filePdf->getRandomName();
        $filePdf->move('uploads/dokumen', $namaFile);
        $data['file_dokumen'] = $namaFile;
    }

    if ($id) {
        $this->db->table('dokumen_disabilitas')->where('id', $id)->update($data);
    } else {
        $this->db->table('dokumen_disabilitas')->insert($data);
    }
    
    return redirect()->to('/layananpublik/penyandangDisabilitas')->with('success', 'Dokumen SOP berhasil disimpan!');
}

/**
 * Menghapus dokumen PDF (SOP).
 */
public function hapusDokumenDisabilitas($id)
{
    $dokumen = $this->db->table('dokumen_disabilitas')->getWhere(['id' => $id])->getRow();
    if ($dokumen) {
        if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
            unlink('uploads/dokumen/' . $dokumen->file_dokumen);
        }
        $this->db->table('dokumen_disabilitas')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Dokumen SOP berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}

// Tambahkan method ini di dalam class Layananpublik

public function saranaPrasaranaDisabilitas()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'sarana-disabilitas';
    
    $this->data['current_module']['judul_module'] = 'Sarana & Prasarana Disabilitas';

    if ($this->request->getMethod() === 'post') {
        
        // Aturan validasi: Teks wajib, file harus PDF maks 10MB
        $rules = [
            'isi_halaman' => 'required',
            'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]'
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload file PDF jika ada yang diunggah
            $filePdf = $this->request->getFile('file_pdf');
            if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
                
                // Hapus file lama dari server
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                // Simpan file baru ke 'public/uploads/dokumen'
                $namaFile = $filePdf->getRandomName();
                $filePdf->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile; // Nama file disimpan di kolom 'gambar_halaman'
            }

            // Update data di database
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Sarana & Prasarana Disabilitas berhasil diperbarui!');
            return redirect()->to('/layananpublik/saranaPrasaranaDisabilitas');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/layanandisabilitas/saranaprasaranadisabilitas.php', $this->data);
}

public function ringkasanLaporan()
{
    $this->data['current_module']['judul_module'] = 'Laporan Kinerja Instansi Pemerintah (LKjIP)';

    // Ambil semua data laporan, diurutkan berdasarkan tahun
    $this->data['laporan_list'] = $this->db->table('lkjip_laporan')
                                          ->orderBy('tahun', 'DESC')
                                          ->orderBy('urutan', 'ASC')
                                          ->get()->getResultArray();
    
    // Muat view utama
    $this->view('layananpublik/laporan/ringkasanlaporan.php', $this->data);
}

/**
 * Menampilkan form untuk menambah/mengedit laporan.
 */
public function formRingkasanLaporan($id = null)
{
    $this->data['laporan'] = null;
    if ($id) {
        $this->data['laporan'] = $this->db->table('lkjip_laporan')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Laporan LKjIP';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Laporan LKjIP';
    }

    // Muat view form
    $this->view('layananpublik/laporan/form_ringkasanlaporan.php', $this->data);
}

/**
 * Menyimpan data laporan.
 */
public function simpanRingkasanLaporan()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_laporan' => 'required',
        'tahun' => 'required|integer',
        'file_laporan' => 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]' // PDF, maks 10MB
    ];
    if (!$id) {
        $rules['file_laporan'] = 'uploaded[file_laporan]|' . $rules['file_laporan'];
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_laporan' => $this->request->getPost('judul_laporan'),
        'tahun'         => $this->request->getPost('tahun'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    $filePdf = $this->request->getFile('file_laporan');
    if ($filePdf->isValid() && !$filePdf->hasMoved()) {
        $namaFile = $filePdf->getRandomName();
        $filePdf->move('uploads/dokumen', $namaFile);
        $data['file_laporan'] = $namaFile;
    }

    if ($id) {
        $this->db->table('lkjip_laporan')->where('id', $id)->update($data);
    } else {
        $this->db->table('lkjip_laporan')->insert($data);
    }
    
    return redirect()->to('/layananpublik/ringkasanLaporan')->with('success', 'Data Laporan LKjIP berhasil disimpan!');
}

/**
 * Menghapus data laporan.
 */
public function hapusRingkasanLaporan($id)
{
    $laporan = $this->db->table('lkjip_laporan')->getWhere(['id' => $id])->getRow();
    if ($laporan) {
        if (!empty($laporan->file_laporan) && file_exists('uploads/dokumen/' . $laporan->file_laporan)) {
            unlink('uploads/dokumen/' . $laporan->file_laporan);
        }
        $this->db->table('lkjip_laporan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}

// Tambahkan method ini di dalam class Layananpublik

public function ringkasanDaftarAset()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'ringkasan-aset';
    
    $this->data['current_module']['judul_module'] = 'Ringkasan Daftar Aset';

    if ($this->request->getMethod() === 'post') {
        
        // Aturan validasi: Teks wajib, file harus PDF maks 10MB
        $rules = [
            'isi_halaman' => 'required',
            'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]'
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload file PDF jika ada yang diunggah
            $filePdf = $this->request->getFile('file_pdf');
            if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
                
                // Hapus file lama dari server
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                // Simpan file baru ke 'public/uploads/dokumen'
                $namaFile = $filePdf->getRandomName();
                $filePdf->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile; // Nama file disimpan di kolom 'gambar_halaman'
            }

            // Update data di database
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Ringkasan Daftar Aset berhasil diperbarui!');
            return redirect()->to('/layananpublik/ringkasanDaftarAset');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/laporan/ringkasandaftaraset.php', $this->data);
}


public function laporanTahunan()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'laporan-tahunan';
    
    $this->data['current_module']['judul_module'] = 'Laporan Tahunan';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman' => 'required',
            'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload file PDF jika ada yang diunggah
            $filePdf = $this->request->getFile('file_pdf');
            if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
                
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                $namaFile = $filePdf->getRandomName();
                $filePdf->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile;
            }

            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Laporan Tahunan berhasil diperbarui!');
            return redirect()->to('/layananpublik/laporanTahunan');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/laporan/laporantahunan.php', $this->data);
}


/**
 * Menampilkan halaman manajemen Laporan Keuangan.
 */
public function laporanKeuangan()
{
    $this->data['current_module']['judul_module'] = 'Laporan Realisasi Anggaran';

    // Ambil semua data laporan, diurutkan berdasarkan tahun dan urutan
    $this->data['laporan_list'] = $this->db->table('laporan_keuangan')
                                          ->orderBy('tahun', 'DESC')
                                          ->orderBy('urutan', 'ASC')
                                          ->get()->getResultArray();
    
    // Muat view utama
    $this->view('layananpublik/laporan/laporankeuangan.php', $this->data);
}

/**
 * Menampilkan form untuk menambah/mengedit laporan.
 */
public function formLaporanKeuangan($id = null)
{
    $this->data['laporan'] = null;
    if ($id) {
        $this->data['laporan'] = $this->db->table('laporan_keuangan')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Laporan Anggaran';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Laporan Anggaran';
    }

    // Muat view form
    $this->view('layananpublik/laporan/form_laporankeuangan.php', $this->data);
}

/**
 * Menyimpan data laporan.
 */
public function simpanLaporanKeuangan()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_laporan' => 'required',
        'tahun'         => 'required|integer',
        'link_laporan'  => 'required|valid_url_strict'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_laporan' => $this->request->getPost('judul_laporan'),
        'link_laporan'  => $this->request->getPost('link_laporan'),
        'tahun'         => $this->request->getPost('tahun'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    if ($id) {
        $this->db->table('laporan_keuangan')->where('id', $id)->update($data);
    } else {
        $this->db->table('laporan_keuangan')->insert($data);
    }
    
    return redirect()->to('/layananpublik/laporanKeuangan')->with('success', 'Data Laporan Keuangan berhasil disimpan!');
}

/**
 * Menghapus data laporan.
 */
public function hapusLaporanKeuangan($id)
{
    $this->db->table('laporan_keuangan')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
}

// Add these new methods inside the Layananpublik class

/**
 * Displays the SAKIP document management page.
 */
public function sakip()
{
    $this->data['current_module']['judul_module'] = 'Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)';

    // Fetch all SAKIP documents, ordered by 'urutan'
    $this->data['laporan_list'] = $this->db->table('sakip_laporan')
                                          ->orderBy('urutan', 'ASC')
                                          ->get()->getResultArray();
    
    $this->view('layananpublik/laporan/sakip.php', $this->data);
}

/**
 * Displays the form to add or edit a SAKIP document.
 */
public function formSakip($id = null)
{
    $this->data['laporan'] = null;
    if ($id) {
        $this->data['laporan'] = $this->db->table('sakip_laporan')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Laporan SAKIP';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Laporan SAKIP';
    }

    $this->view('layananpublik/laporan/form_sakip.php', $this->data);
}

/**
 * Saves a SAKIP document.
 */
public function simpanSakip()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_laporan' => 'required',
        'file_laporan' => 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]' // PDF, max 10MB
    ];
    if (!$id) {
        $rules['file_laporan'] = 'uploaded[file_laporan]|' . $rules['file_laporan'];
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_laporan' => $this->request->getPost('judul_laporan'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    $filePdf = $this->request->getFile('file_laporan');
    if ($filePdf->isValid() && !$filePdf->hasMoved()) {
        $namaFile = $filePdf->getRandomName();
        $filePdf->move('uploads/dokumen', $namaFile);
        $data['file_laporan'] = $namaFile;
    }

    if ($id) {
        $this->db->table('sakip_laporan')->where('id', $id)->update($data);
    } else {
        $this->db->table('sakip_laporan')->insert($data);
    }
    
    return redirect()->to('/layananpublik/sakip')->with('success', 'Data Laporan SAKIP berhasil disimpan!');
}

/**
 * Deletes a SAKIP document.
 */
public function hapusSakip($id)
{
    $laporan = $this->db->table('sakip_laporan')->getWhere(['id' => $id])->getRow();
    if ($laporan) {
        if (!empty($laporan->file_laporan) && file_exists('uploads/dokumen/' . $laporan->file_laporan)) {
            unlink('uploads/dokumen/' . $laporan->file_laporan);
        }
        $this->db->table('sakip_laporan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}

public function lhkpn()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'lhkpn';
    
    $this->data['current_module']['judul_module'] = 'Laporan Harta Kekayaan (LHKPN)';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman'  => 'required',
            'external_link' => 'required|valid_url_strict' // Wajib diisi dan harus URL valid
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman'    => $this->request->getPost('isi_halaman'),
                'gambar_halaman' => $this->request->getPost('external_link') // Simpan link di kolom 'gambar_halaman'
            ];

            // Update data di database
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data LHKPN berhasil diperbarui!');
            return redirect()->to('/layananpublik/lhkpn');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view
    $this->view('layananpublik/laporan/lhkpn.php', $this->data);
}

// Tambahkan semua method baru ini di dalam class Layananpublik

/**
 * Menampilkan halaman manajemen Laporan Survey.
 */
public function laporanSurvey()
{
    $this->data['current_module']['judul_module'] = 'Hasil Survey Indeks Kepuasan Masyarakat';

    // 1. Ambil data teks pengantar dari tabel halaman_profil
    $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'laporan-survey-intro'])->getRowArray();

    // 2. Ambil daftar laporan dari tabel laporan_survey
    $this->data['laporan_list'] = $this->db->table('laporan_survey')->orderBy('urutan', 'ASC')->get()->getResultArray();
    
    // 3. Kirim semua data (intro dan laporan_list) ke view
    $this->view('layananpublik/laporan/laporansurvey.php', $this->data);
}

/**
 * Menampilkan form untuk menambah/mengedit laporan survey.
 */
public function formLaporanSurvey($id = null)
{
    $this->data['laporan'] = null;
    if ($id) {
        $this->data['laporan'] = $this->db->table('laporan_survey')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Laporan Survey';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Laporan Survey';
    }

    // Muat view form
    $this->view('layananpublik/laporan/form_laporansurvey.php', $this->data);
}

/**
 * Menyimpan data laporan survey.
 */
public function simpanLaporanSurvey()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_laporan' => 'required',
        'file_laporan'  => 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]' // PDF, maks 10MB
    ];
    if (!$id) {
        $rules['file_laporan'] = 'uploaded[file_laporan]|' . $rules['file_laporan'];
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_laporan' => $this->request->getPost('judul_laporan'),
        'urutan'        => $this->request->getPost('urutan'),
    ];

    $filePdf = $this->request->getFile('file_laporan');
    if ($filePdf->isValid() && !$filePdf->hasMoved()) {
        $namaFile = $filePdf->getRandomName();
        $filePdf->move('uploads/dokumen', $namaFile);
        $data['file_laporan'] = $namaFile;
    }

    if ($id) {
        $this->db->table('laporan_survey')->where('id', $id)->update($data);
    } else {
        $this->db->table('laporan_survey')->insert($data);
    }
    
    return redirect()->to('/layananpublik/laporanSurvey')->with('success', 'Data Laporan Survey berhasil disimpan!');
}

/**
 * Menghapus data laporan survey.
 */
public function hapusLaporanSurvey($id)
{
    $laporan = $this->db->table('laporan_survey')->getWhere(['id' => $id])->getRow();
    if ($laporan) {
        if (!empty($laporan->file_laporan) && file_exists('uploads/dokumen/' . $laporan->file_laporan)) {
            unlink('uploads/dokumen/' . $laporan->file_laporan);
        }
        $this->db->table('laporan_survey')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}


public function editIntroLaporanSurvey()
{
    $nama_halaman = 'laporan-survey-intro';
    $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Laporan Survey';

    if ($this->request->getMethod() === 'post') {
        if ($this->validate(['isi_halaman' => 'required'])) {
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
            session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
            return redirect()->to('/layananpublik/laporanSurvey'); // Kembali ke halaman daftar
        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
    $this->view('layananpublik/laporan/edit_intro_laporansurvey.php', $this->data);
}

public function dendaTilang()
    {
        $this->data['current_module']['judul_module'] = 'Manajemen Denda Tilang';

        // Fetch all ticket fine data
        $this->data['tilang_list'] = $this->db->table('denda_tilang')
                                             ->orderBy('tanggal_sidang', 'DESC')
                                             ->get()->getResultArray();
        
        $this->view('layananpublik/pengumuman/dendatilang.php', $this->data);
    }

    /**
     * Displays the form to add or edit a ticket fine.
     */
    public function formDendaTilang($id = null)
    {
        $this->data['tilang'] = null;
        if ($id) {
            $this->data['tilang'] = $this->db->table('denda_tilang')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Data Tilang';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Data Tilang Baru';
        }

        $this->view('layananpublik/pengumuman/form_dendatilang.php', $this->data);
    }

    /**
     * Saves a ticket fine record.
     */
    public function simpanDendaTilang()
    {
        $id = $this->request->getPost('id');
        $rules = [
            'tanggal_sidang' => 'required|valid_date',
            'nama'           => 'required',
            'denda'          => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'tanggal_sidang' => $this->request->getPost('tanggal_sidang'),
            'nama'           => $this->request->getPost('nama'),
            'nomor_tilang'   => $this->request->getPost('nomor_tilang'),
            'nomor_polisi'   => $this->request->getPost('nomor_polisi'),
            'barang_bukti'   => $this->request->getPost('barang_bukti'),
            'denda'          => $this->request->getPost('denda'),
        ];

        if ($id) {
            $this->db->table('denda_tilang')->where('id', $id)->update($data);
        } else {
            $this->db->table('denda_tilang')->insert($data);
        }
        
        return redirect()->to('/layananpublik/dendaTilang')->with('success', 'Data denda tilang berhasil disimpan!');
    }

    /**
     * Deletes a ticket fine record.
     */
    public function hapusDendaTilang($id)
    {
        $this->db->table('denda_tilang')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data tilang berhasil dihapus!');
    }

public function panggilanTdkDiketahui()
{
    $this->data['current_module']['judul_module'] = 'Manajemen Panggilan Tidak Diketahui';
    $this->data['panggilan_list'] = $this->db->table('panggilan_tdk_diketahui')
                                             ->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
    
    $this->view('layananpublik/pengumuman/panggilantdkdiketahui.php', $this->data);
}

/**
 * Menampilkan form untuk menambah/mengedit panggilan.
 */
public function formPanggilanTdkDiketahui($id = null)
{
    $this->data['panggilan'] = null;
    if ($id) {
        $this->data['panggilan'] = $this->db->table('panggilan_tdk_diketahui')->getWhere(['id' => $id])->getRowArray();
        $this->data['current_module']['judul_module'] = 'Edit Panggilan Tidak Diketahui';
    } else {
        $this->data['current_module']['judul_module'] = 'Tambah Panggilan Tidak Diketahui';
    }
    $this->view('layananpublik/pengumuman/form_panggilantdkdiketahui.php', $this->data);
}

/**
 * Menyimpan data panggilan.
 */
public function simpanPanggilanTdkDiketahui()
{
    $id = $this->request->getPost('id');
    $rules = [
        'judul_panggilan' => 'required',
        'file_panggilan'  => 'max_size[file_panggilan,10240]|ext_in[file_panggilan,pdf,jpg,jpeg,png]'
    ];
    if (!$id) {
        $rules['file_panggilan'] = 'uploaded[file_panggilan]|' . $rules['file_panggilan'];
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'judul_panggilan' => $this->request->getPost('judul_panggilan'),
        'deskripsi'       => $this->request->getPost('deskripsi'),
        'urutan'          => $this->request->getPost('urutan'),
    ];

    $filePanggilan = $this->request->getFile('file_panggilan');
    if ($filePanggilan->isValid() && !$filePanggilan->hasMoved()) {
        $namaFile = $filePanggilan->getRandomName();
        $filePanggilan->move('uploads/dokumen', $namaFile);
        $data['file_panggilan'] = $namaFile;
    }

    if ($id) {
        $this->db->table('panggilan_tdk_diketahui')->where('id', $id)->update($data);
    } else {
        $this->db->table('panggilan_tdk_diketahui')->insert($data);
    }
    
    return redirect()->to('/layananpublik/panggilanTdkDiketahui')->with('success', 'Data panggilan berhasil disimpan!');
}

/**
 * Menghapus data panggilan.
 */
public function hapusPanggilanTdkDiketahui($id)
{
    $panggilan = $this->db->table('panggilan_tdk_diketahui')->getWhere(['id' => $id])->getRow();
    if ($panggilan) {
        if (!empty($panggilan->file_panggilan) && file_exists('uploads/dokumen/' . $panggilan->file_panggilan)) {
            unlink('uploads/dokumen/' . $panggilan->file_panggilan);
        }
        $this->db->table('panggilan_tdk_diketahui')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Panggilan berhasil dihapus!');
    }
    return redirect()->back()->with('errors', 'Data tidak ditemukan!');
}

// Tambahkan method ini di dalam class Layananpublik

public function jamKerja()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'jam-kerja';
    
    $this->data['current_module']['judul_module'] = 'Jam Kerja Pelayanan Publik';

    if ($this->request->getMethod() === 'post') {
        
        // Aturan validasi: File harus PDF/Gambar, maks 5MB
        $rules = [
            'file_upload' => 'max_size[file_upload,5120]|ext_in[file_upload,pdf,jpg,jpeg,png]' 
        ];

        if ($this->validate($rules)) {
            $dataUpdate = []; // Mulai dengan array kosong

            // Proses upload file jika ada yang diunggah
            $fileUpload = $this->request->getFile('file_upload');
            if ($fileUpload && $fileUpload->isValid() && !$fileUpload->hasMoved()) {
                
                // Hapus file lama dari server
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                // Simpan file baru ke 'public/uploads/dokumen'
                $namaFile = $fileUpload->getRandomName();
                $fileUpload->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile; // Simpan nama file baru
            }

            // Update data di database hanya jika ada perubahan
            if (!empty($dataUpdate)) {
                 $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            }
            
            session()->setFlashdata('success', 'File Jam Kerja berhasil diperbarui!');
            return redirect()->to('/layananpublik/jamKerja');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil data terbaru dari database
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/jamkerja/jamkerja.php', $this->data);
}

// Tambahkan method ini di dalam class Layananpublik

public function prosedurPermohonanInformasi()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'prosedur-informasi';
    
    $this->data['current_module']['judul_module'] = 'Prosedur Permohonan Informasi';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman' => 'required',
            'file_upload' => 'max_size[file_upload,5120]|ext_in[file_upload,pdf,jpg,jpeg,png]' // PDF/Gambar, maks 5MB
        ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            // Proses upload file jika ada
            $fileUpload = $this->request->getFile('file_upload');
            if ($fileUpload && $fileUpload->isValid() && !$fileUpload->hasMoved()) {
                
                $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                    unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                }

                $namaFile = $fileUpload->getRandomName();
                $fileUpload->move('uploads/dokumen', $namaFile);
                $dataUpdate['gambar_halaman'] = $namaFile;
            }

            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Prosedur Permohonan Informasi berhasil diperbarui!');
            return redirect()->to('/layananpublik/prosedurPermohonanInformasi');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Pastikan path view benar
    $this->view('layananpublik/prosedurpermohonan/prosedurpermohonan.php', $this->data);
}

// Tambahkan method ini di dalam class Layananpublik

public function dasarHukum()
{
    // Identifier unik untuk halaman ini
    $nama_halaman = 'dasar-hukum-pengaduan';
    
    $this->data['current_module']['judul_module'] = 'Dasar Hukum Pengaduan';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [ 'isi_halaman' => 'required' ];

        if ($this->validate($rules)) {
            $dataUpdate = [
                'isi_halaman' => $this->request->getPost('isi_halaman')
            ];

            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
            
            session()->setFlashdata('success', 'Data Dasar Hukum Pengaduan berhasil diperbarui!');
            return redirect()->to('/layananpublik/dasarHukum');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/pengaduanlayananpublik/dasarhukum.php', $this->data);
}

// Tambahkan method ini di dalam class Layananpublik

public function prosedurPengaduan()
{
    // Identifier unik untuk teks utama
    $nama_halaman = 'prosedur-pengaduan';
    
    $this->data['current_module']['judul_module'] = 'Prosedur Pengaduan';

    if ($this->request->getMethod() === 'post') {
        
        $rules = [
            'isi_halaman'   => 'required',
            'pengaduan_url' => 'required|valid_url_strict',
            'pengaduan_image' => 'max_size[pengaduan_image,1024]|is_image[pengaduan_image]' // Gambar, maks 1MB
        ];

        if ($this->validate($rules)) {
            // 1. Simpan Teks Utama
            $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                     ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
            
            // 2. Simpan URL Tombol
            $this->db->table('pengaturan_situs')->where('nama_pengaturan', 'pengaduan_url')
                     ->update(['nilai_pengaturan' => $this->request->getPost('pengaduan_url')]);

            // 3. Proses Upload Gambar Tombol (jika ada)
            $fileGambar = $this->request->getFile('pengaduan_image');
            if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
                $oldData = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_image'])->getRow();
                if ($oldData && !empty($oldData->nilai_pengaturan) && file_exists('uploads/images/' . $oldData->nilai_pengaturan)) {
                    unlink('uploads/images/' . $oldData->nilai_pengaturan); // Hapus gambar lama
                }
                $namaGambar = $fileGambar->getRandomName();
                $fileGambar->move('uploads/images', $namaGambar); // Simpan ke 'public/uploads/images'
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', 'pengaduan_image')
                         ->update(['nilai_pengaturan' => $namaGambar]); // Simpan nama file baru
            }

            session()->setFlashdata('success', 'Data Prosedur Pengaduan berhasil diperbarui!');
            return redirect()->to('/layananpublik/prosedurPengaduan');

        } else {
            $this->data['pesan_gagal'] = $this->validator->getErrors();
        }
    }

    // Ambil semua data terbaru untuk ditampilkan di form
    $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
    $this->data['setting_image'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_image'])->getRowArray();
    $this->data['setting_url'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_url'])->getRowArray();


    // Tampilkan view sesuai path lengkap Anda
    $this->view('layananpublik/pengaduanlayananpublik/prosedurpengaduan.php', $this->data);
}
}