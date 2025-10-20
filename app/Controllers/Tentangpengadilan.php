<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */

class Tentangpengadilan extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url']);
        $this->db = \Config\Database::connect();
    }

    public function pengantarKetua()
    {
        $this->data['current_module']['judul_module'] = 'Pengantar Ketua Pengadilan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nama_ketua'    => 'required',
                'jabatan_ketua' => 'required',
                'isi_pengantar' => 'required',
                'foto_ketua'    => 'max_size[foto_ketua,2048]|is_image[foto_ketua]|mime_in[foto_ketua,image/jpg,image/jpeg,image/png]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'nama_ketua'    => $this->request->getPost('nama_ketua'),
                    'jabatan_ketua' => $this->request->getPost('jabatan_ketua'),
                    'isi_pengantar' => $this->request->getPost('isi_pengantar')
                ];

                $fileFoto = $this->request->getFile('foto_ketua');
                if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
                    $oldData = $this->db->table('pengantar_ketua')->getWhere(['id' => 1])->getRow();
                    if ($oldData && !empty($oldData->foto_ketua) && file_exists('uploads/profil/' . $oldData->foto_ketua)) {
                        unlink('uploads/profil/' . $oldData->foto_ketua);
                    }
                    $namaFoto = $fileFoto->getRandomName();
                    $fileFoto->move('uploads/profil', $namaFoto);
                    $data['foto_ketua'] = $namaFoto;
                }

                $this->db->table('pengantar_ketua')->where('id', 1)->update($data);
                session()->setFlashdata('success', 'Data Pengantar Ketua berhasil diperbarui!'); // Menggunakan session flashdata
                return redirect()->to('/tentang-pengadilan/pengantar-ketua'); // Redirect kembali
            } else {
                // Simpan error ke flashdata untuk ditampilkan di view
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput(); // Kembali ke form dengan input lama dan error
            }
        }

        $this->data['pengantar'] = $this->db->table('pengantar_ketua')->getWhere(['id' => 1])->getRowArray();
        $this->view('tentangpengadilan/pengantarketua.php', $this->data);
    }

    public function visiMisi()
    {
        $this->data['current_module']['judul_module'] = 'Visi & Misi';
        $this->data['visi'] = $this->db->table('visi')->getWhere(['id' => 1])->getRowArray();
        $this->data['misi_list'] = $this->db->table('misi')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/visimisi.php', $this->data);
    }

    public function simpanVisiMisi()
    {
        $dataVisi = ['teks_visi' => $this->request->getPost('teks_visi')];
        $fileVisi = $this->request->getFile('gambar_visi');
        if ($fileVisi && $fileVisi->isValid() && !$fileVisi->hasMoved()) {
            $namaFileVisi = $fileVisi->getRandomName();
            $fileVisi->move('uploads/profil', $namaFileVisi);
            $dataVisi['gambar_visi'] = $namaFileVisi;
            // Hapus gambar lama jika perlu
        }
        $this->db->table('visi')->where('id', 1)->update($dataVisi);

        $misi_ids = $this->request->getPost('misi_id');
        $teks_misi = $this->request->getPost('teks_misi');
        $urutan_misi = $this->request->getPost('urutan_misi');

        if (!empty($misi_ids)) {
            for ($i = 0; $i < count($misi_ids); $i++) {
                $id = $misi_ids[$i];
                $dataMisi = [
                    'teks_misi' => $teks_misi[$i],
                    'urutan'    => $urutan_misi[$i]
                ];

                $fileMisi = $this->request->getFile('gambar_misi_' . $id);
                if ($fileMisi && $fileMisi->isValid() && !$fileMisi->hasMoved()) {
                    $namaFileMisi = $fileMisi->getRandomName();
                    $fileMisi->move('uploads/profil', $namaFileMisi);
                    $dataMisi['gambar_misi'] = $namaFileMisi;
                    // Hapus gambar lama jika perlu
                }
                $this->db->table('misi')->where('id', $id)->update($dataMisi);
            }
        }
        return redirect()->to('/tentang-pengadilan/visi-misi')->with('success', 'Data Visi & Misi berhasil diperbarui!');
    }

    public function sejarah()
    {
        $nama_halaman = 'sejarah';
        $this->data['current_module']['judul_module'] = 'Sejarah Pengadilan';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Sejarah Pengadilan berhasil diperbarui!');
                return redirect()->to('/tentang-pengadilan/sejarah');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/sejarah.php', $this->data);
    }

    public function struktur()
    {
        $nama_halaman = 'struktur';
        $this->data['current_module']['judul_module'] = 'Struktur Organisasi';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'judul_halaman'  => 'required',
                'gambar_halaman' => 'max_size[gambar_halaman,3072]|is_image[gambar_halaman]|mime_in[gambar_halaman,image/jpg,image/jpeg,image/png]'
            ];

            if ($this->validate($rules)) {
                $dataUpdate = ['judul_halaman' => $this->request->getPost('judul_halaman')];
                $fileGambar = $this->request->getFile('gambar_halaman');

                if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
                    $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                    if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/profil/' . $oldData->gambar_halaman)) {
                        unlink('uploads/profil/' . $oldData->gambar_halaman);
                    }
                    $namaGambar = $fileGambar->getRandomName();
                    $fileGambar->move('uploads/profil', $namaGambar);
                    $dataUpdate['gambar_halaman'] = $namaGambar;
                }

                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                session()->setFlashdata('success', 'Data Struktur Organisasi berhasil diperbarui!');
                return redirect()->to('/tentang-pengadilan/struktur-organisasi');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/struktur.php', $this->data);
    }

    public function wilayah()
    {
        $nama_halaman = 'wilayah';
        $this->data['current_module']['judul_module'] = 'Wilayah Yuridiksi';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Wilayah Yuridiksi berhasil diperbarui!');
                return redirect()->to('/tentang-pengadilan/wilayah-hukum');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/wilayah.php', $this->data);
    }
    
/*
    |--------------------------------------------------------------------------
    | PROFIL HAKIM DAN PEGAWAI (CRUD)
    |--------------------------------------------------------------------------
    | Method-method berikut mengelola data pegawai berdasarkan kategori.
    */

    /**
     * Menampilkan daftar profil Hakim.
     */
    public function profilHakim()
    {
        $this->data['current_module']['judul_module'] = 'Profil Hakim';
        $this->data['kategori'] = 'hakim';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'hakim')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilhakim.php', $this->data);
    }

    /**
     * Menampilkan daftar profil Kepaniteraan.
     */
    public function profilKepaniteraan()
    {
        $this->data['current_module']['judul_module'] = 'Profil Kepaniteraan';
        $this->data['kategori'] = 'kepaniteraan';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'kepaniteraan')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilkepaniteraan.php', $this->data);
    }

    /**
     * Menampilkan daftar profil Kesekretariatan.
     */
    public function profilKesekretariatan()
    {
        $this->data['current_module']['judul_module'] = 'Profil Kesekretariatan';
        $this->data['kategori'] = 'kesekretariatan';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'kesekretariatan')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilkesekretariatan.php', $this->data);
    }

    /**
     * Menampilkan daftar profil PPPK.
     */
    public function profilPppk()
    {
        $this->data['current_module']['judul_module'] = 'Profil PPPK';
        $this->data['kategori'] = 'pppk';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'pppk')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilpppk.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah atau mengedit profil pegawai.
     * Menggunakan parameter $id untuk membedakan mode tambah (null) atau edit (ada ID).
     * @param string $kategori Kategori pegawai (hakim, kepaniteraan, dll.)
     * @param int|null $id ID pegawai jika dalam mode edit
     */
    public function formProfil($kategori, $id = null)
    {
        $this->data['profil'] = null; // Default untuk mode tambah
        $this->data['kategori'] = $kategori;

        if ($id) {
            // Jika $id ada, ambil data pegawai dari DB untuk mode edit
            $this->data['profil'] = $this->db->table('pegawai')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Profil';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Profil Baru';
        }
        // Memuat view form yang sama untuk tambah dan edit
        $this->view('tentangpengadilan/hakimdanpegawai/form_profil.php', $this->data);
    }

    /**
     * Menyimpan data profil pegawai baru atau yang diedit.
     * Menangani validasi input dan upload file foto.
     */
    public function simpanProfil()
    {
        $id = $this->request->getPost('id');
        $kategori = $this->request->getPost('kategori');

        $rules = [
            'nama_pegawai' => 'required',
            'jabatan'      => 'required',
            'foto_pegawai' => 'max_size[foto_pegawai,2048]|is_image[foto_pegawai]' // Validasi file gambar maks 2MB
        ];

        // Validasi input form
        if (! $this->validate($rules)) {
            // Jika validasi gagal, kembali ke form dengan pesan error dan input lama
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kumpulkan data dari form ke dalam array
        $data = [
            'nama_pegawai'  => $this->request->getPost('nama_pegawai'),
            'nip'           => $this->request->getPost('nip'),
            'pangkat_gol'   => $this->request->getPost('pangkat_gol'),
            'jabatan'       => $this->request->getPost('jabatan'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'urutan'        => $this->request->getPost('urutan'),
            'kategori'      => $kategori
        ];

        // Proses upload file foto jika ada file yang diunggah
        $fileFoto = $this->request->getFile('foto_pegawai');
        if ($fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            // Hapus foto lama jika ada (untuk mode edit)
            if ($id) {
                $profilLama = $this->db->table('pegawai')->select('foto_pegawai')->getWhere(['id' => $id])->getRow();
                if ($profilLama && !empty($profilLama->foto_pegawai) && file_exists('uploads/pegawai/' . $profilLama->foto_pegawai)) {
                    unlink('uploads/pegawai/' . $profilLama->foto_pegawai);
                }
            }
            $namaFoto = $fileFoto->getRandomName(); // Generate nama file acak
            $fileFoto->move('uploads/pegawai', $namaFoto); // Pindahkan file ke folder uploads
            $data['foto_pegawai'] = $namaFoto; // Simpan nama file baru ke data
        }

        // Simpan data ke database (INSERT untuk data baru, UPDATE untuk data edit)
        if ($id) {
            $this->db->table('pegawai')->where('id', $id)->update($data);
        } else {
            $this->db->table('pegawai')->insert($data);
        }

        // Tentukan URL redirect berdasarkan kategori
        $redirect_url = 'tentangpengadilan/profil' . ucfirst($kategori);
        return redirect()->to($redirect_url)->with('success', 'Data profil berhasil disimpan!');
    }

    /**
     * Menghapus data profil pegawai berdasarkan ID.
     * Termasuk menghapus file foto terkait dari server.
     * @param int $id ID pegawai yang akan dihapus
     */
    public function hapusProfil($id)
    {
        // Ambil data profil untuk mendapatkan nama file foto
        $profil = $this->db->table('pegawai')->getWhere(['id' => $id])->getRow();
        if ($profil) {
            // Hapus file foto dari server jika ada
            if (! empty($profil->foto_pegawai) && file_exists('uploads/pegawai/' . $profil->foto_pegawai)) {
                unlink('uploads/pegawai/' . $profil->foto_pegawai);
            }
            // Hapus record dari database
            $this->db->table('pegawai')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Profil berhasil dihapus!');
        }
        // Jika ID tidak ditemukan
        return redirect()->back()->with('errors', 'Profil tidak ditemukan!');
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL PERUBAHAN & AGEN PERUBAHAN
    |--------------------------------------------------------------------------
    */

    /**
     * Mengelola halaman Profil Role Model (data tunggal).
     */
    public function roleModel()
    {
        $this->data['current_module']['judul_module'] = 'Profil Role Model';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nama'         => 'required',
                'jabatan'      => 'required',
                'poster_image' => 'max_size[poster_image,3072]|is_image[poster_image]' // Validasi gambar poster maks 3MB
            ];

            if ($this->validate($rules)) {
                $dataUpdate = [
                    'nama'          => $this->request->getPost('nama'),
                    'nip'           => $this->request->getPost('nip'),
                    'pangkat_gol'   => $this->request->getPost('pangkat_gol'),
                    'jabatan'       => $this->request->getPost('jabatan'),
                    'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
                    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                ];

                $filePoster = $this->request->getFile('poster_image');
                if ($filePoster && $filePoster->isValid() && !$filePoster->hasMoved()) {
                    $oldData = $this->db->table('role_model')->getWhere(['id' => 1])->getRow();
                    if ($oldData && !empty($oldData->poster_image) && file_exists('uploads/profil/' . $oldData->poster_image)) {
                        unlink('uploads/profil/' . $oldData->poster_image);
                    }
                    $namaGambar = $filePoster->getRandomName();
                    $filePoster->move('uploads/profil', $namaGambar);
                    $dataUpdate['poster_image'] = $namaGambar;
                }

                $this->db->table('role_model')->where('id', 1)->update($dataUpdate);
                session()->setFlashdata('success', 'Data Profil Role Model berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/roleModel');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['rolemodel'] = $this->db->table('role_model')->getWhere(['id' => 1])->getRowArray();
        $this->view('tentangpengadilan/profilperubahan/rolemodel.php', $this->data);
    }

    /**
     * Menampilkan daftar Agen Perubahan.
     */
    public function profilPerubahan()
    {
        $this->data['current_module']['judul_module'] = 'Agen Perubahan';
        $this->data['agen_list'] = $this->db->table('agen_perubahan')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/profilperubahan/profilperubahan.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah atau mengedit Agen Perubahan.
     * @param int|null $id ID agen jika dalam mode edit
     */
    public function formAgenPerubahan($id = null)
    {
        $this->data['agen'] = null;
        if ($id) {
            $this->data['agen'] = $this->db->table('agen_perubahan')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Agen Perubahan';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Agen Perubahan';
        }
        $this->view('tentangpengadilan/profilperubahan/form_agen_perubahan.php', $this->data);
    }

    /**
     * Menyimpan data Agen Perubahan.
     */
    public function simpanAgenPerubahan()
    {
        $id = $this->request->getPost('id');
        // Validasi gambar: wajib saat tambah, opsional saat edit
        $rules = [
            'poster_image' => ($id ? '' : 'uploaded[poster_image]|') . 'max_size[poster_image,3072]|is_image[poster_image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'    => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'urutan'  => $this->request->getPost('urutan'),
        ];

        $filePoster = $this->request->getFile('poster_image');
        if ($filePoster->isValid() && !$filePoster->hasMoved()) {
            // Hapus gambar lama jika edit
            if($id) {
                $agenLama = $this->db->table('agen_perubahan')->select('poster_image')->getWhere(['id' => $id])->getRow();
                 if ($agenLama && !empty($agenLama->poster_image) && file_exists('uploads/profil/' . $agenLama->poster_image)) {
                    unlink('uploads/profil/' . $agenLama->poster_image);
                }
            }
            $namaGambar = $filePoster->getRandomName();
            $filePoster->move('uploads/profil', $namaGambar);
            $data['poster_image'] = $namaGambar;
        }

        if ($id) {
            $this->db->table('agen_perubahan')->where('id', $id)->update($data);
        } else {
            $this->db->table('agen_perubahan')->insert($data);
        }
        
        return redirect()->to('/tentangpengadilan/profilPerubahan')->with('success', 'Data Agen Perubahan berhasil disimpan!');
    }

    /**
     * Menghapus data Agen Perubahan.
     * @param int $id ID agen yang akan dihapus
     */
    public function hapusAgenPerubahan($id)
    {
        $agen = $this->db->table('agen_perubahan')->getWhere(['id' => $id])->getRow();
        if ($agen) {
            if (!empty($agen->poster_image) && file_exists('uploads/profil/' . $agen->poster_image)) {
                unlink('uploads/profil/' . $agen->poster_image);
            }
            $this->db->table('agen_perubahan')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Profil Agen Perubahan berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN KEPANITERAAN (Teks Statis)
    |--------------------------------------------------------------------------
    */

    /**
     * Mengelola halaman teks statis Kepaniteraan Pidana.
     */
    public function kepaniteraanPidana()
    {
        $nama_halaman = 'kepaniteraan-pidana';
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Pidana';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Kepaniteraan Pidana berhasil diperbarui!');
                return redirect()->to('/tentang-pengadilan/kepaniteraan-pidana');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanpidana.php', $this->data);
    }

/**
     * Mengelola halaman teks statis Kepaniteraan Perdata.
     */
    public function kepaniteraanPerdata()
    {
        $nama_halaman = 'kepaniteraan-perdata';
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Perdata';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Kepaniteraan Perdata berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/kepaniteraan-perdata');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanperdata.php', $this->data);
    }

    /**
     * Mengelola halaman teks statis Kepaniteraan Hukum.
     */
    public function kepaniteraanHukum()
    {
        $nama_halaman = 'kepaniteraan-hukum';
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Hukum';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Kepaniteraan Hukum berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/kepaniteraan-hukum');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanhukum.php', $this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN SISTEM PENGELOLAAN PN
    |--------------------------------------------------------------------------
    */

    /**
     * (Placeholder) Method untuk halaman E-learning.
     * Saat ini hanya menampilkan view.
     */
    public function eLearning()
    {
        $this->data['current_module']['judul_module'] = 'E-learning';
        $this->view('tentangpengadilan/sistempengelolaanpn/elearning.php', $this->data);
    }

    /**
     * Mengelola pengaturan link eksternal JDIH.
     * Menyimpan URL ke tabel pengaturan_situs.
     */
    public function jdihPnCiamis()
    {
        $nama_pengaturan = 'url_jdih';
        $this->data['current_module']['judul_module'] = 'Pengaturan Link JDIH';

        if ($this->request->getMethod() === 'post') {
            $rules = ['url' => 'required|valid_url_strict'];

            if ($this->validate($rules)) {
                $dataUpdate = ['nilai_pengaturan' => $this->request->getPost('url')];
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', $nama_pengaturan)->update($dataUpdate);
                session()->setFlashdata('success', 'URL Link JDIH berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/jdih');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['pengaturan'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => $nama_pengaturan])->getRowArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/jdihpnciamis.php', $this->data);
    }

    /**
     * Mengelola pengaturan link eksternal Kebijakan.
     * Menyimpan URL ke tabel pengaturan_situs.
     */
    public function kebijakan()
    {
        $nama_pengaturan = 'url_kebijakan';
        $this->data['current_module']['judul_module'] = 'Pengaturan Link Kebijakan';

        if ($this->request->getMethod() === 'post') {
            $rules = ['url' => 'required|valid_url_strict'];

            if ($this->validate($rules)) {
                $dataUpdate = ['nilai_pengaturan' => $this->request->getPost('url')];
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', $nama_pengaturan)->update($dataUpdate);
                session()->setFlashdata('success', 'URL Link Kebijakan berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/kebijakan');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }

        $this->data['pengaturan'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => $nama_pengaturan])->getRowArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/kebijakan.php', $this->data);
    }

    /**
     * Mengelola halaman Rencana Strategis (teks + upload PDF single).
     * Menggunakan tabel halaman_profil.
     */
    public function rencanaStrategis()
    {
        $nama_halaman = 'rencana-strategis';
        $this->data['current_module']['judul_module'] = 'Rencana Strategis';
    
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'judul_halaman' => 'required', // Tambahkan validasi judul jika perlu
                'file_pdf'      => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]' // PDF, maks 10MB
            ];
    
            if ($this->validate($rules)) {
                $dataUpdate = [
                    'judul_halaman' => $this->request->getPost('judul_halaman')
                ];
    
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
                session()->setFlashdata('success', 'File Rencana Strategis berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/rencana-strategis');
            } else {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
        }
    
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/rencanastrategis.php', $this->data);
    }

    /**
     * Menampilkan halaman manajemen Rencana Kerja (daftar dokumen).
     */
    public function rencanaKerja()
    {
        $this->data['current_module']['judul_module'] = 'Rencana Kerja dan Anggaran';
        $this->data['dokumen_list'] = $this->db->table('rencana_kerja')
                                             ->orderBy('tahun', 'DESC')->orderBy('urutan', 'ASC')
                                             ->get()->getResultArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/rencanakerja.php', $this->data);
    }
    
    /**
     * Menampilkan form untuk menambah/mengedit dokumen Rencana Kerja.
     * @param int|null $id ID dokumen jika dalam mode edit
     */
    public function formRencanaKerja($id = null)
    {
        $this->data['dokumen'] = null;
        if ($id) {
            $this->data['dokumen'] = $this->db->table('rencana_kerja')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Dokumen Rencana Kerja';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Dokumen Rencana Kerja';
        }
        $this->view('tentangpengadilan/sistempengelolaanpn/form_rencanakerja.php', $this->data);
    }
    
    /**
     * Menyimpan data dokumen Rencana Kerja.
     */
    public function simpanRencanaKerja()
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
            'tahun'         => $this->request->getPost('tahun'),
            'urutan'        => $this->request->getPost('urutan'),
        ];
    
        $filePdf = $this->request->getFile('file_dokumen');
        if ($filePdf->isValid() && !$filePdf->hasMoved()) {
            // Hapus file lama jika edit
            if ($id) {
                 $dokumenLama = $this->db->table('rencana_kerja')->select('file_dokumen')->getWhere(['id' => $id])->getRow();
                 if ($dokumenLama && !empty($dokumenLama->file_dokumen) && file_exists('uploads/dokumen/' . $dokumenLama->file_dokumen)) {
                    unlink('uploads/dokumen/' . $dokumenLama->file_dokumen);
                }
            }
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/dokumen', $namaFile);
            $data['file_dokumen'] = $namaFile;
        }
    
        if ($id) {
            $this->db->table('rencana_kerja')->where('id', $id)->update($data);
        } else {
            $this->db->table('rencana_kerja')->insert($data);
        }
        
        // Sesuaikan URL redirect jika rute Anda berbeda
        return redirect()->to('/tentang-pengadilan/rencana-kerja')->with('success', 'Data Rencana Kerja berhasil disimpan!');
    }
    
    /**
     * Menghapus data Rencana Kerja.
     * @param int $id ID dokumen yang akan dihapus
     */
    public function hapusRencanaKerja($id)
    {
        $dokumen = $this->db->table('rencana_kerja')->getWhere(['id' => $id])->getRow();
        if ($dokumen) {
            if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
                unlink('uploads/dokumen/' . $dokumen->file_dokumen);
            }
            $this->db->table('rencana_kerja')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Dokumen Rencana Kerja berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

    /**
     * Menampilkan halaman manajemen Kode Etik (daftar dokumen + teks intro).
     */
    public function kodeEtikHakim()
    {
        $this->data['current_module']['judul_module'] = 'Kode Etik dan Pedoman';
        // Ambil teks pengantar
        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'kode-etik-intro'])->getRowArray();
        // Ambil daftar dokumen
        $this->data['dokumen_list'] = $this->db->table('kode_etik')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/kodeetikhakim.php', $this->data);
    }

    /**
     * Menampilkan form untuk mengedit teks pengantar Kode Etik.
     */
    public function editIntroKodeEtik()
    {
        $nama_halaman = 'kode-etik-intro';
        $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Kode Etik';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                         ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
                // Sesuaikan URL redirect jika rute Anda berbeda
                return redirect()->to('/tentang-pengadilan/kode-etik-hakim');
            } else {
                 session()->setFlashdata('errors', $this->validator->getErrors());
                 return redirect()->back()->withInput();
            }
        }

        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/edit_intro_kodeetik.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah/mengedit dokumen Kode Etik.
     * @param int|null $id ID dokumen jika dalam mode edit
     */
    public function formKodeEtik($id = null)
    {
        $this->data['dokumen'] = null;
        if ($id) {
            $this->data['dokumen'] = $this->db->table('kode_etik')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Dokumen Kode Etik';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Dokumen Kode Etik';
        }
        $this->view('tentangpengadilan/sistempengelolaanpn/form_kodeetik.php', $this->data);
    }

    /**
     * Menyimpan data dokumen Kode Etik.
     */
    public function simpanKodeEtik()
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
             if ($id) {
                 $dokumenLama = $this->db->table('kode_etik')->select('file_dokumen')->getWhere(['id' => $id])->getRow();
                 if ($dokumenLama && !empty($dokumenLama->file_dokumen) && file_exists('uploads/dokumen/' . $dokumenLama->file_dokumen)) {
                    unlink('uploads/dokumen/' . $dokumenLama->file_dokumen);
                }
            }
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/dokumen', $namaFile);
            $data['file_dokumen'] = $namaFile;
        }

        if ($id) {
            $this->db->table('kode_etik')->where('id', $id)->update($data);
        } else {
            $this->db->table('kode_etik')->insert($data);
        }
        
        // Sesuaikan URL redirect jika rute Anda berbeda
        return redirect()->to('/tentang-pengadilan/kode-etik-hakim')->with('success', 'Data Kode Etik berhasil disimpan!');
    }

    /**
     * Menghapus data dokumen Kode Etik.
     * @param int $id ID dokumen yang akan dihapus
     */
    public function hapusKodeEtik($id)
    {
        $dokumen = $this->db->table('kode_etik')->getWhere(['id' => $id])->getRow();
        if ($dokumen) {
            if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
                unlink('uploads/dokumen/' . $dokumen->file_dokumen);
            }
            $this->db->table('kode_etik')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Dokumen Kode Etik berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

}
