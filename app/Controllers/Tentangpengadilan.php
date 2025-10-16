<?php

namespace App\Controllers;

class Tentangpengadilan extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        helper(['form', 'url']);
        $this->db = \Config\Database::connect();
    }

    //--------------------------------------------------------------------
    // HALAMAN STATIS & PROFIL KETUA
    //--------------------------------------------------------------------

    public function pengantarKetua()
    {
        // ... (Kode untuk pengantar ketua yang sudah ada) ...
        $this->data['current_module']['judul_module'] = 'Pengantar Ketua Pengadilan';

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nama_ketua'    => 'required', 'jabatan_ketua' => 'required', 'isi_pengantar' => 'required',
                'foto_ketua'    => 'max_size[foto_ketua,2048]|is_image[foto_ketua]|mime_in[foto_ketua,image/jpg,image/jpeg,image/png]'
            ];
            if ($this->validate($rules)) {
                $data = [
                    'nama_ketua' => $this->request->getPost('nama_ketua'), 'jabatan_ketua' => $this->request->getPost('jabatan_ketua'),
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
                $this->data['pesan_sukses'] = "Data Pengantar Ketua berhasil diperbarui!";
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }
        $this->data['pengantar'] = $this->db->table('pengantar_ketua')->getWhere(['id' => 1])->getRowArray();
        $this->view('tentangpengadilan/pengantarketua.php', $this->data);
    }

    public function visiMisi()
    {
        // ... (Kode untuk Visi & Misi yang sudah ada) ...
        $this->data['current_module']['judul_module'] = 'Visi & Misi';
        $this->data['visi'] = $this->db->table('visi')->getWhere(['id' => 1])->getRowArray();
        $this->data['misi_list'] = $this->db->table('misi')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/visimisi.php', $this->data);
    }

    public function simpanVisiMisi()
    {
        // ... (Kode untuk simpan Visi & Misi yang sudah ada) ...
        $dataVisi = ['teks_visi' => $this->request->getPost('teks_visi')];
        $fileVisi = $this->request->getFile('gambar_visi');
        if ($fileVisi && $fileVisi->isValid() && !$fileVisi->hasMoved()) {
            $namaFileVisi = $fileVisi->getRandomName();
            $fileVisi->move('uploads/profil', $namaFileVisi);
            $dataVisi['gambar_visi'] = $namaFileVisi;
        }
        $this->db->table('visi')->where('id', 1)->update($dataVisi);
        $misi_ids = $this->request->getPost('misi_id');
        $teks_misi = $this->request->getPost('teks_misi');
        $urutan_misi = $this->request->getPost('urutan_misi');
        if (!empty($misi_ids)) {
            for ($i = 0; $i < count($misi_ids); $i++) {
                $id = $misi_ids[$i];
                $dataMisi = ['teks_misi' => $teks_misi[$i], 'urutan' => $urutan_misi[$i]];
                $fileMisi = $this->request->getFile('gambar_misi_' . $id);
                if ($fileMisi && $fileMisi->isValid() && !$fileMisi->hasMoved()) {
                    $namaFileMisi = $fileMisi->getRandomName();
                    $fileMisi->move('uploads/profil', $namaFileMisi);
                    $dataMisi['gambar_misi'] = $namaFileMisi;
                }
                $this->db->table('misi')->where('id', $id)->update($dataMisi);
            }
        }
        return redirect()->to('/tentangpengadilan/visiMisi')->with('success', 'Data Visi & Misi berhasil diperbarui!');
    }

    public function sejarah()
    {
        // ... (Kode untuk Sejarah yang sudah ada) ...
        $nama_halaman = 'sejarah';
        $this->data['current_module']['judul_module'] = 'Sejarah Pengadilan';
        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Sejarah Pengadilan berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/sejarah');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/sejarah.php', $this->data);
    }
    
    public function struktur()
    {
        // ... (Kode untuk Struktur yang sudah ada) ...
        $nama_halaman = 'struktur';
        $this->data['current_module']['judul_module'] = 'Struktur Organisasi';
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'judul_halaman' => 'required',
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
                return redirect()->to('/tentangpengadilan/struktur');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/struktur.php', $this->data);
    }

    public function wilayah()
    {
        // ... (Kode untuk Wilayah yang sudah ada) ...
        $nama_halaman = 'wilayah';
        $this->data['current_module']['judul_module'] = 'Wilayah Yuridiksi';
        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Data Wilayah Yuridiksi berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/wilayah');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/profil/wilayah.php', $this->data);
    }
    
    //--------------------------------------------------------------------
    // HALAMAN PROFIL HAKIM DAN PEGAWAI (CRUD)
    //--------------------------------------------------------------------

    public function profilHakim()
    {
        $this->data['current_module']['judul_module'] = 'Profil Hakim';
        $this->data['kategori'] = 'hakim';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'hakim')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilhakim.php', $this->data);
    }

    public function profilKepaniteraan()
    {
        $this->data['current_module']['judul_module'] = 'Profil Kepaniteraan';
        $this->data['kategori'] = 'kepaniteraan';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'kepaniteraan')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilkepaniteraan.php', $this->data);
    }

    public function profilKesekretariatan()
    {
        $this->data['current_module']['judul_module'] = 'Profil Kesekretariatan';
        $this->data['kategori'] = 'kesekretariatan';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'kesekretariatan')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilkesekretariatan.php', $this->data);
    }

    public function profilPppk()
    {
        $this->data['current_module']['judul_module'] = 'Profil PPPK';
        $this->data['kategori'] = 'pppk';
        $this->data['profil_list'] = $this->db->table('pegawai')->where('kategori', 'pppk')->orderBy('urutan', 'ASC')->get()->getResultArray();
        $this->view('tentangpengadilan/hakimdanpegawai/profilpppk.php', $this->data);
    }

    public function formProfil($kategori, $id = null)
    {
        $this->data['profil'] = null;
        $this->data['kategori'] = $kategori;

        if ($id) {
            $this->data['profil'] = $this->db->table('pegawai')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Profil';
        } else {
            $this->data['current_module']['judul_module'] = 'Tambah Profil Baru';
        }
        $this->view('tentangpengadilan/hakimdanpegawai/form_profil.php', $this->data);
    }

    public function simpanProfil()
    {
        $id = $this->request->getPost('id');
        $kategori = $this->request->getPost('kategori');

        $rules = [
            'nama_pegawai' => 'required', 'jabatan' => 'required',
            'foto_pegawai' => 'max_size[foto_pegawai,2048]|is_image[foto_pegawai]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_pegawai' => $this->request->getPost('nama_pegawai'), 'nip' => $this->request->getPost('nip'),
            'pangkat_gol' => $this->request->getPost('pangkat_gol'), 'jabatan' => $this->request->getPost('jabatan'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'), 'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'urutan' => $this->request->getPost('urutan'), 'kategori' => $kategori
        ];
        $fileFoto = $this->request->getFile('foto_pegawai');
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/pegawai', $namaFoto);
            $data['foto_pegawai'] = $namaFoto;
        }
        if ($id) {
            $this->db->table('pegawai')->where('id', $id)->update($data);
        } else {
            $this->db->table('pegawai')->insert($data);
        }
        $redirect_url = 'tentangpengadilan/profil' . ucfirst($kategori);
        return redirect()->to($redirect_url)->with('success', 'Data profil berhasil disimpan!');
    }

    public function hapusProfil($id)
    {
        $profil = $this->db->table('pegawai')->getWhere(['id' => $id])->getRow();
        if ($profil) {
            if (!empty($profil->foto_pegawai) && file_exists('uploads/pegawai/' . $profil->foto_pegawai)) {
                unlink('uploads/pegawai/' . $profil->foto_pegawai);
            }
            $this->db->table('pegawai')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Profil berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Profil tidak ditemukan!');
    }

    public function roleModel()
    {
        $this->data['current_module']['judul_module'] = 'Profil Role Model';

        if ($this->request->getMethod() === 'post') {
            
            $rules = [
                'nama' => 'required',
                'jabatan' => 'required',
                'poster_image' => 'max_size[poster_image,3072]|is_image[poster_image]' // Max 3MB
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

                // Proses upload gambar poster jika ada file baru
                $filePoster = $this->request->getFile('poster_image');
                if ($filePoster && $filePoster->isValid() && !$filePoster->hasMoved()) {
                    
                    // Hapus gambar lama
                    $oldData = $this->db->table('role_model')->getWhere(['id' => 1])->getRow();
                    if ($oldData && !empty($oldData->poster_image) && file_exists('uploads/profil/' . $oldData->poster_image)) {
                        unlink('uploads/profil/' . $oldData->poster_image);
                    }

                    // Simpan gambar baru
                    $namaGambar = $filePoster->getRandomName();
                    $filePoster->move('uploads/profil', $namaGambar);
                    $dataUpdate['poster_image'] = $namaGambar;
                }

                // Update data di database
                $this->db->table('role_model')->where('id', 1)->update($dataUpdate);
                
                session()->setFlashdata('success', 'Data Profil Role Model berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/roleModel');

            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        // Ambil data terbaru dari database
        $this->data['rolemodel'] = $this->db->table('role_model')->getWhere(['id' => 1])->getRowArray();

        // Tampilkan view sesuai struktur folder Anda
        $this->view('tentangpengadilan/profilperubahan/rolemodel.php', $this->data);
    }


    public function profilPerubahan()
    {
        $this->data['current_module']['judul_module'] = 'Agen Perubahan';

        // Ambil semua data agen, diurutkan berdasarkan 'urutan'
        $builder = $this->db->table('agen_perubahan');
        $this->data['agen_list'] = $builder->orderBy('urutan', 'ASC')->get()->getResultArray();
        
        // Muat view untuk menampilkan daftar
        $this->view('tentangpengadilan/profilperubahan/profilperubahan.php', $this->data);
    }

    /**
     * Menampilkan form untuk menambah atau mengedit Agen Perubahan.
     */
    public function formAgenPerubahan($id = null)
    {
        $this->data['agen'] = null;
        if ($id) {
            // Mode Edit: ambil data agen spesifik
            $this->data['agen'] = $this->db->table('agen_perubahan')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Agen Perubahan';
        } else {
            // Mode Tambah
            $this->data['current_module']['judul_module'] = 'Tambah Agen Perubahan';
        }

        // Muat view form
        $this->view('tentangpengadilan/profilperubahan/form_agen_perubahan.php', $this->data);
    }

    /**
     * Menyimpan data Agen Perubahan yang baru atau yang diedit.
     */
    public function simpanAgenPerubahan()
    {
        $id = $this->request->getPost('id');

        // Aturan validasi
        $rules = [
            'poster_image' => 'uploaded[poster_image]|max_size[poster_image,3072]|is_image[poster_image]'
        ];
        // Jika sedang mengedit, gambar tidak wajib diisi ulang
        if ($id) {
            $rules['poster_image'] = 'max_size[poster_image,3072]|is_image[poster_image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'    => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'urutan'  => $this->request->getPost('urutan'),
        ];

        // Proses upload gambar poster
        $filePoster = $this->request->getFile('poster_image');
        if ($filePoster->isValid() && !$filePoster->hasMoved()) {
            $namaGambar = $filePoster->getRandomName();
            $filePoster->move('uploads/profil', $namaGambar); // Pastikan folder 'public/uploads/profil' ada
            $data['poster_image'] = $namaGambar;
        }

        // Simpan ke database
        if ($id) {
            $this->db->table('agen_perubahan')->where('id', $id)->update($data);
        } else {
            $this->db->table('agen_perubahan')->insert($data);
        }
        
        return redirect()->to('/tentangpengadilan/profilPerubahan')->with('success', 'Data Agen Perubahan berhasil disimpan!');
    }

    /**
     * Menghapus data Agen Perubahan.
     */
    public function hapusAgenPerubahan($id)
    {
        $agen = $this->db->table('agen_perubahan')->getWhere(['id' => $id])->getRow();
        if ($agen) {
            // Hapus file gambar dari server
            if (!empty($agen->poster_image) && file_exists('uploads/profil/' . $agen->poster_image)) {
                unlink('uploads/profil/' . $agen->poster_image);
            }
            $this->db->table('agen_perubahan')->where('id', $id)->delete();
            
            return redirect()->back()->with('success', 'Profil Agen Perubahan berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

    // Tambahkan method ini di dalam class Tentangpengadilan

    public function kepaniteraanPidana()
    {
        // Identifier unik untuk halaman ini di database
        $nama_halaman = 'kepaniteraan-pidana';
        
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Pidana';

        // Proses penyimpanan data jika form disubmit
        if ($this->request->getMethod() === 'post') {
            
            $rules = [ 'isi_halaman' => 'required' ];

            if ($this->validate($rules)) {
                $dataUpdate = [
                    'isi_halaman' => $this->request->getPost('isi_halaman')
                ];

                // Update baris di database dimana 'nama_halaman' sesuai
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                
                session()->setFlashdata('success', 'Data Kepaniteraan Pidana berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/kepaniteraanPidana');

            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        // Ambil data terbaru dari database
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

        // Tampilkan view sesuai struktur folder Anda
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanpidana.php', $this->data);
    }

    public function kepaniteraanPerdata()
    {
        // Identifier unik untuk halaman ini
        $nama_halaman = 'kepaniteraan-perdata';
        
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Perdata';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate([ 'isi_halaman' => 'required' ])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                        ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                
                session()->setFlashdata('success', 'Data Kepaniteraan Perdata berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/kepaniteraanPerdata');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

        // Arahkan ke view yang sesuai
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanperdata.php', $this->data);
    }

    public function kepaniteraanHukum()
    {
        // Identifier unik untuk halaman ini
        $nama_halaman = 'kepaniteraan-hukum';
        
        $this->data['current_module']['judul_module'] = 'Kepaniteraan Hukum';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate([ 'isi_halaman' => 'required' ])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)
                        ->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                
                session()->setFlashdata('success', 'Data Kepaniteraan Hukum berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/kepaniteraanHukum');
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();

        // Arahkan ke view yang sesuai
        $this->view('tentangpengadilan/kepaniteraan/kepaniteraanhukum.php', $this->data);
    }

    public function eLearning()
    {
        $this->data['current_module']['judul_module'] = 'E-learning';
        $this->view('tentangpengadilan/sistempengelolaanpn/elearning.php', $this->data);
    }

    public function jdihPnCiamis()
    {
        // Kunci unik untuk pengaturan ini di database
        $nama_pengaturan = 'url_jdih';
        
        $this->data['current_module']['judul_module'] = 'Pengaturan Link JDIH';

        // Proses penyimpanan jika form disubmit
        if ($this->request->getMethod() === 'post') {
            
            // Aturan validasi: wajib diisi dan harus format URL yang valid
            $rules = [ 'url' => 'required|valid_url_strict' ];

            if ($this->validate($rules)) {
                $dataUpdate = [
                    'nilai_pengaturan' => $this->request->getPost('url')
                ];

                // Update baris di database
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', $nama_pengaturan)->update($dataUpdate);
                
                session()->setFlashdata('success', 'URL Link JDIH berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/jdihPnCiamis');

            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        // Ambil data terbaru dari database
        $this->data['pengaturan'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => $nama_pengaturan])->getRowArray();

        // Tampilkan view
        $this->view('tentangpengadilan/sistempengelolaanpn/jdihpnciamis.php', $this->data);
    }

    public function kebijakan()
    {
        // Kunci unik untuk pengaturan ini di database
        $nama_pengaturan = 'url_kebijakan';
        
        $this->data['current_module']['judul_module'] = 'Pengaturan Link Kebijakan';

        // Proses penyimpanan jika form disubmit
        if ($this->request->getMethod() === 'post') {
            
            $rules = [ 'url' => 'required|valid_url_strict' ];

            if ($this->validate($rules)) {
                $dataUpdate = [
                    'nilai_pengaturan' => $this->request->getPost('url')
                ];

                // Update baris di database
                $this->db->table('pengaturan_situs')->where('nama_pengaturan', $nama_pengaturan)->update($dataUpdate);
                
                session()->setFlashdata('success', 'URL Link Kebijakan berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/kebijakan');

            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        // Ambil data terbaru dari database
        $this->data['pengaturan'] = $this->db->table('pengaturan_situs')->getWhere(['nama_pengaturan' => $nama_pengaturan])->getRowArray();

        // Tampilkan view
        $this->view('tentangpengadilan/sistempengelolaanpn/kebijakan.php', $this->data);
    }   

    public function rencanaStrategis()
    {
        // Identifier unik untuk halaman ini
        $nama_halaman = 'rencana-strategis';
        
        $this->data['current_module']['judul_module'] = 'Rencana Strategis';
    
        if ($this->request->getMethod() === 'post') {
            
            // Aturan validasi: Wajib file PDF, ukuran maksimal 10MB
            $rules = [
                'file_pdf' => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]'
            ];
    
            if ($this->validate($rules)) {
                $dataUpdate = [
                    'judul_halaman' => $this->request->getPost('judul_halaman')
                ];
    
                // Proses upload file PDF jika ada yang diunggah
                $filePdf = $this->request->getFile('file_pdf');
                if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
                    
                    // Hapus file lama dari server
                    $oldData = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRow();
                    if ($oldData && !empty($oldData->gambar_halaman) && file_exists('uploads/dokumen/' . $oldData->gambar_halaman)) {
                        unlink('uploads/dokumen/' . $oldData->gambar_halaman);
                    }
    
                    // Simpan file baru ke folder 'public/uploads/dokumen'
                    $namaFile = $filePdf->getRandomName();
                    $filePdf->move('uploads/dokumen', $namaFile); // Pastikan folder ini ada
                    $dataUpdate['gambar_halaman'] = $namaFile; // Nama file disimpan di kolom 'gambar_halaman'
                }
    
                // Update data di database
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update($dataUpdate);
                
                session()->setFlashdata('success', 'File Rencana Strategis berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/rencanaStrategis');
    
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }
    
        // Ambil data terbaru dari database
        $this->data['halaman'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
    
        // Tampilkan view
        $this->view('tentangpengadilan/sistempengelolaanpn/rencanastrategis.php', $this->data);
    }

    public function rencanaKerja()
    {
        $this->data['current_module']['judul_module'] = 'Rencana Kerja dan Anggaran';
    
        // Ambil semua data, diurutkan berdasarkan tahun dan urutan
        $builder = $this->db->table('rencana_kerja');
        $this->data['dokumen_list'] = $builder->orderBy('tahun', 'DESC')->orderBy('urutan', 'ASC')->get()->getResultArray();
        
        $this->view('tentangpengadilan/sistempengelolaanpn/rencanakerja.php', $this->data);
    }
    
    /**
     * Menampilkan form untuk menambah/mengedit dokumen Rencana Kerja.
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
    
        $rules = [
            'judul_dokumen' => 'required',
            'file_dokumen' => 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]' // PDF, max 10MB
        ];
        // File wajib diisi hanya saat menambah data baru
        if (!$id) {
            $rules['file_dokumen'] = 'uploaded[file_dokumen]|' . $rules['file_dokumen'];
        }
    
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
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/dokumen', $namaFile);
            $data['file_dokumen'] = $namaFile;
        }
    
        if ($id) {
            $this->db->table('rencana_kerja')->where('id', $id)->update($data);
        } else {
            $this->db->table('rencana_kerja')->insert($data);
        }
        
        return redirect()->to('/tentangpengadilan/rencanaKerja')->with('success', 'Data Rencana Kerja berhasil disimpan!');
    }
    
    /**
     * Menghapus data Rencana Kerja.
     */
    public function hapusRencanaKerja($id)
    {
        $dokumen = $this->db->table('rencana_kerja')->getWhere(['id' => $id])->getRow();
        if ($dokumen) {
            // Hapus file dari server
            if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
                unlink('uploads/dokumen/' . $dokumen->file_dokumen);
            }
            $this->db->table('rencana_kerja')->where('id', $id)->delete();
            
            return redirect()->back()->with('success', 'Dokumen Rencana Kerja berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }
    /**
     * Displays the list of Code of Ethics documents.
     */
        public function kodeEtikHakim()
    {
        $this->data['current_module']['judul_module'] = 'Kode Etik dan Pedoman';

        // BARU: Ambil data teks pengantar dari tabel halaman_profil
        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => 'kode-etik-intro'])->getRowArray();

        // SUDAH ADA: Ambil daftar dokumen dari tabel kode_etik
        $this->data['dokumen_list'] = $this->db->table('kode_etik')->orderBy('urutan', 'ASC')->get()->getResultArray();
        
        $this->view('tentangpengadilan/sistempengelolaanpn/kodeetikhakim.php', $this->data);
    }

    /**
     * Displays the form to add or edit a Code of Ethics document.
     */
    public function formKodeEtik($id = null)
    {
        $this->data['dokumen'] = null;
        if ($id) {
            // Edit mode
            $this->data['dokumen'] = $this->db->table('kode_etik')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Dokumen Kode Etik';
        } else {
            // Add mode
            $this->data['current_module']['judul_module'] = 'Tambah Dokumen Kode Etik';
        }

        $this->view('tentangpengadilan/sistempengelolaanpn/form_kodeetik.php', $this->data);
    }

    /**
     * Saves a new or edited Code of Ethics document.
     */
    public function simpanKodeEtik()
    {
        $id = $this->request->getPost('id');

        $rules = [
            'judul_dokumen' => 'required',
            'file_dokumen' => 'max_size[file_dokumen,10240]|ext_in[file_dokumen,pdf]' // PDF, max 10MB
        ];
        // File is required only when adding a new entry
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
            $filePdf->move('uploads/dokumen', $namaFile); // Ensure 'public/uploads/dokumen' exists
            $data['file_dokumen'] = $namaFile;
        }

        if ($id) {
            $this->db->table('kode_etik')->where('id', $id)->update($data);
        } else {
            $this->db->table('kode_etik')->insert($data);
        }
        
        return redirect()->to('/tentangpengadilan/kodeEtikHakim')->with('success', 'Data Kode Etik berhasil disimpan!');
    }

    /**
     * Deletes a Code of Ethics document.
     */
    public function hapusKodeEtik($id)
    {
        $dokumen = $this->db->table('kode_etik')->getWhere(['id' => $id])->getRow();
        if ($dokumen) {
            // Delete the file from the server
            if (!empty($dokumen->file_dokumen) && file_exists('uploads/dokumen/' . $dokumen->file_dokumen)) {
                unlink('uploads/dokumen/' . $dokumen->file_dokumen);
            }
            $this->db->table('kode_etik')->where('id', $id)->delete();
            
            return redirect()->back()->with('success', 'Dokumen Kode Etik berhasil dihapus!');
        }
        return redirect()->back()->with('errors', 'Data tidak ditemukan!');
    }

        public function editIntroKodeEtik()
    {
        $nama_halaman = 'kode-etik-intro';
        $this->data['current_module']['judul_module'] = 'Edit Teks Pengantar Kode Etik';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate(['isi_halaman' => 'required'])) {
                $this->db->table('halaman_profil')->where('nama_halaman', $nama_halaman)->update(['isi_halaman' => $this->request->getPost('isi_halaman')]);
                session()->setFlashdata('success', 'Teks pengantar berhasil diperbarui!');
                return redirect()->to('/tentangpengadilan/kodeEtikHakim'); // Kembali ke halaman daftar
            } else {
                $this->data['pesan_gagal'] = $this->validator->getErrors();
            }
        }

        $this->data['intro'] = $this->db->table('halaman_profil')->getWhere(['nama_halaman' => $nama_halaman])->getRowArray();
        $this->view('tentangpengadilan/sistempengelolaanpn/edit_intro_kodeetik.php', $this->data);
    }

}
