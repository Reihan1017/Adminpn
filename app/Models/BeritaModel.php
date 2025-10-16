<?php namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'konten_dinamis';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jenis_konten', 'judul', 'slug', 'isi_konten', 
        'gambar_cover', 'tgl_publikasi', 'id_user', 'status'
    ];

    // Digunakan untuk Data Tables Ajax: Tentukan kolom yang bisa di-search
    protected $column_search = [
        'judul', 'jenis_konten', 'tgl_publikasi'
    ]; 
    
    // Digunakan untuk Data Tables Ajax: Tentukan kolom untuk sorting
    protected $order = [
        'id' => 'desc'
    ];

    // Method untuk Data Tables Ajax (logika filtering dan sorting)
    public function datatablesQuery()
    {
        $this->builder = $this->db->table($this->table);
        $i = 0;
        
        // Logika Searching
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->builder->groupStart();
                    $this->builder->like($item, $_POST['search']['value']);
                } else {
                    $this->builder->orLike($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->builder->groupEnd();
            }
            $i++;
        }

        // Logika Sorting
        if (isset($_POST['order'])) {
            $this->builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->builder->orderBy(key($order), $order[key($order)]);
        }
    }

    public function getDatatables()
    {
        $this->datatablesQuery();
        if ($_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);
        return $this->builder->get()->getResult();
    }

    public function countFiltered()
    {
        $this->datatablesQuery();
        return $this->builder->countAllResults();
    }

    public function countAll()
    {
        return $this->db->table($this->table)->countAllResults();
    }
}