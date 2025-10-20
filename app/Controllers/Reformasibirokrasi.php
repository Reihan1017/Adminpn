<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */

class Reformasibirokrasi extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------
    // FOLDER: akreditasipenjaminanmutu
    //--------------------------------------------------------------------

    public function manualMutu()
    {
        $this->data['current_module']['judul_module'] = 'Manual Mutu';
        $this->view('reformasibirokrasi/akreditasipenjaminanmutu/manualmutu.php', $this->data);
    }

    public function penilaianBadilum()
    {
        $this->data['current_module']['judul_module'] = 'Penilaian Badilum';
        $this->view('reformasibirokrasi/akreditasipenjaminanmutu/penilaianbadilum.php', $this->data);
    }

    public function sertifikatAkreditasi()
    {
        $this->data['current_module']['judul_module'] = 'Sertifikat Akreditasi';
        $this->view('reformasibirokrasi/akreditasipenjaminanmutu/sertifakre.php', $this->data);
    }
    
    public function timPengadilan()
    {
        $this->data['current_module']['judul_module'] = 'Tim Pengadilan';
        $this->view('reformasibirokrasi/akreditasipenjaminanmutu/timpengadilan.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: zonaintegritas
    //--------------------------------------------------------------------

    public function zonaIntegritasAreaSatu()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 1';
        $this->view('reformasibirokrasi/zonaintegritas/areasatu.php', $this->data);
    }
    
    public function zonaIntegritasAreaDua()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 2';
        $this->view('reformasibirokrasi/zonaintegritas/areadua.php', $this->data);
    }
    
    public function zonaIntegritasAreaTiga()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 3';
        $this->view('reformasibirokrasi/zonaintegritas/areatiga.php', $this->data);
    }
    
    public function zonaIntegritasAreaEmpat()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 4';
        $this->view('reformasibirokrasi/zonaintegritas/areaempat.php', $this->data);
    }
    
    public function zonaIntegritasAreaLima()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 5';
        $this->view('reformasibirokrasi/zonaintegritas/arealima.php', $this->data);
    }
    
    public function zonaIntegritasAreaEnam()
    {
        $this->data['current_module']['judul_module'] = 'Zona Integritas - Area 6';
        $this->view('reformasibirokrasi/zonaintegritas/areaenam.php', $this->data);
    }
}