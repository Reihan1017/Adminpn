<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */


class Beranda extends BaseController
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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