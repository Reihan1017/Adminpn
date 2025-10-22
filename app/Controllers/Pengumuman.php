<?php

namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;
// TAMBAHKAN: Panggil Model Anda
use App\Models\BeritaModel; 

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 * @property \CodeIgniter\Database\BaseConnection $db
 * @property \App\Models\BeritaModel $beritaModel // TAMBAHKAN: Untuk intellisense
 */
class Pengumuman extends BaseController
{
    // HAPUS: $db akan di-handle oleh Model
    // protected $db;

    // TAMBAHKAN: Properti untuk menampung Model
    protected $beritaModel;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url', 'text']);
        
        // TAMBAHKAN: Buat instance dari Model
        $this->beritaModel = new BeritaModel();

        // HAPUS: Tidak perlu konek DB manual lagi
        // if (!isset($this->db)) {
        //     $this->db = \Config\Database::connect();
        // }
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
        $pageData['current_module']['judul_module'] = 'Manajemen Berita & Artikel';

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
                return redirect()->to('/pengumuman/beritaTerkini')->with('error', 'Berita tidak ditemukan.');
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
        $errors = [ /* ... pesan error Anda ... */ ];
        $rules['gambar'] = ($id ? 'permit_empty|' : 'uploaded[gambar]|') . 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]';
        $rules['gambar_tambahan.*'] = 'permit_empty|max_size[gambar_tambahan,2048]|is_image[gambar_tambahan]|mime_in[gambar_tambahan,image/jpg,image/jpeg,image/png]';

        if (!$this->validate($rules, $errors)) {
             return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $slug = $this->request->getPost('slug');

        $data = [
            'judul'           => $this->request->getPost('judul'),
            'slug'            => $slug,
            'isi_berita'      => $this->request->getPost('isi_berita'),
            'tanggal_publish' => date('Y-m-d H:i:s', strtotime($this->request->getPost('tanggal_publish'))),
            'status'          => $this->request->getPost('status')
        ];

        $beritaId = $id;

        // Proses Upload Gambar Utama (Cover)
        $fileGambarUtama = $this->request->getFile('gambar');
        if ($fileGambarUtama && $fileGambarUtama->isValid() && !$fileGambarUtama->hasMoved()) {
            if($id) { 
                // UBAH: Panggil helper baru yang sudah pakai model
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
                // UBAH: Gunakan Model->update()
                $this->beritaModel->update($id, $data);
            } else { // Mode Tambah
                // UBAH: Gunakan Model->insert()
                $this->beritaModel->insert($data);
                
                // UBAH: Ambil ID dari Model
                $beritaId = $this->beritaModel->getInsertID(); 
            }
        } catch (DatabaseException $e) {
            log_message('error', '[simpanBerita] Database Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan database saat menyimpan data berita.');
        }

        // Proses Upload Gambar Tambahan (Galeri)
        if ($beritaId) {
            $filesTambahan = $this->request->getFiles();

            if(isset($filesTambahan['gambar_tambahan'])) {
                foreach($filesTambahan['gambar_tambahan'] as $file) {
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $namaFileTambahan = $slug . '_extra_' . time() . '_' . $file->getRandomName(); 
                        if ($file->move('uploads/berita', $namaFileTambahan)) {
                            
                            // UBAH: Gunakan Model untuk simpan gambar tambahan
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

        return redirect()->to('/pengumuman/beritaTerkini')->with('success', 'Data berita berhasil disimpan!');
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