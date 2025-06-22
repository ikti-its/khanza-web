<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'authController::index');
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

$routes->get('/datapegawai', 'akunController::dataPegawai');


$routes->get('/admin', 'authController::dashboard');

$routes->get('/logout', 'authController::logout');

$routes->get('/datapegawai', 'adminController::dataPegawai', ['filter' => 'noauth']);

$routes->get('/signup', 'adminController::daftarPegawai');
$routes->get('/editpegawai', 'adminController::editPegawai');
$routes->get('/presensipegawai', 'adminController::presensiPegawai');
//============================================================================== merge start
$routes->get('/absenmasuk/(:segment)', 'userPegawaiController::lihatAbsenMasuk/$1', ['filter' => 'checkFotoData']);
$routes->post('/submitTambahAbsenMasuk', 'userPegawaiController::submitTambahAbsenMasuk', ['filter' => 'auth']);
$routes->post('/submitPresensiSwafoto', 'userPegawaiController::submitPresensiSwafoto', ['filter' => 'auth']);
$routes->post('/setFaceRecognized', 'faceRecognitionController::setFaceRecognized');

$routes->get('/tampilkutitugas/(:segment)', 'userPegawaiController::tampilCuti/$1', ['filter' => 'auth']);
$routes->get('/tampiljadwal/(:segment)', 'userPegawaiController::tampilJadwal/$1', ['filter' => 'auth']);
$routes->get('/tampiljadwalpenuh', 'userPegawaiController::tampilJadwalPenuh', ['filter' => 'auth']);

$routes->post('/kirimnotifikasi', 'userAdminController::submitKirimNotifikasi', ['filter' => 'noauth']);
$routes->get('/kirimnotifikasi', 'authController::dashboard', ['filter' => 'auth']);

$routes->get('/izincuti', 'userPegawaiController::tambahCuti', ['filter' => 'auth']);
$routes->get('/lihatizincuti/(:segment)', 'userPegawaiController::tampilCuti/$1', ['filter' => 'auth']);
$routes->post('/submittambahcuti', 'userPegawaiController::submitTambahCuti', ['filter' => 'auth']);

$routes->get('/lihatjadwal/(:segment)', 'userPegawaiController::tampilJadwal/$1', ['filter' => 'auth']);
$routes->get('/lihatjadwal', 'userPegawaiController::tampilJadwalPenuh', ['filter' => 'auth']);

$routes->get('/catatankehadiran/(:segment)', 'userPegawaiController::tampilCatatanKehadiran/$1', ['filter' => 'auth']);
$routes->get('/statusizin', 'userPegawaiController::tampilStatusIzin', ['filter' => 'auth']);
$routes->add('/presensi', 'userPegawaiController::tambahPresensi', ['filter' => 'auth']);
$routes->get('/swafoto', 'userPegawaiController::tambahSwafoto', ['filter' => 'auth']);
$routes->get('/menukehadiran', 'userPegawaiController::lihatOpsiHadir', ['filter' => 'auth']);

$routes->get('/lihatstatuscuti', 'userAdminController::lihatStatusCuti', ['filter' => 'auth']);
$routes->post('/submiteditstatuscuti/(:segment)', 'userAdminController::submitEditStatusCuti/$1', ['filter' => 'auth']);

$routes->get('/kehadiranmanual', 'userPegawaiController::LihatAbsen', ['filter' => 'auth']);
$routes->get('/absenmasuk/(:segment)', 'userPegawaiController::LihatAbsenMasuk/$1', ['filter' => 'checkFotoData']);
$routes->post('/submittambahabsenmasuk', 'userPegawaiController::submitTambahAbsenMasuk', ['filter' => 'auth']);

$routes->post('/submittambahabsenswafoto', 'userPegawaiController::submitPresensiSwafoto', ['filter' => 'auth']);

// $routes->post('/absenpulang', 'userPegawaiController::submitAbsenPulang', ['filter' => 'auth']);
$routes->get('/absenpulang/(:segment)', 'userPegawaiController::LihatAbsenPulang/$1', ['filter' => 'auth']);
$routes->post('/submittambahabsenpulang', 'userPegawaiController::submitTambahAbsenPulang', ['filter' => 'auth']);

$routes->get('/admin', 'authController::dashboard', ['filter' => 'auth']);
$routes->post('/admin', 'authController::login', ['filter' => 'noauth']);

$routes->post('/setFaceRecognized', 'FaceRecognition::setFaceRecognized');

$routes->get('/catatankehadiran/(:segment)', 'userPegawaiController::tampilCatatanKehadiran/$1', ['filter' => 'auth']);
$routes->get('/listizin', 'userPegawaiController::tampilListIzin', ['filter' => 'auth']);
$routes->get('/presensi', 'userPegawaiController::tambahPresensi', ['filter' => 'auth']);
$routes->get('/swafoto', 'userPegawaiController::tambahSwafoto', ['filter' => 'auth']);
$routes->get('/riwayatpresensi', 'userPegawaiController::lihatRiwayatPresensi', ['filter' => 'auth']);

//============================================================================== merge end
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

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('error/400', 'ErrorHandler::show400');
    $routes->get('error/401', 'ErrorHandler::show401');
    $routes->get('error/403', 'ErrorHandler::show403');
    $routes->get('error/500', 'ErrorHandler::show500');
});

$routes->get('/error_403', 'ErrorController::noAccess403', ['filter' => 'auth']);









//=============================================================================

$routes->group('datamedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MedisController::dataMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'MedisController::tambahMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'MedisController::submitTambahMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'MedisController::editMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'MedisController::submitEditMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'MedisController::submitEditMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'MedisController::hapusMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
});

$routes->group('stokkeluarmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokKeluarController::dataStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'StokKeluarController::tambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submittambah', 'StokKeluarController::submitTambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('edit/(:any)', 'StokKeluarController::editStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit', 'StokKeluarController::submitEditStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit/(:segment)', 'StokKeluarController::submitEditStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->delete('hapus/(:segment)', 'StokKeluarController::hapusStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
});


$routes->get('/dashboardpengadaanmedis', 'PengajuanController::dashboardPengadaan', ['filter' => ['auth', 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']]);

//Persetujuan
$routes->group('persetujuanpengajuan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PersetujuanController::dataPersetujuan', ['filter' => 'checkpermission:1337,1,2,4001,4004, 5001']);
    $routes->post('submit', 'PersetujuanController::submitTambahPersetujuan', ['filter' => 'checkpermission:1337,1,2,4001,5001']);
    $routes->post('submit/(:segment)', 'PersetujuanController::submitTambahPersetujuan/$1', ['filter' => 'checkpermission:1337,1,2,4001,5001']);
});
//Pengajuan
$routes->group('pengajuanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->add('/', 'PengajuanController::dataPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PengajuanController::tambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submittambah', 'PengajuanController::submitTambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('edit/(:any)', 'PengajuanController::editPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit', 'PengajuanController::submitEditPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit/(:segment)', 'PengajuanController::submitEditPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->delete('hapus/(:segment)', 'PengajuanController::hapusPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
});


//Pemesanan
// Grup untuk Pemesanan Medis
$routes->group('pemesananmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemesananController::dataPemesananMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('cetak/(:segment)', 'PemesananController::cetakPemesananBrgMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('tambah', 'PemesananController::tambahPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('tambah/(:any)', 'PemesananController::tambahPemesananMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submittambah', 'PemesananController::submitTambahPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('edit/(:any)', 'PemesananController::editPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit', 'PemesananController::submitEditPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit/(:segment)', 'PemesananController::submitEditPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->delete('hapus/(:segment)', 'PemesananController::hapusPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
});

// Grup untuk Penerimaan Medis
$routes->group('penerimaanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PenerimaanController::dataPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']);
    $routes->get('tambah', 'PenerimaanController::tambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('tambah/(:any)', 'PenerimaanController::tambahPenerimaanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submittambah', 'PenerimaanController::submitTambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('edit/(:any)', 'PenerimaanController::editPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit', 'PenerimaanController::submitEditPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit/(:segment)', 'PenerimaanController::submitEditPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->delete('hapus/(:segment)', 'PenerimaanController::hapusPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
});

// Grup untuk Tagihan Medis
$routes->group('tagihanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TagihanController::dataTagihanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']);
    $routes->get('tambah/(:any)', 'TagihanController::tambahTagihanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submittambah', 'TagihanController::submitTambahTagihanMedis', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->get('edit/(:any)', 'TagihanController::editTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submitedit', 'TagihanController::submitEditTagihanMedis', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submitedit/(:segment)', 'TagihanController::submitEditTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->delete('hapus/(:segment)', 'TagihanController::hapusTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
});

$routes->group('stokopnamemedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpnameController::data');
    $routes->get('tambah', 'StokOpnameController::tambah');
    $routes->post('submittambah', 'StokOpnameController::submitTambah');
    $routes->delete('hapus/(:segment)', 'StokOpnameController::hapusOpname/$1');
});
$routes->group('mutasimedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MutasiGudangController::data');
    $routes->get('tambah', 'MutasiGudangController::tambah');
    $routes->post('submittambah', 'MutasiGudangController::submitTambah');
    $routes->delete('hapus/(:segment)', 'MutasiGudangController::hapusMutasi/$1');
});
$routes->group('batchmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DataBatchController::data');
    $routes->get('tambah', 'DataBatchController::tambah');
    $routes->post('submittambah', 'DataBatchController::submitTambah');
    $routes->get('edit/(:any)/(:any)/(:any)', 'DataBatchController::editDataBatch/$1/$2/$3');
    $routes->post('submitedit/(:any)/(:any)/(:any)', 'DataBatchController::submitEdit/$1/$2/$3');
    $routes->delete('hapus/(:any)/(:any)/(:any)', 'DataBatchController::hapus/$1/$2/$3');
});
$routes->group('sisastokmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpnameController::sisastok');
});
// Grup untuk Stok Keluar Medis

//Registrasi
$routes->group('registrasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RegistrasiController::dataRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RegistrasiController::tambahRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'RegistrasiController::submitTambahRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'RegistrasiController::editRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'RegistrasiController::submitEditRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'RegistrasiController::submitEditRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'RegistrasiController::hapusRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('trigger-notif', 'RegistrasiController::triggerNotif');
});

//Kamar
$routes->group('kamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KamarController::dataKamar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'KamarController::tambahKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'KamarController::submitTambahKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'KamarController::editKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('submitedit', 'KamarController::submitEditKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'KamarController::submitEditKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'KamarController::hapusKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'KamarController::terimaKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
});

//Rujukan Masuk
$routes->group('rujukanmasuk', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RujukanMasukController::dataRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RujukanMasukController::tambahRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'RujukanMasukController::submitTambahRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'RujukanMasukController::editRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit', 'RujukanMasukController::submitEditRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'RujukanMasukController::submitEditRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'RujukanMasukController::hapusRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rujukan Keluar
$routes->group('rujukankeluar', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RujukanKeluarController::dataRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RujukanKeluarController::tambahRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'RujukanKeluarController::submitTambahRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'RujukanKeluarController::editRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'RujukanKeluarController::submitEditRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'RujukanKeluarController::submitEditRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'RujukanKeluarController::hapusRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('cetak/(:segment)', 'RujukanKeluarController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('fromrawatinap/(:segment)', 'RujukanKeluarController::submitFromRawatinapToRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rawat Inap
$routes->group('rawatinap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RawatInapController::dataRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RawatInapController::tambahRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('tambah/(:segment)', 'RawatInapController::tambahRawatInapBaru/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'RawatInapController::submitTambahRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'RawatInapController::editRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'RawatInapController::submitEditRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'RawatInapController::submitEditRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'RawatInapController::hapusRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

//Ambulans
$routes->group('ambulans', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AmbulansController::dataAmbulans', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'AmbulansController::tambahAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'AmbulansController::submitTambahAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'AmbulansController::editAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'AmbulansController::submitEditAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'AmbulansController::submitEditAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'AmbulansController::hapusAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('panggil/(:any)', 'AmbulansController::panggilAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'AmbulansController::terimaAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
});

//Unit Gawat Darurat
$routes->group('ugd', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UGDController::dataUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'UGDController::tambahUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'UGDController::submitTambahUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'UGDController::editUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'UGDController::submitEditUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'UGDController::submitEditUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'UGDController::hapusUGD/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('trigger-notif', 'UGDController::triggerNotif');
});

//Tindakan
$routes->group('tindakan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TindakanController::dataTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'TindakanController::tambahTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'TindakanController::tambahTindakan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'TindakanController::submitTambahTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'TindakanController::editTindakan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'TindakanController::submitEditTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)/(:segment)', 'TindakanController::submitEditTindakan/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'TindakanController::hapusTindakan/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'TindakanController::tindakanData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'TindakanController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tindakan/edit/(:segment)/(:segment)', 'TindakanController::editTindakan/$1/$2');
    $routes->get('submit-ranap/(:segment)', 'TindakanController::submitFromRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit-registrasi/(:segment)', 'TindakanController::submitFromRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit-ugd/(:segment)', 'TindakanController::submitFromUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

//Dokter Jaga
$routes->group('dokterjaga', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DokterJagaController::dataDokterJaga', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'DokterJagaController::tambahDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'DokterJagaController::submitTambahDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'DokterJagaController::editDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'DokterJagaController::submitEditDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'DokterJagaController::submitEditDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'DokterJagaController::hapusDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('panggil/(:any)', 'DokterJagaController::panggilDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'DokterJagaController::terimaDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
});

//Pemberian Obat
$routes->group('pemberianobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemberianObatController::dataPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PemberianObatController::tambahPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PemberianObatController::tambahPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PemberianObatController::submitTambahPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PemberianObatController::editPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'PemberianObatController::submitEditPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PemberianObatController::submitEditPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'PemberianObatController::hapusPemberianObat/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'PemberianObatController::PemberianObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PemberianObatController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('pemberianobat/edit/(:segment)/(:segment)', 'PemberianObatController::editPemberianObat/$1/$2');

});

// Resep Dokter
$routes->group('resepdokter', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepDokterController::dataResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepDokterController::tambahResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepDokterController::tambahResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepDokterController::submitTambahResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepDokterController::editResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepDokterController::submitEditResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepDokterController::submitEditResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'ResepDokterController::hapusResepDokter/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepDokterController::ResepDokterData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepDokterController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('resepdokter/edit/(:segment)/(:segment)', 'ResepDokterController::editResepDokter/$1/$2');
});

// Resep Obat
$routes->group('resepobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObatController::dataResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObatController::tambahResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObatController::tambahResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObatController::submitTambahResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObatController::editResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObatController::submitEditResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObatController::submitEditResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObatController::hapusResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepObatController::ResepObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObatController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObatController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('resepobat/tambah/(:segment)', 'ResepObatController::tambahResepObatId/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

$routes->group('resepobatracikan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObatRacikanController::dataResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObatRacikanController::tambahResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObatRacikanController::tambahResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObatRacikanController::submitTambahResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObatRacikanController::editResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObatRacikanController::submitEditResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObatRacikanController::submitEditResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObatRacikanController::hapusResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepObatRacikanController::ResepObatRacikanData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObatRacikanController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObatRacikanController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

$routes->group('resepobatracikandetail', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObatRacikanDetailController::dataResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObatRacikanDetailController::tambahResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObatRacikanDetailController::tambahResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObatRacikanDetailController::submitTambahResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObatRacikanDetailController::editResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObatRacikanDetailController::submitEditResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObatRacikanDetailController::submitEditResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObatRacikanDetailController::hapusResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepObatRacikanDetailController::ResepObatRacikanDetailData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObatRacikanDetailController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObatRacikanDetailController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Permintaan Resep Pulang
$routes->group('permintaanreseppulang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PermintaanResepPulangController::dataPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PermintaanResepPulangController::tambahPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PermintaanResepPulangController::tambahPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PermintaanResepPulangController::submitTambahPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PermintaanResepPulangController::editPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'PermintaanResepPulangController::submitEditPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PermintaanResepPulangController::submitEditPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'PermintaanResepPulangController::hapusPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'PermintaanResepPulangController::PermintaanResepPulangData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PermintaanResepPulangController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'PermintaanResepPulangController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Resep Pulang
$routes->group('reseppulang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepPulangController::dataResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepPulangController::tambahResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepPulangController::tambahResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepPulangController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepPulangController::submitTambahResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:segment)/(:segment)/(:segment)/(:segment)', 'ResepPulangController::editResepPulang/$1/$2/$3/$4');
    $routes->post('submitedit/(:segment)', 'ResepPulangController::submitEditResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)/(:segment)/(:segment)', 'ResepPulangController::hapusResepPulang/$1/$2/$3/$4');
    $routes->get('(:segment)', 'ResepPulangController::resepPulangData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepPulangController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Permintaan Stok Obat
$routes->group('permintaanstokobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PermintaanStokObatController::dataPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PermintaanStokObatController::tambahPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PermintaanStokObatController::tambahPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PermintaanStokObatController::submitTambahPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PermintaanStokObatController::editPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'PermintaanStokObatController::submitEditPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PermintaanStokObatController::submitEditPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'PermintaanStokObatController::hapusPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'PermintaanStokObatController::permintaanStokObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PermintaanStokObatController::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'PermintaanStokObatController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Stok Obat Pasien
$routes->group('stokobatpasien', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokObatPasienController::dataStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'StokObatPasienController::tambahStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'StokObatPasienController::tambahStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'StokObatPasienController::submitTambahStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'StokObatPasienController::editStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'StokObatPasienController::submitEditStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'StokObatPasienController::submitEditStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'StokObatPasienController::hapusStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'StokObatPasienController::stokObatPasienData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'StokObatPasienController::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

//Pemeriksaan Ranap
$routes->group('pemeriksaanranap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemeriksaanRanapController::dataPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('by-rawat/(:segment)', 'PemeriksaanRanapController::dataPemeriksaanRanapDetail/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'PemeriksaanRanapController::tambahPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah/(:any)', 'PemeriksaanRanapController::tambahPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'PemeriksaanRanapController::submitTambahPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'PemeriksaanRanapController::editPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'PemeriksaanRanapController::submitEditPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'PemeriksaanRanapController::submitEditPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'PemeriksaanRanapController::hapusPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('from-rawatinap/(:any)', 'PemeriksaanRanapController::submitFromRawatinapToPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->get('tambah-dari-registrasi/(:segment)', 'PemeriksaanRanapController::submitFromRegistrasiToPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);

    // $routes->get('(:segment)', 'PemeriksaanRanapController::pemeriksaanRanapData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rekam Medis Data Pasien
$routes->group('pasien', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PasienController::dataPasien', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'PasienController::tambahPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'PasienController::submitTambahPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'PasienController::editPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'PasienController::submitEditPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'PasienController::submitEditPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'PasienController::hapusPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('rekam-medis/(:segment)', 'PasienController::lihatPasienByRM/$1');
    // (Opsional) Detail pasien
    // $routes->get('(:segment)', 'PasienController::detailPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Catatan Observasi Kebidanan
$routes->group('catatanobservasikebidanan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiKebidananController::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiKebidananController::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiKebidananController::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'CatatanObservasiKebidananController::editCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiKebidananController::submitEditCatatanObservasiKebidanan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'CatatanObservasiKebidananController::submitEditCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiKebidananController::hapusCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiKebidananController::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiKebidananController::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
});

//Catatan Observasi Ranap
$routes->group('catatanobservasiranap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiRanapController::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiRanapController::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiRanapController::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get(    'edit/(:segment)/(:segment)',    'CatatanObservasiRanapController::editCatatanObservasiRanap/$1/$2',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiRanapController::submitEditCatatanObservasiRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)/(:segment)', 'CatatanObservasiRanapController::submitEditCatatanObservasiRanap/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiRanapController::hapusCatatanObservasiRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiRanapController::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiRanapController::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
});

//Catatan Observasi Post Partum
$routes->group('catatanobservasipostpartum', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiPostpartumController::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiPostpartumController::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiPostpartumController::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'CatatanObservasiPostpartumController::editCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiPostpartumController::submitEditCatatanObservasiPostpartum', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'CatatanObservasiPostpartumController::submitEditCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiPostpartumController::hapusCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiPostpartumController::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiPostpartumController::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);

});

//Diagnosa
$routes->group('diagnosa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DiagnosaController::dataDiagnosa', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'DiagnosaController::tambahDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'DiagnosaController::submitTambahDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'DiagnosaController::editDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'DiagnosaController::submitEditDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'DiagnosaController::submitEditDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'DiagnosaController::hapusDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'DiagnosaController::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//BPJS
$routes->group('bpjs', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'BPJSController::data',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});

//Golongan
$routes->group('golongan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'Golongan::tampilData',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});

//Jabatan
$routes->group('jabatan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'Jabatan::tampilData',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});

//PTKP
$routes->group('ptkp', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'PTKP::tampilData',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});

$routes->group('pph21', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'PPH21::tampilData',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});

$routes->group('lembur', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'Lembur::tampilData',          ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('tambah',                 'BPJSController::tambah',        ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submittambah',          'BPJSController::submitTambah',  ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->get('edit/(:any)',            'BPJSController::edit/$1',       ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit',            'BPJSController::submitEdit',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit/(:segment)', 'BPJSController::submitEdit/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->delete('hapus/(:segment)',    'BPJSController::hapusUGD/$1',   ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('trigger-notif',         'BPJSController::triggerNotif');
});
