<?php
namespace App\Models;

use CodeIgniter\Model;

class SliderModel extends Model
{
    protected $table            = 'slider';
    protected $primaryKey       = 'id_slider';
    protected $allowedFields    = ['judul', 'link', 'status', 'gambar'];
    protected $useTimestamps    = true; // ubah jadi true kalau tabel punya kolom created_at & updated_at

    /**
     * Ambil semua slider
     */
    public function getSlider()
    {
        return $this->orderBy('id_slider', 'DESC')->findAll();
    }

    /**
     * Ambil slider berdasarkan ID
     */
    public function getSliderById($id)
    {
        return $this->find($id);
    }

    /**
     * Simpan slider (insert / update)
     */
    public function saveSlider($data, $id = null)
    {
        if ($id) {
            $this->update($id, $data);
            return ['status' => 'ok', 'message' => 'Slider berhasil diperbarui'];
        } else {
            $this->insert($data);
            return ['status' => 'ok', 'message' => 'Slider berhasil ditambahkan'];
        }
    }

    /**
     * Hapus slider
     */
    public function deleteSlider($id)
    {
        $this->delete($id);
        return ['status' => 'ok', 'message' => 'Slider berhasil dihapus'];
    }
}
