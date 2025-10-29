<?php

namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Models\BeritaModel; // Hanya butuh BeritaModel

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 * @property \App\Models\BeritaModel $beritaModel // Properti untuk intellisense
 */
class Berita extends BaseController
{

    protected $beritaModel;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url', 'text']);
        
        // Buat instance dari Model
        $this->beritaModel = new BeritaModel();
        
        // Buat direktori upload berita jika belum ada
        // (Method simpan() dan hapus() sudah mengarah ke 'uploads/berita/')
        $uploadPath = ROOTPATH . 'public/uploads/berita/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // JS untuk editor teks
        $this->addJs($this->config->baseURL . 'public/vendors/tinymce/tinymce.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/halamanstatis.js?r=' . time());
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN BERITA (CRUD - Dinamis)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar berita di halaman admin.
     */
    public function beritaTerkini()
    {
        $pageData = [];
        $pageData['current_module']['judul_module'] = 'Manajemen Berita & A';

        // UBAH: Ambil data dari Model, bukan $this->db
        $pageData['berita_list'] = $this->beritaModel->getAllBerita();

        return $this->view('berita/beritaterkini.php', $pageData);
    }

    /**
     * Menampilkan form untuk menambah atau mengedit berita.
     * @param int|null $id ID berita jika dalam mode edit.
     */
    public function formBerita($id = null)
    {
        $pageData = [];
        $pageData['berita'] = null;
        $pageData['gambar_tambahan'] = [];

        if ($id) {
            // UBAH: Ambil data dari Model
            $data = $this->beritaModel->getBeritaWithGambar($id);
            $pageData['berita'] = $data['berita'];
            $pageData['gambar_tambahan'] = $data['gambar_tambahan'];
            
            if ($pageData['berita']) {
                $pageData['current_module']['judul_module'] = 'Edit Berita & Artikel';
            } else {
                return redirect()->to('/berita/beritaTerkini')->with('error', 'Berita tidak ditemukan.');
            }
        } else {
            $pageData['current_module']['judul_module'] = 'Tambah Berita & Artikel Baru';
        }

        return $this->view('berita/form_berita.php', $pageData);
    }

    /**
     * Menyimpan data berita baru atau yang diedit.
     */
    public function simpanBerita()
    {
        $id = $this->request->getPost('id');

        // ... (Validasi rules dan errors Anda SAMA, tidak perlu diubah) ...
        $rules = [
            'judul'           => 'required|min_length[5]|max_length[255]',
            'slug'            => 'required|alpha_dash|max_length[255]|is_unique[berita.slug,id,' . ($id ?? 0) . ']',
            'isi_berita'      => 'required',
            'tanggal_publish' => 'required|valid_date[Y-m-d\TH:i]',
            'status'          => 'required|in_list[published,draft]'
        ];
        
        // Ambil pesan error kustom Anda dari controller sebelumnya jika ada
        $errors = [
             'slug' => [
                 'required'   => 'Slug (URL) wajib diisi.',
                 'alpha_dash' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
                 'is_unique'  => 'Slug ini sudah digunakan oleh berita lain. Harap ganti.'
             ]
        ];
        
        $rules['gambar'] = ($id ? 'permit_empty|' : 'uploaded[gambar]|') . 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]';
        $rules['gambar_tambahan.*'] = 'permit_empty|max_size[gambar_tambahan,2048]|is_image[gambar_tambahan]|mime_in[gambar_tambahan,image/jpg,image/jpeg,image/png]';

        if (!$this->validate($rules, $errors)) { // Pastikan $errors dimasukkan
             return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- MULAI LOGIKA BARU UNTUK TANGGAL ---

        $slug = $this->request->getPost('slug');
        $new_status = $this->request->getPost('status'); // <-- Ambil status baru dari form

        // Siapkan data dasar
        $data = [
            'judul'           => $this->request->getPost('judul'),
            'slug'            => $slug,
            'isi_berita'      => $this->request->getPost('isi_berita'),
            // 'tanggal_publish' akan kita tentukan di bawah
            'status'          => $new_status
        ];

        if ($id) { // Mode Edit (Update)
            // 1. Dapatkan status LAMA dari database
            $beritaLama = $this->beritaModel->find($id);
            $old_status = $beritaLama['status'] ?? 'draft'; // Ambil status lama

            // 2. Cek transisi dari 'draft' ke 'published'
            if ($old_status === 'draft' && $new_status === 'published') {
                // INI LOGIKA UTAMANYA:
                // Jika status berubah dari draft ke publish, set tanggal ke SEKARANG.
                $data['tanggal_publish'] = date('Y-m-d H:i:s'); // Waktu SEKARANG
            } else {
                // Jika tidak (misal: draft -> draft, publish -> publish, publish -> draft),
                // biarkan tanggal publish dari inputan form (memungkinkan re-schedule).
                $data['tanggal_publish'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('tanggal_publish')));
            }
        } else { // Mode Tambah (Create)
            // Post baru, selalu ambil dari form.
            // Jika status 'published', ini jadi waktu publish.
            // Jika status 'draft', ini jadi waktu publish yang *direncanakan*.
            $data['tanggal_publish'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('tanggal_publish')));
        }

        // --- SELESAI LOGIKA BARU UNTUK TANGGAL ---


        $beritaId = $id;

        // Proses Upload Gambar Utama (Cover)
        // ... (Kode upload gambar Anda SAMA PERSIS, tidak perlu diubah) ...
        $fileGambarUtama = $this->request->getFile('gambar');
        if ($fileGambarUtama && $fileGambarUtama->isValid() && !$fileGambarUtama->hasMoved()) {
            if($id) { 
                $this->hapusFileGambarLama($id, 'uploads/berita'); 
            }
            $namaGambarUtama = $slug . '_' . time() . '_' . $fileGambarUtama->getRandomName(); 
            if ($fileGambarUtama->move('uploads/berita', $namaGambarUtama)) {
                $data['gambar'] = $namaGambarUtama;
            } else {
                 log_message('error', '[simpanBerita] Gagal memindahkan file gambar utama: ' . $fileGambarUtama->getErrorString());
                 return redirect()->back()->withInput()->with('errors', ['gambar' => 'Gagal mengupload gambar utama.']);
            }
        }

        // Simpan/Update Data Utama ke tabel 'berita'
        try {
            if ($id) { // Mode Edit
                $this->beritaModel->update($id, $data);
            } else { // Mode Tambah
                $this->beritaModel->insert($data);
                $beritaId = $this->beritaModel->getInsertID(); 
            }
        } catch (DatabaseException $e) {
            log_message('error', '[simpanBerita] Database Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan database saat menyimpan data berita.');
        }

        // Proses Upload Gambar Tambahan (Galeri)
        // ... (Kode upload gambar tambahan Anda SAMA PERSIS, tidak perlu diubah) ...
        if ($beritaId) {
            $filesTambahan = $this->request->getFiles();

            if(isset($filesTambahan['gambar_tambahan'])) {
                foreach($filesTambahan['gambar_tambahan'] as $file) {
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $namaFileTambahan = $slug . '_extra_' . time() . '_' . $file->getRandomName(); 
                        if ($file->move('uploads/berita', $namaFileTambahan)) {
                            
                            $this->beritaModel->simpanGambarTambahan([
                                'berita_id' => $beritaId,
                                'nama_file' => $namaFileTambahan,
                                'urutan'    => 100 
                            ]);
                        } else {
                            log_message('error', '[simpanBerita] Gagal memindahkan file gambar tambahan: ' . $file->getErrorString() . ' - ' . $file->getName());
                        }
                    }
                }
            }
        }

        return redirect()->to('/berita/beritaTerkini')->with('success', 'Data berita berhasil disimpan!');
    }

    /**
     * Menghapus data berita berdasarkan ID.
     * @param int $id ID berita yang akan dihapus.
     */
    public function hapusBerita($id)
    {
        // UBAH: Ambil semua data (termasuk nama file) dari Model
        $dataHapus = $this->beritaModel->getDataUntukHapus($id);
        
        if ($dataHapus['berita']) {
            // 1. Hapus file gambar utama
            if (!empty($dataHapus['berita']['gambar']) && file_exists('uploads/berita/' . $dataHapus['berita']['gambar'])) {
                @unlink('uploads/berita/' . $dataHapus['berita']['gambar']);
            }

            // 2. Hapus file gambar tambahan
            foreach ($dataHapus['gambar_tambahan'] as $img) {
                if (!empty($img['nama_file']) && file_exists('uploads/berita/' . $img['nama_file'])) {
                    @unlink('uploads/berita/' . $img['nama_file']);
                }
            }
            
            // 3. Hapus data dari database menggunakan Model
            //    Model akan menghapus dari tabel 'berita' dan 'berita_gambar'
            if ($this->beritaModel->hapusBeritaDanGambar($id)) {
                return redirect()->back()->with('success', 'Berita berhasil dihapus!');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus data dari database.');
            }

        }
        return redirect()->back()->with('error', 'Berita tidak ditemukan!');
    }

    /**
     * Helper method untuk menghapus file gambar LAMA saat edit.
     * UBAH: Nama dan logic sedikit diubah agar lebih jelas
     */
    private function hapusFileGambarLama(int $id, string $folder = 'uploads/berita')
    {
        // UBAH: Ambil data dari Model
        $record = $this->beritaModel->find($id); // find() hanya ambil data tabel utama
        
        if ($record && !empty($record['gambar'])) { // 'gambar' karena returnType array
            $filePath = FCPATH . $folder . DIRECTORY_SEPARATOR . $record['gambar'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    // HAPUS: Fungsi hapusFileGambar lama tidak terpakai
    // private function hapusFileGambar(string $table, int $id, string $field, string $folder = 'uploads/berita') { ... }
}
