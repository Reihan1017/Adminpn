<?php

namespace App\Controllers;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request
 */

class Layananhukum extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------
    // HALAMAN UTAMA LAYANAN HUKUM
    //--------------------------------------------------------------------
    public function index()
    {
        $this->data['current_module']['judul_module'] = 'Layanan Hukum';
        $this->view('layananhukum/index.php', $this->data); // Asumsi ada file index.php
    }

    //--------------------------------------------------------------------
    // FOLDER: ecourt
    //--------------------------------------------------------------------
    public function ecourt()
    {
        $this->data['current_module']['judul_module'] = 'E-Court';
        $this->view('layananhukum/ecourt/ecourt.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: gugatansederhana
    //--------------------------------------------------------------------
    public function gugatanSederhana()
    {
        $this->data['current_module']['judul_module'] = 'Gugatan Sederhana';
        $this->view('layananhukum/gugatansederhana/gugatansederhana.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: layanantdkwaragtdkmampu
    //--------------------------------------------------------------------
    public function peraturanDanKebijakan()
    {
        $this->data['current_module']['judul_module'] = 'Peraturan dan Kebijakan';
        $this->view('layananhukum/layanantdkwaragtdkmampu/peraturandankebijakan.php', $this->data);
    }

    public function posBantuanHukum()
    {
        $this->data['current_module']['judul_module'] = 'Pos Bantuan Hukum (Posbakum)';
        $this->view('layananhukum/layanantdkwaragtdkmampu/posbakum.php', $this->data);
    }

    public function prodeo()
    {
        $this->data['current_module']['judul_module'] = 'Prodeo';
        $this->view('layananhukum/layanantdkwaragtdkmampu/prodeo.php', $this->data);
    }
    
    //--------------------------------------------------------------------
    // FOLDER: pelayanandisabilitas
    //--------------------------------------------------------------------
    public function plyndisabilitas()
    {
        $this->data['current_module']['judul_module'] = 'Pelayanan Disabilitas';
        $this->view('layananhukum/pelayanandisabilitas/plyndisabilitas.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: perkarapelanggaranlalulintas
    //--------------------------------------------------------------------
    public function laluLintas()
    {
        $this->data['current_module']['judul_module'] = 'Perkara Pelanggaran Lalu Lintas';
        $this->view('layananhukum/perkarapelanggaranlalulintas/lalulintas.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: prosedurmediasi
    //--------------------------------------------------------------------
    public function mediasi()
    {
        $this->data['current_module']['judul_module'] = 'Prosedur Mediasi';
        $this->view('layananhukum/prosedurmediasi/mediasi.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: prosedurpengajuan / perdata
    //--------------------------------------------------------------------
    public function eksekusiGrosseAkta()
    {
        $this->data['current_module']['judul_module'] = 'Eksekusi Grosse Akta';
        $this->view('layananhukum/prosedurpengajuan/perdata/eksekusigrosseakta.php', $this->data);
    }

    public function eksekusiHakTanggungan()
    {
        $this->data['current_module']['judul_module'] = 'Eksekusi Hak Tanggungan';
        $this->view('layananhukum/prosedurpengajuan/perdata/eksekusihaktanggungan.php', $this->data);
    }
    
    public function eksekusiJaminan()
    {
        $this->data['current_module']['judul_module'] = 'Eksekusi Jaminan';
        $this->view('layananhukum/prosedurpengajuan/perdata/eksekusijaminan.php', $this->data);
    }

    public function eksekusiPutusan()
    {
        $this->data['current_module']['judul_module'] = 'Eksekusi Putusan';
        $this->view('layananhukum/prosedurpengajuan/perdata/eksekusiputusan.php', $this->data);
    }

    public function gugatanKelompok()
    {
        $this->data['current_module']['judul_module'] = 'Gugatan Kelompok';
        $this->view('layananhukum/prosedurpengajuan/perdata/gugatankelompok.php', $this->data);
    }
    
    public function gugatanKepentinganUmum()
    {
        $this->data['current_module']['judul_module'] = 'Gugatan Kepentingan Umum';
        $this->view('layananhukum/prosedurpengajuan/perdata/gugatankepentinganumum.php', $this->data);
    }

    public function sitaEksekusi()
    {
        $this->data['current_module']['judul_module'] = 'Sita Eksekusi';
        $this->view('layananhukum/prosedurpengajuan/perdata/sitaeksekusi.php', $this->data);
    }

    public function sitaJaminan()
    {
        $this->data['current_module']['judul_module'] = 'Sita Jaminan';
        $this->view('layananhukum/prosedurpengajuan/perdata/sitajaminan.php', $this->data);
    }

    public function sitaMaritau()
    {
        $this->data['current_module']['judul_module'] = 'Sita Marital';
        $this->view('layananhukum/prosedurpengajuan/perdata/sitamaritau.php', $this->data);
    }
    
    public function sitaPersamaan()
    {
        $this->data['current_module']['judul_module'] = 'Sita Persamaan';
        $this->view('layananhukum/prosedurpengajuan/perdata/sitapersamaan.php', $this->data);
    }

    //--------------------------------------------------------------------
    // FOLDER: prosedurpengajuan / pidana
    //--------------------------------------------------------------------
    public function acaraBiasa()
    {
        $this->data['current_module']['judul_module'] = 'Acara Biasa';
        $this->view('layananhukum/prosedurpengajuan/pidana/acarabiasa.php', $this->data);
    }
    
    public function acaraCepat()
    {
        $this->data['current_module']['judul_module'] = 'Acara Cepat';
        $this->view('layananhukum/prosedurpengajuan/pidana/acaracepat.php', $this->data);
    }
    
    public function acaraSingkat()
    {
        $this->data['current_module']['judul_module'] = 'Acara Singkat';
        $this->view('layananhukum/prosedurpengajuan/pidana/acarasingkat.php', $this->data);
    }

    public function penahananDanPerpanjangan()
    {
        $this->data['current_module']['judul_module'] = 'Penahanan dan Perpanjangan';
        $this->view('layananhukum/prosedurpengajuan/pidana/penahanandanperpanjangan.php', $this->data);
    }

    public function pengadilanAnak()
    {
        $this->data['current_module']['judul_module'] = 'Pengadilan Anak';
        $this->view('layananhukum/prosedurpengajuan/pidana/pengadilananak.php', $this->data);
    }
    
    public function penggeledahan()
    {
        $this->data['current_module']['judul_module'] = 'Penggeledahan';
        $this->view('layananhukum/prosedurpengajuan/pidana/penggeledahan.php', $this->data);
    }
    
    public function penyitaan()
    {
        $this->data['current_module']['judul_module'] = 'Penyitaan';
        $this->view('layananhukum/prosedurpengajuan/pidana/penyitaan.php', $this->data);
    }
    
    public function praperadilan()
    {
        $this->data['current_module']['judul_module'] = 'Praperadilan';
        $this->view('layananhukum/prosedurpengajuan/pidana/praperadilan.php', $this->data);
    }
}