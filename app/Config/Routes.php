<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->get('/', 'Login::index');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);
$routes->post('artikel/upload-image', 'Artikel::uploadImage');

//======================================================================
// ROUTES UNTUK CONTROLLER BERANDA PENGADILAN
//======================================================================
$routes->get('beranda/beranda',                                'Beranda::Beranda');

//======================================================================
// ROUTES UNTUK CONTROLLER TENTANG PENGADILAN (LENGKAP)
//======================================================================

// --- HALAMAN STATIS SEDERHANA (Teks & Gambar) ---
$routes->get('tentang-pengadilan/pengantar-ketua',             'Tentangpengadilan::pengantarKetua');
$routes->post('tentang-pengadilan/pengantar-ketua',            'Tentangpengadilan::pengantarKetua');

$routes->get('tentang-pengadilan/sejarah',                     'Tentangpengadilan::sejarah');
$routes->post('tentang-pengadilan/sejarah',                    'Tentangpengadilan::sejarah');

$routes->get('tentang-pengadilan/struktur-organisasi',         'Tentangpengadilan::struktur');
$routes->post('tentang-pengadilan/struktur-organisasi',        'Tentangpengadilan::struktur');

$routes->get('tentang-pengadilan/wilayah-hukum',               'Tentangpengadilan::wilayah');
$routes->post('tentang-pengadilan/wilayah-hukum',              'Tentangpengadilan::wilayah');

$routes->get('tentang-pengadilan/sistem-pengelolaan',          'Tentangpengadilan::sistemPengelolaanPn');
$routes->post('tentang-pengadilan/sistem-pengelolaan',         'Tentangpengadilan::sistemPengelolaanPn');

// --- HALAMAN VISI MISI ---
$routes->get('tentang-pengadilan/visi-misi',                   'Tentangpengadilan::visiMisi');
$routes->post('tentang-pengadilan/simpan-visi-misi',           'Tentangpengadilan::simpanVisiMisi');

// --- HALAMAN KEPANITERAAN (Teks) ---
$routes->get('tentang-pengadilan/kepaniteraan-hukum',          'Tentangpengadilan::kepaniteraanHukum');
$routes->post('tentang-pengadilan/kepaniteraan-hukum',         'Tentangpengadilan::kepaniteraanHukum');
$routes->get('tentang-pengadilan/kepaniteraan-perdata',        'Tentangpengadilan::kepaniteraanPerdata');
$routes->post('tentang-pengadilan/kepaniteraan-perdata',       'Tentangpengadilan::kepaniteraanPerdata');
$routes->get('tentang-pengadilan/kepaniteraan-pidana',         'Tentangpengadilan::kepaniteraanPidana');
$routes->post('tentang-pengadilan/kepaniteraan-pidana',        'Tentangpengadilan::kepaniteraanPidana');

// --- HALAMAN PROFIL PERUBAHAN ---
$routes->get('tentang-pengadilan/role-model',                  'Tentangpengadilan::roleModel');
$routes->post('tentang-pengadilan/role-model',                 'Tentangpengadilan::roleModel');

// --- AGEN PERUBAHAN (CRUD) ---
$routes->get('tentang-pengadilan/profil-perubahan',            'Tentangpengadilan::profilPerubahan'); // Ini halaman daftarnya
$routes->get('tentang-pengadilan/form-agen-perubahan/(:num?)',  'Tentangpengadilan::formAgenPerubahan/$1');
$routes->post('tentang-pengadilan/simpan-agen-perubahan',      'Tentangpengadilan::simpanAgenPerubahan');
$routes->get('tentang-pengadilan/hapus-agen-perubahan/(:num)', 'Tentangpengadilan::hapusAgenPerubahan/$1');

// --- PROFIL HAKIM DAN PEGAWAI (CRUD) ---
$routes->get('tentang-pengadilan/profilhakim',                 'Tentangpengadilan::profilHakim');
$routes->get('tentang-pengadilan/profilkepaniteraan',          'Tentangpengadilan::profilKepaniteraan');
$routes->get('tentang-pengadilan/profilkesekretariatan',       'Tentangpengadilan::profilKesekretariatan');
$routes->get('tentang-pengadilan/profilpppk',                  'Tentangpengadilan::profilPppk');
$routes->get('tentang-pengadilan/form-profil/(:segment)/(:num?)','Tentangpengadilan::formProfil/$1/$2'); // Untuk form tambah/edit
$routes->post('tentang-pengadilan/simpan-profil',              'Tentangpengadilan::simpanProfil'); // Untuk menyimpan
$routes->get('tentang-pengadilan/hapus-profil/(:num)',         'Tentangpengadilan::hapusProfil/$1'); // Untuk menghapus

// --- HALAMAN LINK EKSTERNAL ---
$routes->get('tentang-pengadilan/jdih',                        'Tentangpengadilan::jdihPnCiamis');
$routes->post('tentang-pengadilan/jdih',                       'Tentangpengadilan::jdihPnCiamis');
$routes->get('tentang-pengadilan/kebijakan',                   'Tentangpengadilan::kebijakan');
$routes->post('tentang-pengadilan/kebijakan',                  'Tentangpengadilan::kebijakan');

// --- HALAMAN UPLOAD PDF (SINGLE) ---
$routes->get('tentang-pengadilan/rencana-strategis',           'Tentangpengadilan::rencanaStrategis');
$routes->post('tentang-pengadilan/rencana-strategis',          'Tentangpengadilan::rencanaStrategis');

// --- HALAMAN RENCANA KERJA (CRUD DOKUMEN) ---
$routes->get('tentang-pengadilan/rencana-kerja',               'Tentangpengadilan::rencanaKerja');
$routes->get('tentang-pengadilan/form-rencana-kerja/(:num?)',  'Tentangpengadilan::formRencanaKerja/$1');
$routes->post('tentang-pengadilan/simpan-rencana-kerja',       'Tentangpengadilan::simpanRencanaKerja');
$routes->get('tentang-pengadilan/hapus-rencana-kerja/(:num)', 'Tentangpengadilan::hapusRencanaKerja/$1');

// --- HALAMAN KODE ETIK (HYBRID) ---
$routes->get('tentang-pengadilan/kode-etik-hakim',             'Tentangpengadilan::kodeEtikHakim');
$routes->get('tentang-pengadilan/edit-intro-kode-etik',        'Tentangpengadilan::editIntroKodeEtik');
$routes->post('tentang-pengadilan/edit-intro-kode-etik',       'Tentangpengadilan::editIntroKodeEtik');
$routes->get('tentang-pengadilan/form-kode-etik/(:num?)',      'Tentangpengadilan::formKodeEtik/$1');
$routes->post('tentang-pengadilan/simpan-kode-etik',           'Tentangpengadilan::simpanKodeEtik');
$routes->get('tentang-pengadilan/hapus-kode-etik/(:num)',      'Tentangpengadilan::hapusKodeEtik/$1');


//======================================================================
// ROUTES UNTUK CONTROLLER LAYANANPUBLIK
//======================================================================
$routes->get('layanan-publik',                             'Layananpublik::index');

// --- FOLDER: jamkerja ---
$routes->get('layananpublik/jamKerja',                       'Layananpublik::jamKerja');
$routes->post('layananpublik/jamKerja',                      'Layananpublik::jamKerja');

// --- FOLDER: laporan ---
// (Beberapa rute belum ditambahkan dikarenakan isi dari frontend website duplikat/error)
$routes->get('layanan-publik/laporan/akuntabilitas',        'Layananpublik::laporanAkuntabilitas');
$routes->get('layanan-publik/laporan/hasil-penelitian',     'Layananpublik::laporanHasilPenelitian');
$routes->get('layanan-publik/laporan/pelayanan-publik',     'Layananpublik::laporanPelayananPublik');

// Laporan Keuangan (CRUD)
$routes->get('layananpublik/laporanKeuangan',                'Layananpublik::laporanKeuangan');
$routes->get('layananpublik/formLaporanKeuangan/(:num?)',     'Layananpublik::formLaporanKeuangan/$1');
$routes->post('layananpublik/simpanLaporanKeuangan',         'Layananpublik::simpanLaporanKeuangan');
$routes->get('layananpublik/hapusLaporanKeuangan/(:num)',     'Layananpublik::hapusLaporanKeuangan/$1');

// Laporan Survey (Hybrid: Intro + CRUD)
$routes->get('layananpublik/laporanSurvey',                  'Layananpublik::laporanSurvey');
$routes->get('layananpublik/editIntroLaporanSurvey',         'Layananpublik::editIntroLaporanSurvey');
$routes->post('layananpublik/editIntroLaporanSurvey',        'Layananpublik::editIntroLaporanSurvey');
$routes->get('layananpublik/formLaporanSurvey/(:num?)',       'Layananpublik::formLaporanSurvey/$1');
$routes->post('layananpublik/simpanLaporanSurvey',           'Layananpublik::simpanLaporanSurvey');
$routes->get('layananpublik/hapusLaporanSurvey/(:num)',       'Layananpublik::hapusLaporanSurvey/$1');

// Laporan Tahunan (Teks + PDF Single)
$routes->get('layananpublik/laporanTahunan',                 'Layananpublik::laporanTahunan');
$routes->post('layananpublik/laporanTahunan',                'Layananpublik::laporanTahunan');

// LHKPN (Teks + Link Eksternal)
$routes->get('layananpublik/lhkpn',                          'Layananpublik::lhkpn');
$routes->post('layananpublik/lhkpn',                         'Layananpublik::lhkpn');

// Ringkasan Daftar Aset (Teks + PDF Single)
$routes->get('layananpublik/ringkasanDaftarAset',            'Layananpublik::ringkasanDaftarAset');
$routes->post('layananpublik/ringkasanDaftarAset',           'Layananpublik::ringkasanDaftarAset');

// Ringkasan Laporan / LKjIP (CRUD Dokumen)
$routes->get('layananpublik/ringkasanLaporan',               'Layananpublik::ringkasanLaporan');
$routes->get('layananpublik/formRingkasanLaporan/(:num?)',    'Layananpublik::formRingkasanLaporan/$1');
$routes->post('layananpublik/simpanRingkasanLaporan',        'Layananpublik::simpanRingkasanLaporan');
$routes->get('layananpublik/hapusRingkasanLaporan/(:num)',    'Layananpublik::hapusRingkasanLaporan/$1');

// SAKIP (CRUD Dokumen)
$routes->get('layananpublik/sakip',                          'Layananpublik::sakip');
$routes->get('layananpublik/formSakip/(:num?)',               'Layananpublik::formSakip/$1');
$routes->post('layananpublik/simpanSakip',                   'Layananpublik::simpanSakip');
$routes->get('layananpublik/hapusSakip/(:num)',               'Layananpublik::hapusSakip/$1');

// --- FOLDER: layanandisabilitas ---
// Penyandang Disabilitas (Hybrid: Teks/Gambar + CRUD Dokumen)
$routes->get('layananpublik/penyandangDisabilitas',          'Layananpublik::penyandangDisabilitas');
$routes->post('layananpublik/simpanPenyandangDisabilitas',   'Layananpublik::simpanPenyandangDisabilitas');
$routes->get('layananpublik/formDokumenDisabilitas/(:num?)',  'Layananpublik::formDokumenDisabilitas/$1');
$routes->post('layananpublik/simpanDokumenDisabilitas',      'Layananpublik::simpanDokumenDisabilitas');
$routes->get('layananpublik/hapusDokumenDisabilitas/(:num)',  'Layananpublik::hapusDokumenDisabilitas/$1');

// Sarana Prasarana Disabilitas (Teks + PDF Single)
$routes->get('layananpublik/saranaPrasaranaDisabilitas',     'Layananpublik::saranaPrasaranaDisabilitas');
$routes->post('layananpublik/saranaPrasaranaDisabilitas',    'Layananpublik::saranaPrasaranaDisabilitas');

// --- FOLDER: pengaduanlayananpublik ---
// Dasar Hukum (Teks)
$routes->get('layananpublik/dasarHukum',                     'Layananpublik::dasarHukum');
$routes->post('layananpublik/dasarHukum',                    'Layananpublik::dasarHukum');

// Prosedur Pengaduan (Hybrid: Teks + Link/Gambar Tombol)
$routes->get('layananpublik/prosedurPengaduan',              'Layananpublik::prosedurPengaduan');
$routes->post('layananpublik/prosedurPengaduan',             'Layananpublik::prosedurPengaduan');

// --- FOLDER: pengumuman ---
// Denda Tilang (CRUD)
$routes->get('layananpublik/dendaTilang',                    'Layananpublik::dendaTilang');
$routes->get('layananpublik/formDendaTilang/(:num?)',         'Layananpublik::formDendaTilang/$1');
$routes->post('layananpublik/simpanDendaTilang',             'Layananpublik::simpanDendaTilang');
$routes->get('layananpublik/hapusDendaTilang/(:num)',         'Layananpublik::hapusDendaTilang/$1');

// Lelang Barang (CRUD)
$routes->get('layananpublik/lelangBarang',                   'Layananpublik::lelangBarang');
$routes->get('layananpublik/formLelangBarang/(:num?)',        'Layananpublik::formLelangBarang/$1');
$routes->post('layananpublik/simpanLelangBarang',            'Layananpublik::simpanLelangBarang');
$routes->get('layananpublik/hapusLelangBarang/(:num)',        'Layananpublik::hapusLelangBarang/$1');

// Panggilan Tidak Diketahui (CRUD)
$routes->get('layananpublik/panggilanTdkDiketahui',          'Layananpublik::panggilanTdkDiketahui');
$routes->get('layananpublik/formPanggilanTdkDiketahui/(:num?)','Layananpublik::formPanggilanTdkDiketahui/$1');
$routes->post('layananpublik/simpanPanggilanTdkDiketahui',   'Layananpublik::simpanPanggilanTdkDiketahui');
$routes->get('layananpublik/hapusPanggilanTdkDiketahui/(:num)','Layananpublik::hapusPanggilanTdkDiketahui/$1');

// (Beberapa rute belum ditambahkan dikarenakan isi dari frontend website duplikat/error)
$routes->get('layanan-publik/pengumuman/penerimaan-pegawai', 'Layananpublik::pengumumanPenerimaanPegawai');
$routes->get('layanan-publik/pengumuman/lainnya',            'Layananpublik::pengumumanLainnya');

// --- FOLDER: prosedurpermohonan ---
// Prosedur Permohonan Informasi (Teks + Gambar/PDF Single)
$routes->get('layananpublik/prosedurPermohonanInformasi',    'Layananpublik::prosedurPermohonanInformasi');
$routes->post('layananpublik/prosedurPermohonanInformasi',   'Layananpublik::prosedurPermohonanInformasi');

// --- FOLDER: ptsp ---
// (Beberapa rute belum ditambahkan dikarenakan isi dari frontend website duplikat/error)
$routes->get('layanan-publik/ptsp',                          'Layananpublik::ptsp');
$routes->get('layanan-publik/ptsp/index',                    'Layananpublik::ptspIndex');

// Jenis Layanan PTSP (Teks)
$routes->get('layanan-publik/ptsp/jenis-layanan',            'Layananpublik::ptspJenisLayanan');
$routes->post('layanan-publik/ptsp/jenis-layanan',           'Layananpublik::ptspJenisLayanan'); 

// Kompensasi Pelayanan (Teks + PDF Single)
$routes->get('layananpublik/kompensasiPelayanan',            'Layananpublik::kompensasiPelayanan');
$routes->post('layananpublik/kompensasiPelayanan',           'Layananpublik::kompensasiPelayanan');

// Maklumat Pelayanan (Teks + Gambar Single)
$routes->get('layananpublik/maklumatPelayanan',              'Layananpublik::maklumatPelayanan');
$routes->post('layananpublik/maklumatPelayanan',             'Layananpublik::maklumatPelayanan');

// Standar Pelayanan (Hybrid: Teks Intro + CRUD Dokumen)
$routes->get('layananpublik/standarPelayanan',               'Layananpublik::standarPelayanan');
$routes->get('layananpublik/editIntroStandarPelayanan',      'Layananpublik::editIntroStandarPelayanan'); // Rute terpisah untuk edit intro
$routes->post('layananpublik/editIntroStandarPelayanan',     'Layananpublik::editIntroStandarPelayanan'); // Rute terpisah untuk simpan intro
$routes->get('layananpublik/formStandarPelayanan/(:num?)',    'Layananpublik::formStandarPelayanan/$1');
$routes->post('layananpublik/simpanStandarPelayanan',        'Layananpublik::simpanStandarPelayanan');
$routes->get('layananpublik/hapusStandarPelayanan/(:num)',   'Layananpublik::hapusStandarPelayanan/$1'); // Rute hapus dokumen standar pelayanan

//======================================================================

//======================================================================
// ROUTES UNTUK CONTROLLER LAYANANHUKUM
//======================================================================

// HALAMAN UTAMA
$routes->get('layanan-hukum', 'Layananhukum::index');

// FOLDER: ecourt
$routes->get('layanan-hukum/ecourt', 'Layananhukum::ecourt');

// FOLDER: gugatansederhana
$routes->get('layanan-hukum/gugatan-sederhana', 'Layananhukum::gugatanSederhana');

// FOLDER: layanantdkwaragtdkmampu
$routes->get('layanan-hukum/tidak-mampu/peraturan', 'Layananhukum::peraturanDanKebijakan');
$routes->get('layanan-hukum/tidak-mampu/posbakum', 'Layananhukum::posBantuanHukum');
$routes->get('layanan-hukum/tidak-mampu/prodeo', 'Layananhukum::prodeo');

// FOLDER: pelayanandisabilitas
$routes->get('layanan-hukum/pelayanan-disabilitas', 'Layananhukum::plyndisabilitas');

// FOLDER: perkarapelanggaranlalulintas
$routes->get('layanan-hukum/lalu-lintas', 'Layananhukum::laluLintas');

// FOLDER: prosedurmediasi
$routes->get('layanan-hukum/mediasi', 'Layananhukum::mediasi');

// FOLDER: prosedurpengajuan / perdata
$routes->get('layanan-hukum/prosedur-perdata/eksekusi-grosse-akta', 'Layananhukum::eksekusiGrosseAkta');
$routes->get('layanan-hukum/prosedur-perdata/eksekusi-hak-tanggungan', 'Layananhukum::eksekusiHakTanggungan');
$routes->get('layanan-hukum/prosedur-perdata/eksekusi-jaminan', 'Layananhukum::eksekusiJaminan');
$routes->get('layanan-hukum/prosedur-perdata/eksekusi-putusan', 'Layananhukum::eksekusiPutusan');
$routes->get('layanan-hukum/prosedur-perdata/gugatan-kelompok', 'Layananhukum::gugatanKelompok');
$routes->get('layanan-hukum/prosedur-perdata/gugatan-kepentingan-umum', 'Layananhukum::gugatanKepentinganUmum');
$routes->get('layanan-hukum/prosedur-perdata/sita-eksekusi', 'Layananhukum::sitaEksekusi');
$routes->get('layanan-hukum/prosedur-perdata/sita-jaminan', 'Layananhukum::sitaJaminan');
$routes->get('layanan-hukum/prosedur-perdata/sita-maritau', 'Layananhukum::sitaMaritau');
$routes->get('layanan-hukum/prosedur-perdata/sita-persamaan', 'Layananhukum::sitaPersamaan');

// FOLDER: prosedurpengajuan / pidana
$routes->get('layanan-hukum/prosedur-pidana/acara-biasa', 'Layananhukum::acaraBiasa');
$routes->get('layanan-hukum/prosedur-pidana/acara-cepat', 'Layananhukum::acaraCepat');
$routes->get('layanan-hukum/prosedur-pidana/acara-singkat', 'Layananhukum::acaraSingkat');
$routes->get('layanan-hukum/prosedur-pidana/penahanan', 'Layananhukum::penahananDanPerpanjangan');
$routes->get('layanan-hukum/prosedur-pidana/pengadilan-anak', 'Layananhukum::pengadilanAnak');
$routes->get('layanan-hukum/prosedur-pidana/penggeledahan', 'Layananhukum::penggeledahan');
$routes->get('layanan-hukum/prosedur-pidana/penyitaan', 'Layananhukum::penyitaan');
$routes->get('layanan-hukum/prosedur-pidana/praperadilan', 'Layananhukum::praperadilan');

//======================================================================
// ROUTES UNTUK CONTROLLER BERITA
//======================================================================
// Rute untuk Berita Terkini (dikelola oleh Controller Pengumuman)
// Rute untuk Manajemen Berita (di backend admin)
$routes->group('pengumuman', function($routes){
    $routes->get('beritaTerkini', 'Pengumuman::beritaTerkini');
    $routes->get('formBerita/(:num?)', 'Pengumuman::formBerita/$1');
    $routes->post('simpanBerita', 'Pengumuman::simpanBerita');
    $routes->get('hapusBerita/(:num)', 'Pengumuman::hapusBerita/$1');
});

// Anda juga perlu rute untuk API upload gambar TinyMCE
$routes->post('api/uploadImage', 'Api::uploadImage'); // Pastikan controller Api dan method uploadImage ada
$routes->get('berita/pengumuman', 'Pengumuman::pengumuman');

//======================================================================
// ROUTES UNTUK CONTROLLER HUBUNGIKAMI
//======================================================================
$routes->get('hubungi-kami/alamat-pengadilan', 'Hubungikami::alamatPengadilan');
$routes->get('hubungi-kami/sosial-media', 'Hubungikami::sosialMedia');

//======================================================================
// ROUTES UNTUK CONTROLLER REFORMASIBIROKRASI
//======================================================================

// Akreditasi Penjaminan Mutu
$routes->get('reformasi-birokrasi/akreditasi/manual-mutu', 'Reformasibirokrasi::manualMutu');
$routes->get('reformasi-birokrasi/akreditasi/penilaian-badilum', 'Reformasibirokrasi::penilaianBadilum');
$routes->get('reformasi-birokrasi/akreditasi/sertifikat', 'Reformasibirokrasi::sertifikatAkreditasi');
$routes->get('reformasi-birokrasi/akreditasi/tim-pengadilan', 'Reformasibirokrasi::timPengadilan');

// Zona Integritas
$routes->get('reformasi-birokrasi/zona-integritas/areasatu', 'Reformasibirokrasi::zonaIntegritasAreaSatu');
$routes->get('reformasi-birokrasi/zona-integritas/areadua', 'Reformasibirokrasi::zonaIntegritasAreaDua');
$routes->get('reformasi-birokrasi/zona-integritas/areatiga', 'Reformasibirokrasi::zonaIntegritasAreaTiga');
$routes->get('reformasi-birokrasi/zona-integritas/areaempat', 'Reformasibirokrasi::zonaIntegritasAreaEmpat');
$routes->get('reformasi-birokrasi/zona-integritas/arealima', 'Reformasibirokrasi::zonaIntegritasAreaLima');
$routes->get('reformasi-birokrasi/zona-integritas/areaenam', 'Reformasibirokrasi::zonaIntegritasAreaEnam');
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
/* $routes->get('/', 'Home::index');
$routes->setTranslateURIDashes(true);
 */
 
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}