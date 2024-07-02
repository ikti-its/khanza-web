<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'authController::index');
$routes->get('/login', 'authController::index');
$routes->get('/logout', 'authController::logout', ['filter' => 'auth']);

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


$routes->get('/datamedis', 'MedisController::dataMedis', ['filter' => 'auth']);
$routes->get('/tambahmedis', 'MedisController::tambahMedis', ['filter' => 'auth']);
$routes->post('/submittambahmedis', 'MedisController::submitTambahMedis', ['filter' => 'auth']);
$routes->get('/editmedis/(:any)', 'MedisController::editMedis/$1', ['filter' => 'auth']);
$routes->post('/submiteditmedis', 'MedisController::submitEditMedis', ['filter' => 'auth']);
$routes->post('/submiteditmedis/(:segment)', 'MedisController::submitEditMedis/$1');
$routes->get('/hapusmedis/(:segment)', 'MedisController::hapusMedis/$1');

$routes->get('/dashboardpengadaanmedis', 'PengajuanController::dashboardPengadaan', ['filter' => 'auth']);

//Persetujuan
$routes->get('/persetujuanpengadaan', 'PersetujuanController::dataPersetujuan', ['filter' => 'auth']);
$routes->post('/submitpersetujuan', 'PersetujuanController::submitTambahPersetujuan', ['filter' => 'auth']);
$routes->post('/submitpersetujuan/(:segment)', 'PersetujuanController::submitTambahPersetujuan/$1');
//Pengajuan
$routes->get('/pengajuanmedis', 'PengajuanController::dataPengajuanMedis', ['filter' => 'auth']);
$routes->get('/tambahpengajuanmedis', 'PengajuanController::tambahPengajuanMedis', ['filter' => 'auth']);
$routes->post('/submittambahpengajuanmedis', 'PengajuanController::submitTambahPengajuanMedis', ['filter' => 'auth']);
$routes->get('/editpengajuanmedis/(:any)', 'PengajuanController::editPengajuanMedis/$1', ['filter' => 'auth']);
$routes->post('/submiteditpengajuanmedis', 'PengajuanController::submitEditPengajuanMedis', ['filter' => 'auth']);
$routes->post('/submiteditpengajuanmedis/(:segment)', 'PengajuanController::submitEditPengajuanMedis/$1');
$routes->get('/hapuspengajuanmedis/(:segment)', 'PengajuanController::hapusPengajuanMedis/$1');

//Pemesanan
$routes->get('/pemesananmedis', 'PemesananController::dataPemesananMedis', ['filter' => 'auth']);
$routes->get('/cetakpemesananbrgmedis/(:segment)', 'PemesananController::cetakPemesananBrgMedis/$1', ['filter' => 'auth']);
$routes->get('/tambahpemesananmedis', 'PemesananController::tambahPemesananMedis', ['filter' => 'auth']);
$routes->get('/tambahpemesananmedis/(:any)', 'PemesananController::tambahPemesananMedisbyId/$1', ['filter' => 'auth']);
$routes->post('/submittambahpemesananmedis', 'PemesananController::submitTambahPemesananMedis', ['filter' => 'auth']);
$routes->get('/editpemesananmedis/(:any)', 'PemesananController::editPemesananMedis/$1', ['filter' => 'auth']);
$routes->post('/submiteditpemesananmedis', 'PemesananController::submitEditPemesananMedis', ['filter' => 'auth']);
$routes->post('/submiteditpemesananmedis/(:segment)', 'PemesananController::submitEditPemesananMedis/$1');
$routes->get('/hapuspemesananmedis/(:segment)', 'PemesananController::hapusPemesananMedis/$1');

//Penerimaan
$routes->get('/penerimaanmedis', 'PenerimaanController::dataPenerimaanMedis', ['filter' => 'auth']);
$routes->get('/tambahpenerimaanmedis', 'PenerimaanController::tambahPenerimaanMedis', ['filter' => 'auth']);
$routes->get('/tambahpenerimaanmedis/(:any)', 'PenerimaanController::tambahPenerimaanMedisbyId/$1', ['filter' => 'auth']);
$routes->post('/submittambahpenerimaanmedis', 'PenerimaanController::submitTambahPenerimaanMedis', ['filter' => 'auth']);
$routes->get('/editpenerimaanmedis/(:any)', 'PenerimaanController::editPenerimaanMedis/$1', ['filter' => 'auth']);
$routes->post('/submiteditpenerimaanmedis', 'PenerimaanController::submitEditPenerimaanMedis', ['filter' => 'auth']);
$routes->post('/submiteditpenerimaanmedis/(:segment)', 'PenerimaanController::submitEditPenerimaanMedis/$1');
$routes->get('/hapuspenerimaanmedis/(:segment)', 'PenerimaanController::hapusPenerimaanMedis/$1');

//Tagihan
$routes->get('/tagihanmedis', 'TagihanController::dataTagihanMedis', ['filter' => 'auth']);
$routes->get('/tambahtagihanmedis', 'TagihanController::tambahTagihanMedis', ['filter' => 'auth']);
$routes->get('/tambahtagihanmedis/(:any)', 'TagihanController::tambahTagihanMedisbyId/$1', ['filter' => 'auth']);
$routes->post('/submittambahtagihanmedis', 'TagihanController::submitTambahTagihanMedis', ['filter' => 'auth']);
$routes->get('/edittagihanmedis/(:any)', 'TagihanController::editTagihanMedis/$1', ['filter' => 'auth']);
$routes->post('/submitedittagihanmedis', 'TagihanController::submitEditTagihanMedis', ['filter' => 'auth']);
$routes->post('/submitedittagihanmedis/(:segment)', 'TagihanController::submitEditTagihanMedis/$1');
$routes->get('/hapustagihanmedis/(:segment)', 'TagihanController::hapusTagihanMedis/$1');

//Stok Keluar
$routes->get('/stokkeluarmedis', 'StokKeluarController::dataStokKeluarMedis', ['filter' => 'auth']);
$routes->get('/tambahstokkeluarmedis', 'StokKeluarController::tambahStokKeluarMedis', ['filter' => 'auth']);
$routes->post('/submittambahstokkeluarmedis', 'StokKeluarController::submitTambahStokKeluarMedis', ['filter' => 'auth']);
$routes->get('/editstokkeluarmedis/(:any)', 'StokKeluarController::editStokKeluarMedis/$1', ['filter' => 'auth']);
$routes->post('/submiteditstokkeluarmedis', 'StokKeluarController::submitEditStokKeluarMedis', ['filter' => 'auth']);
$routes->post('/submiteditstokkeluarmedis/(:segment)', 'StokKeluarController::submitEditStokKeluarMedis/$1');
$routes->get('/hapusstokkeluarmedis/(:segment)', 'StokKeluarController::hapusStokKeluarMedis/$1');
