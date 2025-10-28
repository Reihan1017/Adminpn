<?php

namespace App\Models;

use CodeIgniter\Model;

class StatisModel extends \App\Models\BaseModel
{
    protected $table = 'halaman_statis';
    protected $primaryKey = 'id';

    // --- UBAH BAGIAN INI ---
    // Hapus 'parent_id' dan 'urutan', tambahkan 'label'
    protected $allowedFields = [
        'judul',
        'slug',
        'konten',
        'status',
        'label',     // <-- BARU: Field Label
        'tgl_terbit', 
        'foto'       
    ];
    // --- AKHIR PERUBAHAN ---

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil semua halaman
    public function getAllPages($status = null)
    {
        $builder = $this;
        if ($status !== null) {
            $builder = $this->where('status', $status);
        }
        
        return $builder->orderBy('updated_at', 'DESC')
                       ->findAll();
    }



    // Ambil satu halaman berdasarkan id
    public function getById($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    // Ambil satu halaman berdasarkan slug
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    // Fungsi ini tidak terpakai di controller Anda, tapi tidak apa-apa
    public function addPage(array $data)
    {
        return $this->insert($data);
    }

    // Fungsi ini tidak terpakai di controller Anda
    public function updatePage($id, array $data)
    {
        return $this->update($id, $data);
    }

    // Fungsi ini tidak terpakai di controller Anda
    public function deletePage($id)
    {
        return $this->delete($id);
    }
}