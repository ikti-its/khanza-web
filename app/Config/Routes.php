<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'userPegawaiController::lihatDashboard', ['filter' => 'auth']);
$routes->get('/login', 'authController::index');
$routes->get('/logout', 'authController::logout', ['filter' => 'auth']);

$routes->get('/dashboard', 'userPegawaiController::lihatDashboard', ['filter' => 'auth']);
$routes->post('/dashboard', 'authController::login', ['filter' => 'noauth']);
$routes->get('/profile', 'userPegawaiController::lihatProfil', ['filter' => 'auth']);
$routes->post('/submiteditprofil/(:segment)', 'userPegawaiController::submitEditProfil/$1', ['filter' => 'auth']);
$routes->get('/datauserpegawai', 'userPegawaiController::lihatPegawai', ['filter' => 'auth']);
$routes->get('/detailberkaspegawai/(:segment)', 'userPegawaiController::detailBerkasPegawai/$1', ['filter' => 'auth']);


$routes->get('/admin', 'authController::dashboard', ['filter' => 'auth']);
$routes->post('/admin', 'authController::login', ['filter' => 'noauth']);

//kalo mau akses halaman pake auth, pertama kali pake no auth(contoh di login)
$routes->get('/dataakun', 'akunController::dataAkun', ['filter' => 'auth']);

$routes->get('/tambahakun', 'akunController::tambahAkun', ['filter' => 'auth']);
$routes->post('/submittambahakun', 'akunController::submitTambahAkun', ['filter' => 'auth']);

$routes->get('/editakun/(:any)', 'akunController::editAkun/$1', ['filter' => 'auth']);
$routes->post('/submiteditakun', 'akunController::submitEditAkun', ['filter' => 'auth']);

$routes->post('/submiteditakun/(:segment)', 'AkunController::submitEditAkun/$1');

$routes->get('/hapusakun/(:segment)', 'AkunController::hapusAkun/$1');

// $routes->get('/datapegawai', 'akunController::dataPegawai');


// $routes->get('/admin', 'authController::dashboard');

// $routes->get('/logout', 'authController::logout');

// $routes->get('/datapegawai', 'adminController::dataPegawai', ['filter' => 'noauth']);

// $routes->get('/signup', 'adminController::daftarPegawai');
// $routes->get('/editpegawai', 'adminController::editPegawai');
// $routes->get('/presensipegawai', 'adminController::presensiPegawai');

//==============================================================================
$routes->get('/datapegawai', 'pegawaiController::dataPegawai', ['filter' => 'auth']);
$routes->get('/datapegawai-test', 'pegawaiController::dataPegawaiTest',  ['filter' => 'auth']);

$routes->get('/tambahpegawai', 'pegawaiController::tambahPegawai', ['filter' => 'auth']);
$routes->post('/submittambahpegawai', 'pegawaiController::submitTambahPegawai', ['filter' => 'auth']);

$routes->get('/editpegawai/(:any)', 'pegawaiController::editPegawai/$1', ['filter' => 'auth']);
$routes->post('/submiteditpegawai/(:segment)', 'pegawaiController::submitEditPegawai/$1', ['filter' => 'auth']);

$routes->get('/hapuspegawai/(:segment)', 'pegawaiController::hapusPegawai/$1', ['filter' => 'auth']);

//==========================================================================================
$routes->get('/presensi', 'presensiController::halamanPresensi', ['filter' => 'auth']);
// Route to serve the JavaScript file (loadModel.js)
$routes->get('loadModel.js', 'PresensiController::script', ['filter' => 'auth']);


//==========================================================================================
$routes->get('/alamatpegawai', 'alamatController::alamatPegawai', ['filter' => 'auth']);
$routes->get('/tambahalamat', 'alamatController::tambahAlamat', ['filter' => 'auth']);

$routes->post('/submittambahalamat', 'alamatController::submitTambahAlamat', ['filter' => 'auth']);

$routes->get('/editalamat/(:any)', 'alamatController::editAlamat/$1', ['filter' => 'auth']);
$routes->post('/submiteditalamat/(:segment)', 'alamatController::submitEditAlamat/$1', ['filter' => 'auth']);

$routes->get('/hapusalamat/(:segment)', 'alamatController::hapusAlamat/$1', ['filter' => 'auth']);

//===========================================================================================
$routes->get('/berkaspegawai', 'berkasController::berkasPegawai', ['filter' => 'auth']);
$routes->get('/tambahberkas', 'berkasController::tambahBerkas', ['filter' => 'auth']);

$routes->post('/submittambahberkas', 'berkasController::submitTambahBerkas', ['filter' => 'auth']);

$routes->get('/editberkas/(:any)', 'berkasController::editBerkas/$1', ['filter' => 'auth']);

$routes->post('/submiteditberkas/(:segment)', 'berkasController::submitEditBerkas/$1', ['filter' => 'auth']);
$routes->post('/submiteditktp/(:segment)', 'berkasController::submitEditKTP/$1', ['filter' => 'auth']);
$routes->post('/submiteditkk/(:segment)', 'berkasController::submitEditKK/$1', ['filter' => 'auth']);
$routes->post('/submiteditnpwp/(:segment)', 'berkasController::submitEditNPWP/$1', ['filter' => 'auth']);
$routes->post('/submiteditbpjs/(:segment)', 'berkasController::submitEditBPJS/$1', ['filter' => 'auth']);
$routes->post('/submiteditijazah/(:segment)', 'berkasController::submitEditIjazah/$1', ['filter' => 'auth']);
$routes->post('/submiteditskck/(:segment)', 'berkasController::submitEditSkck/$1', ['filter' => 'auth']);
$routes->post('/submiteditstr/(:segment)', 'berkasController::submitEditStr/$1', ['filter' => 'auth']);
$routes->post('/submiteditserkom/(:segment)', 'berkasController::submitEditSerkom/$1', ['filter' => 'auth']);

$routes->get('/hapusberkas/(:segment)', 'berkasController::hapusBerkas/$1', ['filter' => 'auth']);


$routes->group('datamedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MedisController::dataMedis');
    $routes->get('tambah', 'MedisController::tambahMedis');
    $routes->post('submittambah', 'MedisController::submitTambahMedis');
    $routes->get('edit/(:any)', 'MedisController::editMedis/$1');
    $routes->post('submitedit', 'MedisController::submitEditMedis');
    $routes->post('submitedit/(:segment)', 'MedisController::submitEditMedis/$1');
    $routes->delete('hapus/(:segment)', 'MedisController::hapusMedis/$1');
});

$routes->group('stokkeluarmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokKeluarController::dataStokKeluarMedis');
    $routes->get('tambah', 'StokKeluarController::tambahStokKeluarMedis');
    $routes->post('submittambah', 'StokKeluarController::submitTambahStokKeluarMedis');
    $routes->get('edit/(:any)', 'StokKeluarController::editStokKeluarMedis/$1');
    $routes->post('submitedit', 'StokKeluarController::submitEditStokKeluarMedis');
    $routes->post('submitedit/(:segment)', 'StokKeluarController::submitEditStokKeluarMedis/$1');
    $routes->delete('hapus/(:segment)', 'StokKeluarController::hapusStokKeluarMedis/$1');
});


$routes->get('/dashboardpengadaanmedis', 'PengajuanController::dashboardPengadaan', ['filter' => 'auth']);

//Persetujuan
$routes->group('persetujuanpengajuan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PersetujuanController::dataPersetujuan');
    $routes->post('submit', 'PersetujuanController::submitTambahPersetujuan');
    $routes->post('submit/(:segment)', 'PersetujuanController::submitTambahPersetujuan/$1');
});
//Pengajuan
// $routes->add('/pengajuanmedis', 'PengajuanController::dataPengajuanMedis', ['filter' => 'auth']);
// $routes->get('/tambahpengajuanmedis', 'PengajuanController::tambahPengajuanMedis', ['filter' => 'auth']);
// $routes->post('/submittambahpengajuanmedis', 'PengajuanController::submitTambahPengajuanMedis', ['filter' => 'auth']);
// $routes->get('/editpengajuanmedis/(:any)', 'PengajuanController::editPengajuanMedis/$1', ['filter' => 'auth']);
// $routes->post('/submiteditpengajuanmedis', 'PengajuanController::submitEditPengajuanMedis', ['filter' => 'auth']);
// $routes->post('/submiteditpengajuanmedis/(:segment)', 'PengajuanController::submitEditPengajuanMedis/$1');
// $routes->delete('/hapuspengajuanmedis/(:segment)', 'PengajuanController::hapusPengajuanMedis/$1');
$routes->group('pengajuanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->add('/', 'PengajuanController::dataPengajuanMedis');
    $routes->get('tambah', 'PengajuanController::tambahPengajuanMedis');
    $routes->post('submittambah', 'PengajuanController::submitTambahPengajuanMedis');
    $routes->get('edit/(:any)', 'PengajuanController::editPengajuanMedis/$1');
    $routes->post('submitedit', 'PengajuanController::submitEditPengajuanMedis');
    $routes->post('submitedit/(:segment)', 'PengajuanController::submitEditPengajuanMedis/$1');
    $routes->delete('hapus/(:segment)', 'PengajuanController::hapusPengajuanMedis/$1');
});


//Pemesanan
// Grup untuk Pemesanan Medis
$routes->group('pemesananmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemesananController::dataPemesananMedis');
    $routes->get('cetak/(:segment)', 'PemesananController::cetakPemesananBrgMedis/$1');
    $routes->get('tambah', 'PemesananController::tambahPemesananMedis');
    $routes->get('tambah/(:any)', 'PemesananController::tambahPemesananMedisbyId/$1');
    $routes->post('submittambah', 'PemesananController::submitTambahPemesananMedis');
    $routes->get('edit/(:any)', 'PemesananController::editPemesananMedis/$1');
    $routes->post('submitedit', 'PemesananController::submitEditPemesananMedis');
    $routes->post('submitedit/(:segment)', 'PemesananController::submitEditPemesananMedis/$1');
    $routes->delete('hapus/(:segment)', 'PemesananController::hapusPemesananMedis/$1');
});

// Grup untuk Penerimaan Medis
$routes->group('penerimaanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PenerimaanController::dataPenerimaanMedis');
    $routes->get('tambah', 'PenerimaanController::tambahPenerimaanMedis');
    $routes->get('tambah/(:any)', 'PenerimaanController::tambahPenerimaanMedisbyId/$1');
    $routes->post('submittambah', 'PenerimaanController::submitTambahPenerimaanMedis');
    $routes->get('edit/(:any)', 'PenerimaanController::editPenerimaanMedis/$1');
    $routes->post('submitedit', 'PenerimaanController::submitEditPenerimaanMedis');
    $routes->post('submitedit/(:segment)', 'PenerimaanController::submitEditPenerimaanMedis/$1');
    $routes->delete('hapus/(:segment)', 'PenerimaanController::hapusPenerimaanMedis/$1');
});

// Grup untuk Tagihan Medis
$routes->group('tagihanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TagihanController::dataTagihanMedis');
    $routes->get('tambah', 'TagihanController::tambahTagihanMedis');
    $routes->get('tambah/(:any)', 'TagihanController::tambahTagihanMedisbyId/$1');
    $routes->post('submittambah', 'TagihanController::submitTambahTagihanMedis');
    $routes->get('edit/(:any)', 'TagihanController::editTagihanMedis/$1');
    $routes->post('submitedit', 'TagihanController::submitEditTagihanMedis');
    $routes->post('submitedit/(:segment)', 'TagihanController::submitEditTagihanMedis/$1');
    $routes->delete('hapus/(:segment)', 'TagihanController::hapusTagihanMedis/$1');
});

// Grup untuk Stok Keluar Medis
