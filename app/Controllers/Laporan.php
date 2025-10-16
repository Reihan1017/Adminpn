<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Laporan extends BaseController
{
    public function index()
    {
        return "Ini halaman Laporan utama";
    }

   // halaman utama Laporan
   public function akuntabilitas()
   {
       return view('layananpublik/laporan/akuntabilitas');
   }

   public function hasilpenelitian()
   {
       return view('layananpublik/laporan/hasilpenelitian');
   }

   public function laporankeuangan()
   {
       return view('layananpublik/laporan/laporankeuangan');
   }

   public function laporanpelayananpublik()
   {
       return view('layananpublik/laporan/laporanpelayananpublik');
   }

   public function laporansurvey()
   {
       return view('layananpublik/laporan/laporansurvey');
   }

   public function laporantahunan()
   {
       return view('layananpublik/laporan/laporantahunan');
   }

   public function lhkpn()
   {
       return view('layananpublik/laporan/lhkpn');
   }

   public function ringkasandaftaraset()
   {
       return view('layananpublik/laporan/ringkasandaftaraset');
   }

   public function ringkasanlaporan()
   {
       return view('layananpublik/laporan/ringkasanlaporan');
   }

   public function sakip()
   {
       return view('layananpublik/laporan/sakip');
   }
}
