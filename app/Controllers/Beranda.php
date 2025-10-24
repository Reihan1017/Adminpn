<?php

namespace App\Controllers;

// (use statement lain jika ada)
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 * @property \CodeIgniter\Database\BaseConnection $db
 */
class Beranda extends BaseController // Pastikan extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct(); // PENTING: Panggil constructor parent
        helper(['form', 'url', 'text']);
        if (!isset($this->db)) {
            $this->db = \Config\Database::connect();
        }
    }

    //--------------------------------------------------------------------
    // HALAMAN UTAMA TENTANG PENGADILAN
    //--------------------------------------------------------------------

    public function Beranda()
    {
        $this->data['current_module']['judul_module'] = 'Beranda Pengadilan';
        $this->view('beranda/beranda.php', $this->data);
    }

}
