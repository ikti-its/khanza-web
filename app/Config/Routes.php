<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'userPegawaiController::lihatDashboard', ['filter' => 'auth']);
$routes->get('/login', 'authController::index');
$routes->post('/logout', 'authController::logout', ['filter' => 'auth']);

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

$routes->get('/error_403', 'ErrorController::noAccess403', ['filter' => 'auth']);

$routes->group('datamedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MedisController::dataMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'MedisController::tambahMedis', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->post('submittambah', 'MedisController::submitTambahMedis', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->get('edit/(:any)', 'MedisController::editMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->post('submitedit', 'MedisController::submitEditMedis', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->post('submitedit/(:segment)', 'MedisController::submitEditMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->delete('hapus/(:segment)', 'MedisController::hapusMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4002']);
});

$routes->group('stokkeluarmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokKeluarController::dataStokKeluarMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'StokKeluarController::tambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submittambah', 'StokKeluarController::submitTambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->get('edit/(:any)', 'StokKeluarController::editStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submitedit', 'StokKeluarController::submitEditStokKeluarMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submitedit/(:segment)', 'StokKeluarController::submitEditStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->delete('hapus/(:segment)', 'StokKeluarController::hapusStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
});


$routes->get('/dashboardpengadaanmedis', 'PengajuanController::dashboardPengadaan', ['filter' => ['auth', 'checkpermission:1337,1,4001,4002,4003,4004,5001']]);

//Persetujuan
$routes->group('persetujuanpengajuan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PersetujuanController::dataPersetujuan', ['filter' => 'checkpermission:1337,1,4001,4004, 5001']);
    $routes->post('submit', 'PersetujuanController::submitTambahPersetujuan', ['filter' => 'checkpermission:1337,1,4001,5001']);
    $routes->post('submit/(:segment)', 'PersetujuanController::submitTambahPersetujuan/$1', ['filter' => 'checkpermission:1337,1,4001,5001']);
});
//Pengajuan
$routes->group('pengajuanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->add('/', 'PengajuanController::dataPengajuanMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'PengajuanController::tambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submittambah', 'PengajuanController::submitTambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->get('edit/(:any)', 'PengajuanController::editPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submitedit', 'PengajuanController::submitEditPengajuanMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submitedit/(:segment)', 'PengajuanController::submitEditPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->delete('hapus/(:segment)', 'PengajuanController::hapusPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
});


//Pemesanan
// Grup untuk Pemesanan Medis
$routes->group('pemesananmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemesananController::dataPemesananMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('cetak/(:segment)', 'PemesananController::cetakPemesananBrgMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->get('tambah', 'PemesananController::tambahPemesananMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->get('tambah/(:any)', 'PemesananController::tambahPemesananMedisbyId/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submittambah', 'PemesananController::submitTambahPemesananMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->get('edit/(:any)', 'PemesananController::editPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submitedit', 'PemesananController::submitEditPemesananMedis', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->post('submitedit/(:segment)', 'PemesananController::submitEditPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
    $routes->delete('hapus/(:segment)', 'PemesananController::hapusPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4003']);
});

// Grup untuk Penerimaan Medis
$routes->group('penerimaanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PenerimaanController::dataPenerimaanMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004,5001']);
    $routes->get('tambah', 'PenerimaanController::tambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->get('tambah/(:any)', 'PenerimaanController::tambahPenerimaanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submittambah', 'PenerimaanController::submitTambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->get('edit/(:any)', 'PenerimaanController::editPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submitedit', 'PenerimaanController::submitEditPenerimaanMedis', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->post('submitedit/(:segment)', 'PenerimaanController::submitEditPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
    $routes->delete('hapus/(:segment)', 'PenerimaanController::hapusPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,4001,4004']);
});

// Grup untuk Tagihan Medis
$routes->group('tagihanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TagihanController::dataTagihanMedis', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004,5001']);
    $routes->get('tambah/(:any)', 'TagihanController::tambahTagihanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,5001']);
    $routes->post('submittambah', 'TagihanController::submitTambahTagihanMedis', ['filter' => 'checkpermission:1337,1,5001']);
    $routes->get('edit/(:any)', 'TagihanController::editTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,5001']);
    $routes->post('submitedit', 'TagihanController::submitEditTagihanMedis', ['filter' => 'checkpermission:1337,1,5001']);
    $routes->post('submitedit/(:segment)', 'TagihanController::submitEditTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,5001']);
    $routes->delete('hapus/(:segment)', 'TagihanController::hapusTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,5001']);
});

$routes->group('stokopnamemedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpnameController::data');
    $routes->get('tambah', 'StokOpnameController::tambah');
    $routes->post('submittambah', 'StokOpnameController::submitTambah');
});
$routes->group('mutasimedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MutasiGudangController::data');
    $routes->get('tambah', 'MutasiGudangController::tambah');
    $routes->post('submittambah', 'MutasiGudangController::submitTambah');
});
$routes->group('batchmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DataBatchController::data');
    $routes->get('tambah', 'DataBatchController::tambah');
    $routes->post('submittambah', 'DataBatchController::submitTambah');
    $routes->get('edit/(:any)', 'DataBatchController::editDataBatch/$1');
    $routes->post('submitedit', 'DataBatchController::submitEdit');
    $routes->delete('hapus/(:segment)', 'DataBatchController::hapus/$1', ['filter' => 'checkpermission:1337,1,4001']);
});
$routes->group('sisastokmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpnameController::sisastok');
});
// Grup untuk Stok Keluar Medis
