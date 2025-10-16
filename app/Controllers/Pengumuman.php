<?php

namespace App\Controllers;

class Pengumuman extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        // Memuat helper dan koneksi database
        helper('form');
        $this->db = \Config\Database::connect();
    }

    //--------------------------------------------------------------------
    // FOLDER: berita
    //--------------------------------------------------------------------

    public function pengumuman()
    {
        $this->data['current_module']['judul_module'] = 'Pengumuman';

        // Ambil semua data pengumuman dari DB, urutkan dari yang terbaru
        $builder = $this->db->table('pengumuman');
        $this->data['pengumuman_list'] = $builder->orderBy('tanggal_publish', 'DESC')->get()->getResultArray();
        
        // Tampilkan view daftar pengumuman
        $this->view('berita/pengumuman.php', $this->data);
    }

    /**
     * Menampilkan form untuk tambah atau edit pengumuman.
     */
    public function formPengumuman($id = null)
    {
        $this->data['pengumuman'] = null;
        if ($id) {
            // Mode EDIT: Ambil data spesifik dari DB
            $this->data['pengumuman'] = $this->db->table('pengumuman')->getWhere(['id' => $id])->getRowArray();
            $this->data['current_module']['judul_module'] = 'Edit Pengumuman';
        } else {
            // Mode TAMBAH
            $this->data['current_module']['judul_module'] = 'Tambah Pengumuman Baru';
        }

        // Tampilkan view yang berisi form
        $this->view('berita/form_pengumuman.php', $this->data);
    }

    /**
     * Menyimpan data pengumuman (baru atau editan).
     */
    public function simpanPengumuman()
    {
        $id = $this->request->getPost('id');

        // Aturan Validasi
        $rules = [
            'judul' => 'required|min_length[5]',
            'deskripsi' => 'required',
            'file_pengumuman' => 'max_size[file_pengumuman,5120]|ext_in[file_pengumuman,png,jpg,jpeg,pdf]' // Max 5MB, format gambar/pdf
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data
        $data = [
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal_publish' => $this->request->getPost('tanggal_publish')
        ];

        // Proses Upload File
        $file = $this->request->getFile('file_pengumuman');
        if ($file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/pengumuman', $namaFile); // Pastikan folder 'public/uploads/pengumuman' ada
            $data['file_pengumuman'] = $namaFile;
        }

        // Simpan ke DB
        if ($id) {
            $this->db->table('pengumuman')->where('id', $id)->update($data);
        } else {
            $this->db->table('pengumuman')->insert($data);
        }
        
        return redirect()->to('/pengumuman/pengumuman')->with('success', 'Pengumuman berhasil disimpan!');
    }

    /**
     * Menghapus data pengumuman.
     */
    public function hapusPengumuman($id)
    {
        // Hapus file dari server
        $pengumuman = $this->db->table('pengumuman')->getWhere(['id' => $id])->getRow();
        if ($pengumuman && file_exists('uploads/pengumuman/' . $pengumuman->file_pengumuman)) {
            unlink('uploads/pengumuman/' . $pengumuman->file_pengumuman);
        }

        // Hapus data dari DB
        $this->db->table('pengumuman')->where('id', $id)->delete();
        return redirect()->to('/pengumuman/pengumuman')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
