<?php

namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Models\PengumumanModel; 
use App\Models\BeritaModel; 

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 * @property \CodeIgniter\Database\BaseConnection $db
 * @property \App\Models\BeritaModel $beritaModel // TAMBAHKAN: Untuk intellisense
 */
class Pengumuman extends BaseController
{

    protected $pengumumanModel;
    protected $uploadPathGambar;
    protected $uploadPathPdf;
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
        $this->pengumumanModel = new PengumumanModel();
        
        // Buat path upload
        $this->uploadPathGambar = ROOTPATH . 'public/uploads/pengumuman/gambar/';
        $this->uploadPathPdf = ROOTPATH . 'public/uploads/pengumuman/pdf/';
        
        // Buat direktori jika belum ada
        if (!is_dir($this->uploadPathGambar)) {
            mkdir($this->uploadPathGambar, 0777, true);
        }
        if (!is_dir($this->uploadPathPdf)) {
            mkdir($this->uploadPathPdf, 0777, true);
        }

        // JS untuk editor teks (Tidak berubah)
        $this->addJs($this->config->baseURL . 'public/vendors/tinymce/tinymce.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/halamanstatis.js?r=' . time());
    }

    public function index()
    {
        $data = $this->data;
        $data['title'] = 'Daftar Pengumuman';
        // 5. Ganti pemanggilan data
        $data['pengumuman'] = $this->pengumumanModel->getAllPengumuman(); 

        if (session()->getFlashdata('message')) {
            $data['message'] = session()->getFlashdata('message');
        }

        // 6. Ganti path view
        return $this->view('pengumuman/pengumuman-result', $data);
    }

    public function create()
    {
        $data = $this->data;
        $data['title'] = 'Tambah Pengumuman';
        
        // 7. Hapus logika 'parent_id'
        // $data['halaman_list'] sudah dihapus

        // 8. Ganti path view
        return $this->view('pengumuman/pengumuman-form', $data);
    }

    public function store()
    {
        $slug = url_title($this->request->getPost('judul'), '-', true);
        $data = [
            'judul' => $this->request->getPost('judul'),
            'slug' => $slug,
            'konten' => $this->request->getPost('konten'),
            'status' => $this->request->getPost('status') ?: 'publish',
            // 'parent_id' dan 'urutan' dihapus
            // 'tgl_terbit' dihapus (kita pakai created_at)
        ];

        // 9. Handle Upload Gambar
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            $gambarName = $gambarFile->getRandomName();
            $gambarFile->move($this->uploadPathGambar, $gambarName);
            $data['gambar'] = $gambarName;
        }
        
        // 10. Handle Upload PDF
        $pdfFile = $this->request->getFile('file_pdf');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $pdfName = $pdfFile->getRandomName(); // Boleh juga pakai nama asli: $pdfFile->getName()
            $pdfFile->move($this->uploadPathPdf, $pdfName);
            $data['file_pdf'] = $pdfName;
        }

        // 11. Simpan ke Database
        $this->pengumumanModel->save($data);

        return redirect()->to(base_url('pengumuman'))->with('message', ['status' => 'ok', 'message' => 'Pengumuman berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $data = $this->data;
        $pengumuman = $this->pengumumanModel->getById($id);
        if (!$pengumuman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengumuman tidak ditemukan');
        }
        
        $data['title'] = 'Edit Pengumuman';
        $data['pengumuman'] = $pengumuman;
        
        // Hapus logika 'parent_id'
        
        // 12. Ganti path view
        return $this->view('pengumuman/pengumuman-form', $data);
    }

    public function update($id)
    {
        $pengumuman = $this->pengumumanModel->getById($id);
        if (!$pengumuman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengumuman tidak ditemukan');
        }

        $slug = url_title($this->request->getPost('judul'), '-', true);
        $data = [
            'judul' => $this->request->getPost('judul'),
            'slug' => $slug,
            'konten' => $this->request->getPost('konten'),
            'status' => $this->request->getPost('status') ?: 'publish',
        ];

        // 13. Handle Update Gambar
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            $gambarName = $gambarFile->getRandomName();
            if ($gambarFile->move($this->uploadPathGambar, $gambarName)) {
                // Hapus gambar lama
                if (!empty($pengumuman['gambar']) && file_exists($this->uploadPathGambar . $pengumuman['gambar'])) {
                    @unlink($this->uploadPathGambar . $pengumuman['gambar']);
                }
                $data['gambar'] = $gambarName;
            }
        }
        
        // 14. Handle Update PDF
        $pdfFile = $this->request->getFile('file_pdf');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $pdfName = $pdfFile->getRandomName();
            if ($pdfFile->move($this->uploadPathPdf, $pdfName)) {
                // Hapus file pdf lama
                if (!empty($pengumuman['file_pdf']) && file_exists($this->uploadPathPdf . $pengumuman['file_pdf'])) {
                    @unlink($this->uploadPathPdf . $pengumuman['file_pdf']);
                }
                $data['file_pdf'] = $pdfName;
            }
        }

        $this->pengumumanModel->update($id, $data);

        return redirect()->to(base_url('pengumuman/edit/' . $id))->with('message', ['status' => 'ok', 'message' => 'Pengumuman berhasil diperbarui']);
    }

    public function delete()
    {
        if ($this->request->getPost('id')) {
            $id = $this->request->getPost('id');
            $pengumuman = $this->pengumumanModel->getById($id);
            
            if ($pengumuman) {
                // 15. Hapus file-file
                if (!empty($pengumuman['gambar']) && file_exists($this->uploadPathGambar . $pengumuman['gambar'])) {
                    @unlink($this->uploadPathGambar . $pengumuman['gambar']);
                }
                if (!empty($pengumuman['file_pdf']) && file_exists($this->uploadPathPdf . $pengumuman['file_pdf'])) {
                    @unlink($this->uploadPathPdf . $pengumuman['file_pdf']);
                }
                
                $this->pengumumanModel->delete($id);
                return redirect()->to(base_url('pengumuman'))->with('message', ['status' => 'ok', 'message' => 'Pengumuman berhasil dihapus']);
            }
        }
        return redirect()->to(base_url('pengumuman'))->with('message', ['status' => 'error', 'message' => 'Gagal menghapus pengumuman']);
    }
}