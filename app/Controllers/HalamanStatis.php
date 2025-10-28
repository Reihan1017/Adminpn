<?php

namespace App\Controllers;

use App\Models\StatisModel;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */

class HalamanStatis extends BaseController
{
    protected $statisModel;
    protected $uploadPath;

    public function __construct()
    {
        parent::__construct();
        $this->statisModel = new StatisModel();
        
        $this->uploadPath = ROOTPATH . 'public/uploads/halaman/';
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }

        $this->addJs($this->config->baseURL . 'public/vendors/tinymce/tinymce.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/halamanstatis.js?r=' . time());
    }

    // =================================================================
    // FUNGSI buildPageTree() DIHAPUS
    // =================================================================
    
    public function index()
    {
        $data = $this->data;
        $data['title'] = 'Daftar Halaman Statis';
        
        // Ganti dengan pemanggilan getAllPages tanpa Tree
        $data['halaman_list'] = $this->statisModel->getAllPages();

        if (session()->getFlashdata('message')) {
            $data['message'] = session()->getFlashdata('message');
        }

        return $this->view('halamanstatis/halamanstatis-result', $data);
    }

    public function create()
    {
        $data = $this->data;
        $data['title'] = 'Tambah Halaman Statis';
        
        // Hapus: $data['halaman_list'] = $this->statisModel->getAllPages();

        return $this->view('halamanstatis/halamanstatis-form', $data);
    }

    public function store()
    {
        // 1. Validasi Data (Tambahkan aturan jika perlu)
        // ...

        // 2. Siapkan Data
        $slug = url_title($this->request->getPost('judul'), '-', true);
        $data = [
            'judul' => $this->request->getPost('judul'),
            'slug' => $slug,
            'konten' => $this->request->getPost('konten'),
            'status' => $this->request->getPost('status'),
            'label' => $this->request->getPost('label') ?: null, // <-- FIELD BARU
            'tgl_terbit' => $this->request->getPost('tgl_terbit') ?: null,
        ];

        // 3. Handle Upload Foto
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            $fotoFile->move($this->uploadPath, $fotoName);
            $data['foto'] = $fotoName; // Simpan nama file
        }

        // 4. Simpan ke Database
        $this->statisModel->save($data);

        return redirect()->to(base_url('halamanstatis'))->with('message', ['status' => 'ok', 'message' => 'Halaman berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $halaman = $this->statisModel->getById($id);
        if (!$halaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
        $data = $this->data;
        $data['title'] = 'Edit Halaman Statis';
        $data['halaman'] = $halaman;
        
        // Hapus: Pengiriman daftar halaman untuk dropdown parent

        return $this->view('halamanstatis/halamanstatis-form', $data);
    }

    public function update($id)
    {
        // 1. Ambil data lama (untuk cek foto lama)
        // ...

        // 2. Siapkan Data
        $slug = url_title($this->request->getPost('judul'), '-', true);
        $data = [
            'judul' => $this->request->getPost('judul'),
            'slug' => $slug,
            'konten' => $this->request->getPost('konten'),
            'status' => $this->request->getPost('status'),
            'label' => $this->request->getPost('label') ?: null, // <-- FIELD BARU
            'tgl_terbit' => $this->request->getPost('tgl_terbit') ?: null,
        ];

        // 3. Handle Upload Foto (Update)
        // ... (Logika Foto Tetap Sama) ...
        $fotoFile = $this->request->getFile('foto');
        $halaman = $this->statisModel->getById($id); // Diambil dari data lama untuk logika foto/delete
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            if ($fotoFile->move($this->uploadPath, $fotoName)) {
                if (!empty($halaman['foto']) && file_exists($this->uploadPath . $halaman['foto'])) {
                    @unlink($this->uploadPath . $halaman['foto']);
                }
                $data['foto'] = $fotoName; 
            }
        }

        // 4. Update Database
        $this->statisModel->update($id, $data);

        return redirect()->to(base_url('halamanstatis/edit/' . $id))->with('message', ['status' => 'ok', 'message' => 'Halaman berhasil diperbarui']);
    }

    public function delete()
    {
        // ... (Logika Delete Tetap Sama) ...
        if ($this->request->getPost('id')) {
            $id = $this->request->getPost('id');
            $halaman = $this->statisModel->getById($id);
            if ($halaman) {
                if (!empty($halaman['foto']) && file_exists($this->uploadPath . $halaman['foto'])) {
                    @unlink($this->uploadPath . $halaman['foto']);
                }
                $this->statisModel->delete($id);
                return redirect()->to(base_url('halamanstatis'))->with('message', ['status' => 'ok', 'message' => 'Halaman berhasil dihapus']);
            }
        }
        return redirect()->to(base_url('halamanstatis'))->with('message', ['status' => 'error', 'message' => 'Gagal menghapus halaman']);
    }
}