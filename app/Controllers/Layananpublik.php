<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */
class Layananpublik extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url']);
        $this->db = \Config\Database::connect();
    }

 /*
    |--------------------------------------------------------------------------
    | HALAMAN PTSP (Pelayanan Terpadu Satu Pintu)
    |--------------------------------------------------------------------------
    */

    /**
     * Mengelola halaman teks statis Jenis Layanan PTSP.
     * Menggunakan tabel halaman_profil.
     */
    public function ptspJenisLayanan()
    {
        $nama_halaman = 'jenis-layanan-ptsp';
        $this->data['current_module']['judul_module'] = 'Jenis Layanan PTSP';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                session()->setFlashdata('success', 'Data Jenis Layanan PTSP berhasil diperbarui!');
                return redirect()->to('/layanan-publik/ptsp/jenis-layanan'); // Sesuaikan dengan Rute
            } else {
                // Simpan error ke session dan kembali ke form
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/ptsp/jenislayanan.php', $this->data);
    }

    /**
     * Menampilkan halaman utama manajemen Standar Pelayanan.
     * Mengambil teks intro dari halaman_profil dan daftar dokumen dari standar_pelayanan_dokumen.
     */
    public function standarPelayanan()
    {
        $this->data['current_module']['judul_module'] = 'Standar Pelayanan';
        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'standar-pelayanan-intro'])->getRowArray();
        $this->data['dokumen_list'] = $this->db->table('standar_pelayanan_dokumen')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('layananpublik/ptsp/standarpelayanan.php', $this->data);
    }

    /**
     * Mengelola halaman edit untuk teks pengantar Standar Pelayanan.
     */
    public function editIntroStandarPelayanan()
    {
        $nama_halaman = 'standar-pelayanan-intro';
        $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Standar Pelayanan';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
                return redirect()->to('/layananpublik/standarPelayanan');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/ptsp/edit_intro_standarpelayanan.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit dokumen Standar Pelayanan.
     * @param int|null $id ID dokumen jika dalam mode edit.
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
     * Menyimpan data dokumen Standar Pelayanan (PDF).
     */
    public function simpanStandarPelayanan()
    {
        $id = $this->request->getPost('id');
        // Validasi: judul wajib, file PDF wajib saat tambah
        $rules = [
            'judul_dokumen' => 'required',
            'file_dokumen'  => ($id ? '' : 'uploaded[file_dokumen]|') . 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_dokumen' => $this->request->getPost('judul_dokumen'),
            'urutan'        => $this->request->getPost('urutan'),
        ];

        $filePdf = $this->request->getFile('file_dokumen');
        if ($filePdf->isValid() && !$filePdf->hasMoved()) {
            // Hapus file lama jika edit
            if($id) {
                 $dokumenLama = $this->db->table('standar_pelayanan_dokumen')->select('file_dokumen')->getWhere(['id' => $id])->getRow();
                 if ($dokumenLama && !empty($dokumenLama->file_dokumen) && file_exists('uploads/dokumen/' . $dokumenLama->file_dokumen)) {
                    unlink('uploads/dokumen/' . $dokumenLama->file_dokumen);
                }
            }
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/dokumen', $namaFile);
            $data['file_dokumen'] = $namaFile;
        }

        if ($id) {
            $this->db->table('standar_pelayanan_dokumen')->where('id', $id)->update($data);
        } else {
            $this->db->table('standar_pelayanan_dokumen')->insert($data);
        }
        
        return redirect()->to('/layananpublik/standarPelayanan')->with('success', 'Dokumen Standar Pelayanan berhasil disimpan!');
    }

    /**
     * Menghapus dokumen Standar Pelayanan.
     * @param int $id ID dokumen yang akan dihapus.
     */
    public function hapusStandarPelayanan($id)
    {
        $dokumen = $this->db->table('standar_pelayanan_dokumen')->getWhere(['id' => $id])->getRow();
        if ($dokumen) {
            // Hapus file fisik dari server
            if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
                unlink('uploads/dokumen/' . $dokumen->file_dokumen);
            }
            $this->db->table('standar_pelayanan_dokumen')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

    /**
     * Mengelola halaman Maklumat Pelayanan (teks + gambar single).
     * Menggunakan tabel halaman_profil.
     */
    public function maklumatPelayanan()
    {
        $nama_halaman = 'maklumat-pelayanan';
        $this->data['current_module']['judul_module'] = 'Maklumat Pelayanan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman'    => 'required',
                'gambar_halaman' => 'max_size[gambar_halaman,3072]|is_image[gambar_halaman]' // Validasi gambar maks 3MB
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
                session()->setFlashdata('success', 'Data Maklumat Pelayanan berhasil diperbarui!');
                return redirect()->to('/layananpublik/maklumatPelayanan');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/ptsp/maklumatpelayanan.php', $this->data);
    }

    /**
     * Mengelola halaman Kompensasi Pelayanan (teks + PDF single).
     * Menggunakan tabel halaman_profil.
     */
    public function kompensasiPelayanan()
    {
        $nama_halaman = 'kompensasi-pelayanan';
        $this->data['current_module']['judul_module'] = 'Kompensasi Pelayanan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman' => 'required',
                'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
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
                session()->setFlashdata('success', 'Data Kompensasi Pelayanan berhasil diperbarui!');
                return redirect()->to('/layananpublik/kompensasiPelayanan');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/ptsp/kompensasipelayanan.php', $this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN LAYANAN DISABILITAS
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman utama manajemen Layanan Disabilitas.
     * Mengambil teks/gambar dari halaman_profil dan daftar dokumen dari dokumen_disabilitas.
     */
    public function penyandangDisabilitas()
    {
        $this->data['current_module']['judul_module'] = 'Layanan Disabilitas';
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'layanan-disabilitas'])->getRowArray();
        $this->data['dokumen_list'] = $this->db->table('dokumen_disabilitas')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('layananpublik/layanandisabilitas/penyandangdisabilitas.php', $this->data);
    }

    /**
     * Menyimpan perubahan pada teks pengantar dan gambar alur Layanan Disabilitas.
     * Data disimpan ke tabel halaman_profil.
     */
    public function simpanPenyandangDisabilitas()
    {
        $nama_halaman = 'layanan-disabilitas';
        $rules = [
            'isi_halaman'    => 'required',
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
            // Kembali ke form dengan input lama, tapi redirect ke halaman utama karena form ada di sana
             return redirect()->to('/layananpublik/penyandangDisabilitas')->withInput();
        }
        
        return redirect()->to('/layananpublik/penyandangDisabilitas');
    }

    /**
     * Menampilkan form untuk menambah/mengedit dokumen SOP Disabilitas.
     * @param int|null $id ID dokumen jika dalam mode edit.
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
     * Menyimpan data dokumen SOP Disabilitas (PDF).
     */
    public function simpanDokumenDisabilitas()
    {
        $id = $this->request->getPost('id');
        $rules = [
            'judul_dokumen' => 'required',
            'file_dokumen'  => ($id ? '' : 'uploaded[file_dokumen]|') . 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_dokumen' => $this->request->getPost('judul_dokumen'),
            'urutan'        => $this->request->getPost('urutan'),
        ];

        $filePdf = $this->request->getFile('file_dokumen');
        if ($filePdf->isValid() && !$filePdf->hasMoved()) {
             if($id) {
                 $dokumenLama = $this->db->table('dokumen_disabilitas')->select('file_dokumen')->getWhere(['id' => $id])->getRow();
                 if ($dokumenLama && !empty($dokumenLama->file_dokumen) && file_exists('uploads/dokumen/' . $dokumenLama->file_dokumen)) {
                    unlink('uploads/dokumen/' . $dokumenLama->file_dokumen);
                }
            }
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
     * Menghapus dokumen SOP Disabilitas.
     * @param int $id ID dokumen yang akan dihapus.
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

    /**
     * Mengelola halaman Sarana & Prasarana Disabilitas (teks + PDF/gambar single).
     * Menggunakan tabel halaman_profil.
     */
    public function saranaPrasaranaDisabilitas()
    {
        $nama_halaman = 'sarana-disabilitas';
        $this->data['current_module']['judul_module'] = 'Sarana & Prasarana Disabilitas';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman' => 'required',
                'file_upload' => 'max_size[file_upload,5120]|ext_in[file_upload,pdf,jpg,jpeg,png]' // PDF/Gambar, maks 5MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
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

                if (!empty($dataUpdate)) {
                     $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                }
                session()->setFlashdata('success', 'Data Sarana & Prasarana Disabilitas berhasil diperbarui!');
                return redirect()->to('/layananpublik/saranaPrasaranaDisabilitas');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/layanandisabilitas/saranaprasaranadisabilitas.php', $this->data);
    }

/*
    |--------------------------------------------------------------------------
    | HALAMAN LAPORAN
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman manajemen Laporan Kinerja Instansi Pemerintah (LKjIP).
     * Mengambil daftar laporan dari tabel lkjip_laporan.
     */
    public function ringkasanLaporan()
    {
        $this->data['current_module']['judul_module'] = 'Laporan Kinerja Instansi Pemerintah (LKjIP)';
        $this->data['laporan_list'] = $this->db->table('lkjip_laporan')
                                             ->orderBy('tahun', 'DESC')
                                             ->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
        
        $this->view('layananpublik/laporan/ringkasanlaporan.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit laporan LKjIP.
     * @param int|null $id ID laporan jika dalam mode edit.
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
        $this->view('layananpublik/laporan/form_ringkasanlaporan.php', $this->data);
    }

    /**
     * Menyimpan data laporan LKjIP (baru atau editan).
     * Menangani validasi dan upload file PDF.
     */
    public function simpanRingkasanLaporan()
    {
        $id = $this->request->getPost('id');
        // Validasi: judul & tahun wajib, file PDF wajib saat tambah
        $rules = [
            'judul_laporan' => 'required',
            'tahun'         => 'required|integer',
            'file_laporan'  => ($id ? '' : 'uploaded[file_laporan]|') . 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]'
        ];

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
            // Hapus file lama jika proses edit
             if($id) {
                 $laporanLama = $this->db->table('lkjip_laporan')->select('file_laporan')->getWhere(['id' => $id])->getRow();
                 if ($laporanLama && !empty($laporanLama->file_laporan) && file_exists('uploads/dokumen/' . $laporanLama->file_laporan)) {
                    unlink('uploads/dokumen/' . $laporanLama->file_laporan);
                }
            }
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
     * Menghapus data laporan LKjIP.
     * Termasuk menghapus file PDF terkait.
     * @param int $id ID laporan yang akan dihapus.
     */
    public function hapusRingkasanLaporan($id)
    {
        $laporan = $this->db->table('lkjip_laporan')->getWhere(['id' => $id])->getRow();
        if ($laporan) {
            // Hapus file fisik
            if (!empty($laporan->file_laporan) && file_exists('uploads/dokumen/' . $laporan->file_laporan)) {
                unlink('uploads/dokumen/' . $laporan->file_laporan);
            }
            $this->db->table('lkjip_laporan')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

    /**
     * Mengelola halaman Ringkasan Daftar Aset (teks + PDF single).
     * Menggunakan tabel halaman_profil.
     */
    public function ringkasanDaftarAset()
    {
        $nama_halaman = 'ringkasan-aset';
        $this->data['current_module']['judul_module'] = 'Ringkasan Daftar Aset';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman' => 'required',
                'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
                $filePdf = $this->request->getFile('file_pdf');

                if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
                    $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                    if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                        unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                    }
                    $namaFile = $filePdf->getRandomName();
                    $filePdf->move('uploads/dokumen', $namaFile);
                    $dataUpdate['gambar_halaman'] = $namaFile; // Simpan nama file di kolom gambar_halaman
                }

                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                session()->setFlashdata('success', 'Data Ringkasan Daftar Aset berhasil diperbarui!');
                return redirect()->to('/layananpublik/ringkasanDaftarAset');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/laporan/ringkasandaftaraset.php', $this->data);
    }

    /**
     * Mengelola halaman Laporan Tahunan (teks + PDF single).
     * Menggunakan tabel halaman_profil.
     */
    public function laporanTahunan()
    {
        $nama_halaman = 'laporan-tahunan';
        $this->data['current_module']['judul_module'] = 'Laporan Tahunan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman' => 'required',
                'file_pdf'    => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
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
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/laporan/laporantahunan.php', $this->data);
    }

    /**
     * Menampilkan halaman manajemen Laporan Realisasi Anggaran.
     * Mengambil daftar link laporan dari tabel laporan_keuangan.
     */
    public function laporanKeuangan()
    {
        $this->data['current_module']['judul_module'] = 'Laporan Realisasi Anggaran';
        $this->data['laporan_list'] = $this->db->table('laporan_keuangan')
                                             ->orderBy('tahun', 'DESC')
                                             ->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
        $this->view('layananpublik/laporan/laporankeuangan.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit link Laporan Keuangan.
     * @param int|null $id ID laporan jika dalam mode edit.
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
        $this->view('layananpublik/laporan/form_laporankeuangan.php', $this->data);
    }

    /**
     * Menyimpan data link Laporan Keuangan.
     */
    public function simpanLaporanKeuangan()
    {
        $id = $this->request->getPost('id');
        $rules = [
            'judul_laporan' => 'required',
            'tahun'         => 'required|integer',
            'link_laporan'  => 'required|valid_url_strict' // Validasi URL
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
     * Menghapus data link Laporan Keuangan.
     * @param int $id ID laporan yang akan dihapus.
     */
    public function hapusLaporanKeuangan($id)
    {
        // Tidak perlu hapus file fisik karena hanya link
        $this->db->table('laporan_keuangan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Menampilkan halaman manajemen Laporan SAKIP.
     * Mengambil daftar dokumen dari tabel sakip_laporan.
     */
    public function sakip()
    {
        $this->data['current_module']['judul_module'] = 'Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)';
        $this->data['laporan_list'] = $this->db->table('sakip_laporan')
                                             ->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
        $this->view('layananpublik/laporan/sakip.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit dokumen SAKIP.
     * @param int|null $id ID laporan jika dalam mode edit.
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
     * Menyimpan data dokumen SAKIP (PDF).
     */
    public function simpanSakip()
    {
        $id = $this->request->getPost('id');
        $rules = [
            'judul_laporan' => 'required',
            'file_laporan'  => ($id ? '' : 'uploaded[file_laporan]|') . 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_laporan' => $this->request->getPost('judul_laporan'),
            'urutan'        => $this->request->getPost('urutan'),
        ];

        $filePdf = $this->request->getFile('file_laporan');
        if ($filePdf->isValid() && !$filePdf->hasMoved()) {
            if($id) {
                 $laporanLama = $this->db->table('sakip_laporan')->select('file_laporan')->getWhere(['id' => $id])->getRow();
                 if ($laporanLama && !empty($laporanLama->file_laporan) && file_exists('uploads/dokumen/' . $laporanLama->file_laporan)) {
                    unlink('uploads/dokumen/' . $laporanLama->file_laporan);
                }
            }
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
     * Menghapus data dokumen SAKIP.
     * @param int $id ID laporan yang akan dihapus.
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

    /**
     * Mengelola halaman LHKPN (teks + link eksternal).
     * Menggunakan tabel halaman_profil.
     */
    public function lhkpn()
    {
        $nama_halaman = 'lhkpn';
        $this->data['current_module']['judul_module'] = 'Laporan Harta Kekayaan (LHKPN)';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman'   => 'required',
                'external_link' => 'required|valid_url_strict' // Validasi URL
            ];

            if ($this->validate($rules)) {
                $dataUpdate = [
                    'isi_halaman'    => $this->request->getPost('isi_halaman'),
                    'gambar_halaman' => $this->request->getPost('external_link') // Simpan link di kolom gambar_halaman
                ];
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                session()->setFlashdata('success', 'Data LHKPN berhasil diperbarui!');
                return redirect()->to('/layananpublik/lhkpn');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/laporan/lhkpn.php', $this->data);
    }

/*
    |--------------------------------------------------------------------------
    | HALAMAN LAPORAN SURVEY (Hybrid: Teks Intro + CRUD Dokumen)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman manajemen Laporan Survey.
     * Mengambil teks intro dari halaman_profil dan daftar dokumen dari laporan_survey.
     */
    public function laporanSurvey()
    {
        $this->data['current_module']['judul_module'] = 'Hasil Survey Indeks Kepuasan Masyarakat';
        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'laporan-survey-intro'])->getRowArray();
        $this->data['laporan_list'] = $this->db->table('laporan_survey')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('layananpublik/laporan/laporansurvey.php', $this->data);
    }

    /**
     * Menampilkan form untuk mengedit teks pengantar Laporan Survey.
     */
    public function editIntroLaporanSurvey()
    {
        $nama_halaman = 'laporan-survey-intro';
        $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Laporan Survey';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
                return redirect()->to('/layananpublik/laporanSurvey'); // Kembali ke halaman daftar
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/laporan/edit_intro_laporansurvey.php', $this->data);
    }


    /**
     * Menampilkan form untuk menambah/mengedit dokumen Laporan Survey.
     * @param int|null $id ID laporan jika dalam mode edit.
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
        $this->view('layananpublik/laporan/form_laporansurvey.php', $this->data);
    }

    /**
     * Menyimpan data dokumen Laporan Survey (PDF).
     */
    public function simpanLaporanSurvey()
    {
        $id = $this->request->getPost('id');
        // Validasi: judul wajib, file PDF wajib saat tambah
        $rules = [
            'judul_laporan' => 'required',
            'file_laporan'  => ($id ? '' : 'uploaded[file_laporan]|') . 'max_size[file_laporan,10240]|ext_in[file_laporan,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_laporan' => $this->request->getPost('judul_laporan'),
            'urutan'        => $this->request->getPost('urutan'),
        ];

        $filePdf = $this->request->getFile('file_laporan');
        if ($filePdf->isValid() && !$filePdf->hasMoved()) {
            // Hapus file lama jika edit
            if($id) {
                 $laporanLama = $this->db->table('laporan_survey')->select('file_laporan')->getWhere(['id' => $id])->getRow();
                 if ($laporanLama && !empty($laporanLama->file_laporan) && file_exists('uploads/dokumen/' . $laporanLama->file_laporan)) {
                    unlink('uploads/dokumen/' . $laporanLama->file_laporan);
                }
            }
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
     * Menghapus data dokumen Laporan Survey.
     * @param int $id ID laporan yang akan dihapus.
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


    /*
    |--------------------------------------------------------------------------
    | HALAMAN PENGUMUMAN (Denda Tilang, Panggilan Tdk Diketahui)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman manajemen Denda Tilang.
     */
    public function dendaTilang()
    {
        $this->data['current_module']['judul_module'] = 'Manajemen Denda Tilang';
        $this->data['tilang_list'] = $this->db->table('denda_tilang')
                                             ->orderBy('tanggal_sidang', 'DESC')
                                             ->get()->getResultArray();
        
        $this->view('layananpublik/pengumuman/dendatilang.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah atau mengedit denda tilang.
     * @param int|null $id ID data tilang jika dalam mode edit.
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
     * Menyimpan data denda tilang.
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
     * Menghapus data denda tilang.
     * @param int $id ID data tilang yang akan dihapus.
     */
    public function hapusDendaTilang($id)
    {
        $this->db->table('denda_tilang')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data tilang berhasil dihapus!');
    }

    /**
     * Menampilkan halaman manajemen Panggilan Tidak Diketahui.
     */
    public function panggilanTdkDiketahui()
    {
        $this->data['current_module']['judul_module'] = 'Manajemen Panggilan Tidak Diketahui';
        $this->data['panggilan_list'] = $this->db->table('panggilan_tdk_diketahui')
                                             ->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
        
        $this->view('layananpublik/pengumuman/panggilantdkdiketahui.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit panggilan tidak diketahui.
     * @param int|null $id ID panggilan jika dalam mode edit.
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
     * Menyimpan data panggilan tidak diketahui.
     */
    public function simpanPanggilanTdkDiketahui()
    {
        $id = $this->request->getPost('id');
        // Validasi: judul wajib, file wajib saat tambah (PDF/Gambar)
        $rules = [
            'judul_panggilan' => 'required',
            'file_panggilan'  => ($id ? '' : 'uploaded[file_panggilan]|') . 'max_size[file_panggilan,10240]|ext_in[file_panggilan,pdf,jpg,jpeg,png]'
        ];

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
            if($id) {
                 $panggilanLama = $this->db->table('panggilan_tdk_diketahui')->select('file_panggilan')->getWhere(['id' => $id])->getRow();
                 if ($panggilanLama && !empty($panggilanLama->file_panggilan) && file_exists('uploads/dokumen/' . $panggilanLama->file_panggilan)) {
                    unlink('uploads/dokumen/' . $panggilanLama->file_panggilan);
                }
            }
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
     * Menghapus data panggilan tidak diketahui.
     * @param int $id ID panggilan yang akan dihapus.
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

    /*
    |--------------------------------------------------------------------------
    | HALAMAN JAM KERJA & PROSEDUR INFORMASI/PENGADUAN
    |--------------------------------------------------------------------------
    */

     /**
     * Mengelola halaman Jam Kerja (upload file tunggal PDF/Gambar).
     * Menggunakan tabel halaman_profil.
     */
    public function jamKerja()
    {
        $nama_halaman = 'jam-kerja';
        $this->data['current_module']['judul_module'] = 'Jam Kerja Pelayanan Publik';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'file_upload' => 'max_size[file_upload,5120]|ext_in[file_upload,pdf,jpg,jpeg,png]' // PDF/Gambar, maks 5MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = []; // Hanya update jika ada file baru
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

                if (!empty($dataUpdate)) {
                     $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                }
                session()->setFlashdata('success', 'File Jam Kerja berhasil diperbarui!');
                return redirect()->to('/layananpublik/jamKerja');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/jamkerja/jamkerja.php', $this->data);
    }

    /**
     * Mengelola halaman Prosedur Permohonan Informasi (teks + gambar/PDF single).
     * Menggunakan tabel halaman_profil.
     */
    public function prosedurPermohonanInformasi()
    {
        $nama_halaman = 'prosedur-informasi';
        $this->data['current_module']['judul_module'] = 'Prosedur Permohonan Informasi';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman' => 'required',
                'file_upload' => 'max_size[file_upload,5120]|ext_in[file_upload,pdf,jpg,jpeg,png]' // PDF/Gambar, maks 5MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
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
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/prosedurpermohonan/prosedurpermohonan.php', $this->data);
    }

    /**
     * Mengelola halaman Dasar Hukum Pengaduan (teks statis).
     * Menggunakan tabel halaman_profil.
     */
    public function dasarHukum()
    {
        $nama_halaman = 'dasar-hukum-pengaduan';
        $this->data['current_module']['judul_module'] = 'Dasar Hukum Pengaduan';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $dataUpdate = ['isi_halaman' => $this->request->getPost('isi_halaman')];
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                session()->setFlashdata('success', 'Data Dasar Hukum Pengaduan berhasil diperbarui!');
                return redirect()->to('/layananpublik/dasarHukum');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('layananpublik/pengaduanlayananpublik/dasarhukum.php', $this->data);
    }

    /**
     * Mengelola halaman Prosedur Pengaduan (teks + tombol link/gambar).
     * Menggunakan tabel halaman_profil dan pengaturan_situs.
     */
    public function prosedurPengaduan()
    {
        $nama_halaman = 'prosedur-pengaduan';
        $this->data['current_module']['judul_module'] = 'Prosedur Pengaduan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'isi_halaman'   => 'required',
                'pengaduan_url' => 'required|valid_url_strict',
                'pengaduan_image' => 'max_size[pengaduan_image,1024]|is_image[pengaduan_image]' // Gambar, maks 1MB
            ];

            if ($this->validate($rules)) {
                // Simpan Teks Utama
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                
                // Simpan URL Tombol
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', 'pengaduan_url')
                         ->update(['nilai_pengaturan' => $this->request->getPost('pengaduan_url')]);

                // Proses Upload Gambar Tombol
                $fileGambar = $this->request->getFile('pengaduan_image');
                if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
                    $oldData = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_image'])->getRow();
                    if ($oldData && !empty($oldData->nilai_pengaturan) && file_exists('uploads/images/' . $oldData->nilai_pengaturan)) {
                        unlink('uploads/images/' . $oldData->nilai_pengaturan);
                    }
                    $namaGambar = $fileGambar->getRandomName();
                    $fileGambar->move('uploads/images', $namaGambar);
                    $this->db->table('pengaturan_situs')->where('nama_pengaturan', 'pengaduan_image')
                             ->update(['nilai_pengaturan' => $namaGambar]);
                }

                session()->setFlashdata('success', 'Data Prosedur Pengaduan berhasil diperbarui!');
                return redirect()->to('/layananpublik/prosedurPengaduan');
            } else {
                 session()->setFlashdata('errors', $this->validator->getErrors());
                 return redirect()->back()->withInput();
            }
        }

        // Ambil semua data terkait untuk view
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->data['setting_image'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_image'])->getRowArray();
        $this->data['setting_url'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => 'pengaduan_url'])->getRowArray();
        $this->view('layananpublik/pengaduanlayananpublik/prosedurpengaduan.php', $this->data);
    }

}