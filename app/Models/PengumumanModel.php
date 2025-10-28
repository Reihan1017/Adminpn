<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends \App\Models\BaseModel
{
    // 1. Ganti nama tabel
    protected $table = 'pengumuman'; 
    protected $primaryKey = 'id';

    // 2. Sesuaikan kolom
    protected $allowedFields = [
        'judul',
        'slug',
        'konten',
        'status',
        'gambar',   // <-- TAMBAHAN
        'file_pdf'  // <-- TAMBAHAN
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 3. Fungsi ambil data (disederhanakan)
    public function getAllPengumuman()
    {
        // Urutkan berdasarkan yang terbaru
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    // Ambil satu data berdasarkan id
    public function getById($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    // Ambil satu data berdasarkan slug
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }
}