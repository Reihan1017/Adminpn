<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */

class Hubungikami extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------
    // FOLDER: hubungikami
    //--------------------------------------------------------------------

    public function alamatPengadilan()
    {
        // Baris ini sudah ada, biarkan saja
        $this->data['current_module']['judul_module'] = 'Alamat Pengadilan';

        // --- MULAI MODIFIKASI ---

        // 1. Hubungkan ke database
        $db = \Config\Database::connect();

        // 2. Cek jika ada pengiriman data dari form (method POST)
        if ($this->request->getMethod() === 'post') {
            // Ambil data yang dikirim dari form
            $updateData = [
                'nama_kantor'       => $this->request->getPost('nama_kantor'),
                'alamat'            => $this->request->getPost('alamat'),
                'telepon'           => $this->request->getPost('telepon'),
                'fax'               => $this->request->getPost('fax'),
                'email'             => $this->request->getPost('email'),
                'website'           => $this->request->getPost('website'),
                'google_maps_embed' => $this->request->getPost('google_maps_embed')
            ];

            // Lakukan proses update ke tabel 'kontak'
            $db->table('kontak')->where('id', 1)->update($updateData);

            // Siapkan pesan sukses untuk ditampilkan di halaman
            $this->data['pesan_sukses'] = "Data alamat berhasil diperbarui!";
        }

        // 3. Ambil data kontak terbaru dari database untuk ditampilkan
        $query = $db->table('kontak')->where('id', 1)->get();
        $this->data['kontak'] = $query->getRowArray(); // Kirim data ke view

        // --- SELESAI MODIFIKASI ---

        // Baris ini sudah ada, ini akan memuat view dan mengirim semua $this->data
        $this->view('hubungikami/alamatpengadilan.php', $this->data);
    }


    //--------------------------------------------------------------------
    // Method untuk Sosial Media 
    //--------------------------------------------------------------------
    public function sosialMedia()
    {
        $this->data['current_module']['judul_module'] = 'Sosial Media';
        $db = \Config\Database::connect();
        
        // Dapatkan layanan validasi bawaan CodeIgniter
        $validation = \Config\Services::validation();
    
        // Cek jika ada form yang disubmit (method POST)
        if ($this->request->getMethod() === 'post') {
            
            // Tentukan aturan untuk setiap input
            $rules = [
                'facebook'  => 'required|valid_url_strict',
                'instagram' => 'required|valid_url_strict',
                'youtube'   => 'required|valid_url_strict',
                'email'     => 'required|valid_email'
            ];
            
            // (Opsional) Pesan error kustom dalam Bahasa Indonesia
            $errors = [
                'facebook' => [
                    'required' => 'URL Facebook wajib diisi.',
                    'valid_url_strict' => 'Format URL Facebook tidak valid.'
                ],
                'instagram' => [
                    'required' => 'URL Instagram wajib diisi.',
                    'valid_url_strict' => 'Format URL Instagram tidak valid.'
                ],
                'youtube' => [
                    'required' => 'URL Youtube wajib diisi.',
                    'valid_url_strict' => 'Format URL Youtube tidak valid.'
                ],
                'email' => [
                    'required' => 'Alamat email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ];
    
            // Jalankan validasi. Jika lolos (true)...
            if ($this->validate($rules, $errors)) {
                // Lanjutkan proses simpan ke database
                $updateData = [
                    'facebook'  => $this->request->getPost('facebook'),
                    'instagram' => $this->request->getPost('instagram'),
                    'youtube'   => $this->request->getPost('youtube'),
                    'email'     => $this->request->getPost('email'),
                ];
                $db->table('sosial_media')->where('id', 1)->update($updateData);
    
                // Siapkan pesan SUKSES
                $this->data['pesan_sukses'] = "Data sosial media berhasil diperbarui!";
            
            } else {
                // Jika validasi GAGAL (false)...
                // Jangan simpan ke database, tapi kirim pesan error ke view
                $this->data['pesan_gagal'] = $validation->getErrors();
            }
        }
    
        // Ambil data terbaru dari database (selalu dijalankan)
        $query = $db->table('sosial_media')->where('id', 1)->get();
        $this->data['sosmed'] = $query->getRowArray();
        
        $this->view('hubungikami/sosialmedia.php', $this->data);
    }
}