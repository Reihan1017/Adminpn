<?php

namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 * @property \CodeIgniter\Database\BaseConnection $db
 */
class Pengumuman extends BaseController // Pastikan extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct(); // PENTING: Panggil constructor parent
        helper(['form', 'url', 'text']);
        if (!isset($this->db)) {
            $this->db = \Config\Database::connect();
        }
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
        
        // Ambil kolom yang dibutuhkan, termasuk 'slug' dan 'status'
        $pageData['berita_list'] = $this->db->table('berita')
                                             ->select('id, judul, slug, gambar, tanggal_publish, status') 
                                             ->orderBy('tanggal_publish', 'DESC')
                                             ->get()->getResultArray();
    
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
            $beritaData = $this->db->table('berita')->getWhere(['id' => $id])->getRowArray();
            if ($beritaData) {
                $pageData['berita'] = $beritaData;
                $pageData['gambar_tambahan'] = $this->db->table('berita_gambar')
                                                       ->where('berita_id', $id)
                                                       ->orderBy('urutan', 'ASC')
                                                       ->get()->getResultArray();
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
     * Sekarang menerima SLUG MANUAL dari form dan memvalidasi keunikannya.
     */
    public function simpanBerita()
    {
        $id = $this->request->getPost('id');

        // Aturan validasi
        $rules = [
            'judul'           => 'required|min_length[5]|max_length[255]',
            // ATURAN VALIDASI SLUG BARU: wajib, alfanumerik+dash, dan UNIK di tabel 'berita' kolom 'slug'.
            // is_unique[table.field,ignore_field,ignore_value]
            'slug'            => 'required|alpha_dash|max_length[255]|is_unique[berita.slug,id,' . ($id ?? 0) . ']',
            'isi_berita'      => 'required',
            'tanggal_publish' => 'required|valid_date[Y-m-d\TH:i]',
            'status'          => 'required|in_list[published,draft]'
        ];
        
        // Pesan error kustom untuk slug
        $errors = [
            'slug' => [
                'required'   => 'Slug (URL) wajib diisi.',
                'alpha_dash' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
                'is_unique'  => 'Slug ini sudah digunakan oleh berita lain. Harap ganti.'
            ]
        ];

        // Aturan gambar utama
        $rules['gambar'] = ($id ? 'permit_empty|' : 'uploaded[gambar]|') . 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]';
        // Aturan gambar tambahan
        $rules['gambar_tambahan.*'] = 'permit_empty|max_size[gambar_tambahan,2048]|is_image[gambar_tambahan]|mime_in[gambar_tambahan,image/jpg,image/jpeg,image/png]';

        if (!$this->validate($rules, $errors)) { // Kirim $errors ke validator
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil slug dari form input, BUKAN dibuat dari judul
        $slug = $this->request->getPost('slug');

        // Siapkan data dasar
        $data = [
            'judul'           => $this->request->getPost('judul'),
            'slug'            => $slug, // Gunakan slug dari input manual
            'isi_berita'      => $this->request->getPost('isi_berita'),
            'tanggal_publish' => date('Y-m-d H:i:s', strtotime($this->request->getPost('tanggal_publish'))),
            'status'          => $this->request->getPost('status')
        ];

        $beritaId = $id; // Gunakan ID lama jika mode edit

        // 3. Proses Upload Gambar Utama (Cover)
        $fileGambarUtama = $this->request->getFile('gambar');
        if ($fileGambarUtama && $fileGambarUtama->isValid() && !$fileGambarUtama->hasMoved()) {
            // Hapus gambar lama dulu jika sedang mengedit
            if($id) { 
                $this->hapusFileGambar('berita', $id, 'gambar', 'uploads/berita'); 
            }
            // Buat nama file baru yang unik (menggunakan slug manual)
            $namaGambarUtama = $slug . '_' . time() . '_' . $fileGambarUtama->getRandomName(); 
            // Pindahkan file ke folder tujuan
            if ($fileGambarUtama->move('uploads/berita', $namaGambarUtama)) {
                $data['gambar'] = $namaGambarUtama; // Simpan nama file baru ke data
            } else {
                // Catat error jika gagal upload
                log_message('error', '[simpanBerita] Gagal memindahkan file gambar utama: ' . $fileGambarUtama->getErrorString());
                 return redirect()->back()->withInput()->with('errors', ['gambar' => 'Gagal mengupload gambar utama.']);
            }
        }

        // 4. Simpan/Update Data Utama ke tabel 'berita'
        try {
            if ($id) { // Mode Edit
                // Slug di sini tidak perlu logic auto-update/check keunikan karena sudah divalidasi di atas
                $this->db->table('berita')->where('id', $id)->update($data);
            } else { // Mode Tambah
                // Slug di sini tidak perlu logic auto-update/check keunikan karena sudah divalidasi di atas
                $this->db->table('berita')->insert($data);
                $beritaId = $this->db->insertID(); // Dapatkan ID berita yang baru saja dibuat
            }
        } catch (DatabaseException $e) {
            // Catat error database dan beri pesan ke user
            log_message('error', '[simpanBerita] Database Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan database saat menyimpan data berita.');
        }

        // 5. Proses Upload Gambar Tambahan (Galeri)
        if ($beritaId) { // Hanya proses jika ID berita valid
            $filesTambahan = $this->request->getFiles(); // Ambil semua file

            // Cek apakah ada file yang diupload untuk 'gambar_tambahan'
            if(isset($filesTambahan['gambar_tambahan'])) {
                // Loop melalui setiap file dalam array 'gambar_tambahan'
                foreach($filesTambahan['gambar_tambahan'] as $file) {
                    // Pastikan file valid dan belum dipindahkan
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        // Buat nama file unik (menggunakan slug manual)
                        $namaFileTambahan = $slug . '_extra_' . time() . '_' . $file->getRandomName(); 
                        // Pindahkan file ke folder tujuan
                        if ($file->move('uploads/berita', $namaFileTambahan)) {
                            // Jika berhasil, simpan informasinya ke tabel 'berita_gambar'
                            $this->db->table('berita_gambar')->insert([
                                'berita_id' => $beritaId, // Hubungkan ke ID berita
                                'nama_file' => $namaFileTambahan,
                                'urutan'    => 100 // Anda bisa menambahkan input urutan di form jika perlu
                            ]);
                        } else {
                            // Catat error jika gagal memindahkan file
                            log_message('error', '[simpanBerita] Gagal memindahkan file gambar tambahan: ' . $file->getErrorString() . ' - ' . $file->getName());
                            // Anda bisa memutuskan apakah ingin melanjutkan atau menghentikan proses di sini
                        }
                    }
                } // Akhir foreach
            } // Akhir if isset
        } // Akhir if $beritaId

        // 6. Redirect ke halaman daftar dengan pesan sukses
        return redirect()->to('/pengumuman/beritaTerkini')->with('success', 'Data berita berhasil disimpan!');
    }

    /**
     * Menghapus data berita berdasarkan ID.
     * @param int $id ID berita yang akan dihapus.
     */
    public function hapusBerita($id)
    {
        $berita = $this->db->table('berita')->getWhere(['id' => $id])->getRow();
        if ($berita) {
            $this->hapusFileGambar('berita', $id, 'gambar', 'uploads/berita');

            $gambarTambahan = $this->db->table('berita_gambar')->where('berita_id', $id)->get()->getResult();
            foreach ($gambarTambahan as $img) {
                if (!empty($img->nama_file) && file_exists('uploads/berita/' . $img->nama_file)) {
                    unlink('uploads/berita/' . $img->nama_file);
                }
            }
            
            $this->db->table('berita')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Berita berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Berita tidak ditemukan!');
    }

    /**
     * Helper method untuk menghapus file gambar.
     */
    private function hapusFileGambar(string $table, int $id, string $field, string $folder = 'uploads/berita')
    {
        $record = $this->db->table($table)->select($field)->getWhere(['id' => $id])->getRow();
        if ($record && !empty($record->$field)) {
            $filePath = FCPATH . $folder . DIRECTORY_SEPARATOR . $record->$field;
            if (file_exists($filePath)) {
                @unlink($filePath); // Gunakan @ untuk menekan error jika file tidak bisa dihapus
            }
        }
    }
}