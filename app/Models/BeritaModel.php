<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    // Nama tabel utama
    protected $table = 'berita';

    // Primary key tabel
    protected $primaryKey = 'id';

    // Mengizinkan CI4 untuk menangani insert/update
    // Ini adalah kolom-kolom yang BOLEH diisi/diubah
    protected $allowedFields = [
        'judul',
        'slug',
        'isi_berita',
        'gambar',
        'tanggal_publish',
        'status'
    ];

    // Tipe data yang dikembalikan (array) agar konsisten dengan controller Anda
    protected $returnType = 'array';

    // Kita tidak pakai auto-timestamps (created_at, updated_at)
    // karena Anda mengelola 'tanggal_publish' secara manual
    protected $useTimestamps = false;

    /**
     * Mengambil semua berita untuk halaman daftar admin.
     * (Menggantikan logic di beritaTerkini())
     */
    public function getAllBerita()
    {
        return $this->select('id, judul, slug, gambar, tanggal_publish, status')
                    ->orderBy('tanggal_publish', 'DESC')
                    ->findAll(); // findAll() adalah fungsi Model CI4
    }

    /**
     * Mengambil satu data berita BESERTA gambar tambahannya.
     * (Menggantikan logic di formBerita())
     *
     * @param int $id ID Berita
     * @return array Data 'berita' dan 'gambar_tambahan'
     */
    public function getBeritaWithGambar($id)
    {
        $data = [];
        
        // 1. Ambil data berita utama
        // find($id) adalah fungsi Model CI4 untuk get data by primary key
        $data['berita'] = $this->find($id);

        // 2. Jika berita ditemukan, cari gambar tambahannya
        if ($data['berita']) {
            $data['gambar_tambahan'] = $this->db->table('berita_gambar')
                                              ->where('berita_id', $id)
                                              ->orderBy('urutan', 'ASC')
                                              ->get()
                                              ->getResultArray();
        } else {
            $data['gambar_tambahan'] = [];
        }

        return $data;
    }

    /**
     * Menyimpan data gambar tambahan ke tabel 'berita_gambar'.
     * (Menggantikan logic di simpanBerita())
     *
     * @param array $data Data gambar (berita_id, nama_file, urutan)
     * @return bool
     */
    public function simpanGambarTambahan($data)
    {
        return $this->db->table('berita_gambar')->insert($data);
    }

    /**
     * Mengambil data berita dan gambar tambahan untuk dihapus.
     * (Dipakai di controller hapusBerita() untuk dapat nama file)
     *
     * @param int $id ID Berita
     * @return array Data 'berita' dan 'gambar_tambahan'
     */
    public function getDataUntukHapus($id)
    {
        $data = [];
        $data['berita'] = $this->find($id);
        $data['gambar_tambahan'] = [];

        if ($data['berita']) {
             $data['gambar_tambahan'] = $this->db->table('berita_gambar')
                                               ->where('berita_id', $id)
                                               ->get()
                                               ->getResultArray();
        }
        return $data;
    }

    /**
     * Menghapus berita dan semua gambar tambahan terkait (transaksional).
     * (Menggantikan logic di hapusBerita())
     *
     * @param int $id ID Berita
     * @return bool Status transaksi
     */
    public function hapusBeritaDanGambar($id)
    {
        // Mulai transaksi database
        $this->db->transStart();

        // 1. Hapus dari tabel 'berita_gambar'
        $this->db->table('berita_gambar')->where('berita_id', $id)->delete();
        
        // 2. Hapus dari tabel 'berita'
        // delete($id) adalah fungsi Model CI4 untuk hapus by primary key
        $this->delete($id);

        // Selesaikan transaksi
        $this->db->transComplete();

        // Kembalikan status sukses/gagalnya transaksi
        return $this->db->transStatus();
    }
}