<?php 

namespace Config;

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
$routes->group('beranda', function($routes){
});

//======================================================================
// ROUTES UNTUK CONTROLLER PROFIL HAKIM & PEGAWAI
//======================================================================
$routes->group('tentangpengadilan', function($routes) {
    // Rute Terpusat untuk Profil Pegawai
    $routes->get('profilPegawai', 'Tentangpengadilan::profilPegawai');
    $routes->get('formProfil', 'Tentangpengadilan::formProfil'); // Form Tambah
    $routes->get('formProfil/(:num)', 'Tentangpengadilan::formProfil/$1'); // Form Edit
    $routes->post('simpanProfil', 'Tentangpengadilan::simpanProfil');
    $routes->get('hapusProfil/(:num)', 'Tentangpengadilan::hapusProfil/$1');
});

// ROUTES UNTUK BERITA
//======================================================================
$routes->group('berita', function($routes){
    // Halaman utama berita (URL: /berita)
    $routes->get('/', 'Berita::beritaTerkini'); 
    
    // Rute untuk TAMBAH (URL: /berita/form)
    $routes->get('form', 'Berita::formBerita'); 
    
    // Rute untuk EDIT (URL: /berita/form/123)
    $routes->get('form/(:num)', 'Berita::formBerita/$1'); 
    
    // Rute untuk SIMPAN (method POST)
    $routes->post('simpan', 'Berita::simpanBerita');
    
    // Rute untuk HAPUS (URL: /berita/hapus/123)
    $routes->get('hapus/(:num)', 'Berita::hapusBerita/$1');
});

//======================================================================
// ROUTES UNTUK PENGUMUMAN 
//======================================================================
$routes->group('pengumuman', function($routes){
});

// Anda juga perlu rute untuk API upload gambar TinyMCE
$routes->post('api/uploadImage', 'Api::uploadImage'); 

// --- CRUD UNTUK PENGUMUMAN ---
// URL akan menjadi /pengumuman, /pengumuman/create, dll.
$routes->get('pengumuman', 'Pengumuman::index');
$routes->get('pengumuman/create', 'Pengumuman::create');
$routes->post('pengumuman/store', 'Pengumuman::store');
$routes->get('pengumuman/edit/(:num)', 'Pengumuman::edit/$1');
$routes->post('pengumuman/update/(:num)', 'Pengumuman::update/$1');
$routes->post('pengumuman', 'Pengumuman::delete', ['as' => 'pengumuman-delete']);
$routes->post('pengumuman/upload-image', 'Pengumuman::uploadImage');


// --- CRUD UNTUK HALAMAN STATIS (MASTER DATA) ---
$routes->get('halamanstatis', 'HalamanStatis::index');
$routes->get('halamanstatis/create', 'HalamanStatis::create');
$routes->post('halamanstatis/store', 'HalamanStatis::store');
$routes->get('halamanstatis/edit/(:num)', 'HalamanStatis::edit/$1');
$routes->post('halamanstatis/update/(:num)', 'HalamanStatis::update/$1');
$routes->post('halamanstatis', 'HalamanStatis::delete', ['as' => 'halamanstatis-delete']);

// --- RUTE PINTAR UNTUK SEMUA MENU HALAMAN STATIS ---
$routes->get('kelola-halaman/(:segment)', 'KelolaHalaman::index/$1');
$routes->post('kelola-halaman/update/(:segment)', 'KelolaHalaman::update/$1');
$routes->post('kelola-halaman/upload-image', 'KelolaHalaman::upload_image');
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