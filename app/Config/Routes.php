<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'auth::index');
$routes->get('/', 'userPegawai::lihatDashboard', ['filter' => 'auth']);
$routes->get('/login', 'auth::index');
$routes->post('/logout', 'auth::logout', ['filter' => 'auth']);

$routes->get('/dashboard', 'userPegawai::lihatDashboard', ['filter' => 'auth']);
$routes->post('/dashboard', 'auth::login', ['filter' => 'noauth']);
$routes->get('/profile', 'userPegawai::lihatProfil', ['filter' => 'auth']);
$routes->post('/submiteditprofil/(:segment)', 'userPegawai::submitEditProfil/$1', ['filter' => 'auth']);
$routes->get('/datauserpegawai', 'userPegawai::lihatPegawai', ['filter' => 'auth']);
$routes->get('/detailberkaspegawai/(:segment)', 'userPegawai::detailBerkasPegawai/$1', ['filter' => 'auth']);


$routes->get('/admin', 'auth::dashboard', ['filter' => 'auth']);
$routes->post('/admin', 'auth::login', ['filter' => 'noauth']);

//kalo mau akses halaman pake auth, pertama kali pake no auth(contoh di login)
$routes->get('/dataakun', 'akun::dataAkun', ['filter' => 'auth']);

$routes->get('/tambahakun', 'akun::tambahAkun', ['filter' => 'auth']);
$routes->post('/submittambahakun', 'akun::submitTambahAkun', ['filter' => 'auth']);

$routes->get('/editakun/(:any)', 'akun::editAkun/$1', ['filter' => 'auth']);
$routes->post('/submiteditakun', 'akun::submitEditAkun', ['filter' => 'auth']);

$routes->post('/submiteditakun/(:segment)', 'Akun::submitEditAkun/$1');

$routes->get('/hapusakun/(:segment)', 'Akun::hapusAkun/$1');

$routes->get('/datapegawai', 'akun::dataPegawai');


$routes->get('/admin', 'auth::dashboard');

$routes->get('/logout', 'auth::logout');

$routes->get('/datapegawai', 'admin::dataPegawai', ['filter' => 'noauth']);

$routes->get('/signup', 'admin::daftarPegawai');
$routes->get('/editpegawai', 'admin::editPegawai');
$routes->get('/presensipegawai', 'admin::presensiPegawai');
//============================================================================== merge start
$routes->get('/absenmasuk/(:segment)', 'userPegawai::lihatAbsenMasuk/$1', ['filter' => 'checkFotoData']);
$routes->post('/submitTambahAbsenMasuk', 'userPegawai::submitTambahAbsenMasuk', ['filter' => 'auth']);
$routes->post('/submitPresensiSwafoto', 'userPegawai::submitPresensiSwafoto', ['filter' => 'auth']);
$routes->post('/setFaceRecognized', 'faceRecognition::setFaceRecognized');

$routes->get('/tampilkutitugas/(:segment)', 'userPegawai::tampilCuti/$1', ['filter' => 'auth']);
$routes->get('/tampiljadwal/(:segment)', 'userPegawai::tampilJadwal/$1', ['filter' => 'auth']);
$routes->get('/tampiljadwalpenuh', 'userPegawai::tampilJadwalPenuh', ['filter' => 'auth']);

$routes->post('/kirimnotifikasi', 'userAdmin::submitKirimNotifikasi', ['filter' => 'noauth']);
$routes->get('/kirimnotifikasi', 'auth::dashboard', ['filter' => 'auth']);

$routes->get('/izincuti', 'userPegawai::tambahCuti', ['filter' => 'auth']);
$routes->get('/lihatizincuti/(:segment)', 'userPegawai::tampilCuti/$1', ['filter' => 'auth']);
$routes->post('/submittambahcuti', 'userPegawai::submitTambahCuti', ['filter' => 'auth']);

$routes->get('/lihatjadwal/(:segment)', 'userPegawai::tampilJadwal/$1', ['filter' => 'auth']);
$routes->get('/lihatjadwal', 'userPegawai::tampilJadwalPenuh', ['filter' => 'auth']);

$routes->get('/catatankehadiran/(:segment)', 'userPegawai::tampilCatatanKehadiran/$1', ['filter' => 'auth']);
$routes->get('/statusizin', 'userPegawai::tampilStatusIzin', ['filter' => 'auth']);
$routes->add('/presensi', 'userPegawai::tambahPresensi', ['filter' => 'auth']);
$routes->get('/swafoto', 'userPegawai::tambahSwafoto', ['filter' => 'auth']);
$routes->get('/menukehadiran', 'userPegawai::lihatOpsiHadir', ['filter' => 'auth']);

$routes->get('/lihatstatuscuti', 'userAdmin::lihatStatusCuti', ['filter' => 'auth']);
$routes->post('/submiteditstatuscuti/(:segment)', 'userAdmin::submitEditStatusCuti/$1', ['filter' => 'auth']);

$routes->get('/kehadiranmanual', 'userPegawai::LihatAbsen', ['filter' => 'auth']);
$routes->get('/absenmasuk/(:segment)', 'userPegawai::LihatAbsenMasuk/$1', ['filter' => 'checkFotoData']);
$routes->post('/submittambahabsenmasuk', 'userPegawai::submitTambahAbsenMasuk', ['filter' => 'auth']);

$routes->post('/submittambahabsenswafoto', 'userPegawai::submitPresensiSwafoto', ['filter' => 'auth']);

// $routes->post('/absenpulang', 'userPegawai::submitAbsenPulang', ['filter' => 'auth']);
$routes->get('/absenpulang/(:segment)', 'userPegawai::LihatAbsenPulang/$1', ['filter' => 'auth']);
$routes->post('/submittambahabsenpulang', 'userPegawai::submitTambahAbsenPulang', ['filter' => 'auth']);

$routes->get('/admin', 'auth::dashboard', ['filter' => 'auth']);
$routes->post('/admin', 'auth::login', ['filter' => 'noauth']);

$routes->post('/setFaceRecognized', 'FaceRecognition::setFaceRecognized');

$routes->get('/catatankehadiran/(:segment)', 'userPegawai::tampilCatatanKehadiran/$1', ['filter' => 'auth']);
$routes->get('/listizin', 'userPegawai::tampilListIzin', ['filter' => 'auth']);
$routes->get('/presensi', 'userPegawai::tambahPresensi', ['filter' => 'auth']);
$routes->get('/swafoto', 'userPegawai::tambahSwafoto', ['filter' => 'auth']);
$routes->get('/riwayatpresensi', 'userPegawai::lihatRiwayatPresensi', ['filter' => 'auth']);

//============================================================================== merge end
$routes->get('/datapegawai', 'pegawai::dataPegawai', ['filter' => 'auth']);
$routes->get('/datapegawai-test', 'pegawai::dataPegawaiTest',  ['filter' => 'auth']);

$routes->get('/tambahpegawai', 'pegawai::tambahPegawai', ['filter' => 'auth']);
$routes->post('/submittambahpegawai', 'pegawai::submitTambahPegawai', ['filter' => 'auth']);

$routes->get('/editpegawai/(:any)', 'pegawai::editPegawai/$1', ['filter' => 'auth']);
$routes->post('/submiteditpegawai/(:segment)', 'pegawai::submitEditPegawai/$1', ['filter' => 'auth']);

$routes->get('/hapuspegawai/(:segment)', 'pegawai::hapusPegawai/$1', ['filter' => 'auth']);

//==========================================================================================
$routes->get('/presensi', 'presensi::halamanPresensi', ['filter' => 'auth']);
// Route to serve the JavaScript file (loadModel.js)
$routes->get('loadModel.js', 'Presensi::script', ['filter' => 'auth']);


//==========================================================================================
$routes->get('/alamatpegawai', 'alamat::alamatPegawai', ['filter' => 'auth']);
$routes->get('/tambahalamat', 'alamat::tambahAlamat', ['filter' => 'auth']);

$routes->post('/submittambahalamat', 'alamat::submitTambahAlamat', ['filter' => 'auth']);

$routes->get('/editalamat/(:any)', 'alamat::editAlamat/$1', ['filter' => 'auth']);
$routes->post('/submiteditalamat/(:segment)', 'alamat::submitEditAlamat/$1', ['filter' => 'auth']);

$routes->get('/hapusalamat/(:segment)', 'alamat::hapusAlamat/$1', ['filter' => 'auth']);

//===========================================================================================
$routes->get('/berkaspegawai', 'berkas::berkasPegawai', ['filter' => 'auth']);
$routes->get('/tambahberkas', 'berkas::tambahBerkas', ['filter' => 'auth']);

$routes->post('/submittambahberkas', 'berkas::submitTambahBerkas', ['filter' => 'auth']);

$routes->get('/editberkas/(:any)', 'berkas::editBerkas/$1', ['filter' => 'auth']);

$routes->post('/submiteditberkas/(:segment)', 'berkas::submitEditBerkas/$1', ['filter' => 'auth']);
$routes->post('/submiteditktp/(:segment)', 'berkas::submitEditKTP/$1', ['filter' => 'auth']);
$routes->post('/submiteditkk/(:segment)', 'berkas::submitEditKK/$1', ['filter' => 'auth']);
$routes->post('/submiteditnpwp/(:segment)', 'berkas::submitEditNPWP/$1', ['filter' => 'auth']);
$routes->post('/submiteditbpjs/(:segment)', 'berkas::submitEditBPJS/$1', ['filter' => 'auth']);
$routes->post('/submiteditijazah/(:segment)', 'berkas::submitEditIjazah/$1', ['filter' => 'auth']);
$routes->post('/submiteditskck/(:segment)', 'berkas::submitEditSkck/$1', ['filter' => 'auth']);
$routes->post('/submiteditstr/(:segment)', 'berkas::submitEditStr/$1', ['filter' => 'auth']);
$routes->post('/submiteditserkom/(:segment)', 'berkas::submitEditSerkom/$1', ['filter' => 'auth']);

$routes->get('/hapusberkas/(:segment)', 'berkas::hapusBerkas/$1', ['filter' => 'auth']);

$routes->group('', ['namespace' => 'App\s'], function ($routes) {
    $routes->get('error/400', 'ErrorHandler::show400');
    $routes->get('error/401', 'ErrorHandler::show401');
    $routes->get('error/403', 'ErrorHandler::show403');
    $routes->get('error/500', 'ErrorHandler::show500');
});

$routes->get('/error_403', 'Error::noAccess403', ['filter' => 'auth']);









//=============================================================================

$routes->group('datamedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Medis::dataMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Medis::tambahMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'Medis::submitTambahMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'Medis::editMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'Medis::submitEditMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'Medis::submitEditMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'Medis::hapusMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
});

$routes->group('stokkeluarmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokKeluar::dataStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'StokKeluar::tambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submittambah', 'StokKeluar::submitTambahStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('edit/(:any)', 'StokKeluar::editStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit', 'StokKeluar::submitEditStokKeluarMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit/(:segment)', 'StokKeluar::submitEditStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->delete('hapus/(:segment)', 'StokKeluar::hapusStokKeluarMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
});


$routes->get('/dashboardpengadaanmedis', 'Pengajuan::dashboardPengadaan', ['filter' => ['auth', 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']]);

//Persetujuan
$routes->group('persetujuanpengajuan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Persetujuan::dataPersetujuan', ['filter' => 'checkpermission:1337,1,2,4001,4004, 5001']);
    $routes->post('submit', 'Persetujuan::submitTambahPersetujuan', ['filter' => 'checkpermission:1337,1,2,4001,5001']);
    $routes->post('submit/(:segment)', 'Persetujuan::submitTambahPersetujuan/$1', ['filter' => 'checkpermission:1337,1,2,4001,5001']);
});
//Pengajuan
$routes->group('pengajuanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->add('/', 'Pengajuan::dataPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Pengajuan::tambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submittambah', 'Pengajuan::submitTambahPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('edit/(:any)', 'Pengajuan::editPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit', 'Pengajuan::submitEditPengajuanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit/(:segment)', 'Pengajuan::submitEditPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->delete('hapus/(:segment)', 'Pengajuan::hapusPengajuanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
});


//Pemesanan
// Grup untuk Pemesanan Medis
$routes->group('pemesananmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Pemesanan::dataPemesananMedis', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('cetak/(:segment)', 'Pemesanan::cetakPemesananBrgMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('tambah', 'Pemesanan::tambahPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('tambah/(:any)', 'Pemesanan::tambahPemesananMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submittambah', 'Pemesanan::submitTambahPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->get('edit/(:any)', 'Pemesanan::editPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit', 'Pemesanan::submitEditPemesananMedis', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->post('submitedit/(:segment)', 'Pemesanan::submitEditPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
    $routes->delete('hapus/(:segment)', 'Pemesanan::hapusPemesananMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4003']);
});

// Grup untuk Penerimaan Medis
$routes->group('penerimaanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Penerimaan::dataPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']);
    $routes->get('tambah', 'Penerimaan::tambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('tambah/(:any)', 'Penerimaan::tambahPenerimaanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submittambah', 'Penerimaan::submitTambahPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->get('edit/(:any)', 'Penerimaan::editPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit', 'Penerimaan::submitEditPenerimaanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->post('submitedit/(:segment)', 'Penerimaan::submitEditPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
    $routes->delete('hapus/(:segment)', 'Penerimaan::hapusPenerimaanMedis/$1', ['filter' => 'checkpermission:1337,1,2,4001,4004']);
});

// Grup untuk Tagihan Medis
$routes->group('tagihanmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Tagihan::dataTagihanMedis', ['filter' => 'checkpermission:1337,1,2,4001,4002,4003,4004,5001']);
    $routes->get('tambah/(:any)', 'Tagihan::tambahTagihanMedisbyId/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submittambah', 'Tagihan::submitTambahTagihanMedis', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->get('edit/(:any)', 'Tagihan::editTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submitedit', 'Tagihan::submitEditTagihanMedis', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->post('submitedit/(:segment)', 'Tagihan::submitEditTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
    $routes->delete('hapus/(:segment)', 'Tagihan::hapusTagihanMedis/$1', ['filter' => 'checkpermission:1337,1,2,5001']);
});

$routes->group('stokopnamemedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpname::data');
    $routes->get('tambah', 'StokOpname::tambah');
    $routes->post('submittambah', 'StokOpname::submitTambah');
    $routes->delete('hapus/(:segment)', 'StokOpname::hapusOpname/$1');
});
$routes->group('mutasimedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MutasiGudang::data');
    $routes->get('tambah', 'MutasiGudang::tambah');
    $routes->post('submittambah', 'MutasiGudang::submitTambah');
    $routes->delete('hapus/(:segment)', 'MutasiGudang::hapusMutasi/$1');
});
$routes->group('batchmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DataBatch::data');
    $routes->get('tambah', 'DataBatch::tambah');
    $routes->post('submittambah', 'DataBatch::submitTambah');
    $routes->get('edit/(:any)/(:any)/(:any)', 'DataBatch::editDataBatch/$1/$2/$3');
    $routes->post('submitedit/(:any)/(:any)/(:any)', 'DataBatch::submitEdit/$1/$2/$3');
    $routes->delete('hapus/(:any)/(:any)/(:any)', 'DataBatch::hapus/$1/$2/$3');
});
$routes->group('sisastokmedis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokOpname::sisastok');
});
// Grup untuk Stok Keluar Medis

//Registrasi
$routes->group('registrasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Registrasi::dataRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Registrasi::tambahRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'Registrasi::submitTambahRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'Registrasi::editRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'Registrasi::submitEditRegistrasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'Registrasi::submitEditRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'Registrasi::hapusRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('trigger-notif', 'Registrasi::triggerNotif');
    $routes->get('audit', 'Registrasi::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});


//masterpasien/tambah-pasien
$routes->get('masterpasien/tambah-pasien', 'MasterPasien\MasterPasienForm::tampilTambah', [
    'filter' => 'checkpermission:1337,1,2,3'
]);
$routes->post('masterpasien/simpanTambah', 'MasterPasien\MasterPasienForm::simpanTambah');

//masterpasien/ubah-pasien
$routes->get('masterpasien/ubah-pasien/(:segment)', 'MasterPasien\MasterPasienForm::tampilUbah/$1', [
    'filter' => 'checkpermission:1337,1,2,3'
]);

$routes->post('masterpasien/simpanUbah/(:segment)', 'MasterPasien\MasterPasienForm::simpanUbah/$1');


//pasienmeninggal/tambah-pasien
$routes->get('pasienmeninggal/tambah-pasien', 'MasterPasien\PasienMeninggalForm::tampilTambah', [
    'filter' => 'checkpermission:1337,1,2,3'
]);
$routes->post('pasienmeninggal/simpanTambah', 'MasterPasien\PasienMeninggalForm::simpanTambah');

//masterpasien/ubah-pasien
$routes->get('pasienmeninggal/ubah-pasien/(:segment)', 'MasterPasien\PasienMeninggalForm::tampilUbah/$1', [
    'filter' => 'checkpermission:1337,1,2,3'
]);
$routes->post('pasienmeninggal/simpanUbah/(:segment)', 'MasterPasien\PasienMeninggalForm::simpanUbah/$1');



// kelahiranbayi/tambah-pasien
$routes->get('kelahiranbayi/tambah-pasien', 'MasterPasien\KelahiranBayiForm::tampilTambah', [
    'filter' => 'checkpermission:1337,1,2,3'
]);
$routes->post('kelahiranbayi/simpanTambah', 'MasterPasien\KelahiranBayiForm::simpanTambah');

// kelahiranbayi/ubah-pasien
$routes->get('kelahiranbayi/ubah-pasien/(:segment)', 'MasterPasien\KelahiranBayiForm::tampilUbah/$1', [
    'filter' => 'checkpermission:1337,1,2,3'
]);
$routes->post('kelahiranbayi/simpanUbah/(:segment)', 'MasterPasien\KelahiranBayiForm::simpanUbah/$1');



//Kamar
$routes->group('kamar', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Kamar::dataKamar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Kamar::tambahKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'Kamar::submitTambahKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'Kamar::editKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    // $routes->post('submitedit', 'Kamar::submitEditKamar', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'Kamar::submitEditKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'Kamar::hapusKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'Kamar::terimaKamar/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('audit', 'Kamar::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rujukan Masuk
$routes->group('rujukanmasuk', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RujukanMasuk::dataRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RujukanMasuk::tambahRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'RujukanMasuk::submitTambahRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'RujukanMasuk::editRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // $routes->post('submitedit', 'RujukanMasuk::submitEditRujukanMasuk', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'RujukanMasuk::submitEditRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'RujukanMasuk::hapusRujukanMasuk/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('audit', 'RujukanMasuk::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rujukan Keluar
$routes->group('rujukankeluar', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RujukanKeluar::dataRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RujukanKeluar::tambahRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'RujukanKeluar::submitTambahRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'RujukanKeluar::editRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'RujukanKeluar::submitEditRujukanKeluar', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'RujukanKeluar::submitEditRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'RujukanKeluar::hapusRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('cetak/(:segment)', 'RujukanKeluar::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('fromrawatinap/(:segment)', 'RujukanKeluar::submitFromRawatinapToRujukanKeluar/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('audit', 'RujukanKeluar::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Rawat Inap
$routes->group('rawatinap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RawatInap::dataRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'RawatInap::tambahRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('tambah/(:segment)', 'RawatInap::tambahRawatInapBaru/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'RawatInap::submitTambahRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'RawatInap::editRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'RawatInap::submitEditRawatInap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'RawatInap::submitEditRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'RawatInap::hapusRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'RawatInap::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Ambulans
$routes->group('ambulans', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Ambulans::dataAmbulans', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Ambulans::tambahAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'Ambulans::submitTambahAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'Ambulans::editAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'Ambulans::submitEditAmbulans', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'Ambulans::submitEditAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'Ambulans::hapusAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('panggil/(:any)', 'Ambulans::panggilAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'Ambulans::terimaAmbulans/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('audit', 'Ambulans::tampilAudit', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('audit', 'Ambulans::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Unit Gawat Darurat
$routes->group('ugd', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UGD::dataUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'UGD::tambahUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'UGD::submitTambahUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'UGD::editUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'UGD::submitEditUGD', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'UGD::submitEditUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'UGD::hapusUGD/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('trigger-notif', 'UGD::triggerNotif');
    $routes->get('audit', 'UGD::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Tindakan
$routes->group('tindakan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Tindakan::dataTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'Tindakan::tambahTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'Tindakan::tambahTindakan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'Tindakan::submitTambahTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'Tindakan::editTindakan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'Tindakan::submitEditTindakan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)/(:segment)', 'Tindakan::submitEditTindakan/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'Tindakan::hapusTindakan/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'Tindakan::tindakanData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'Tindakan::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tindakan/edit/(:segment)/(:segment)', 'Tindakan::editTindakan/$1/$2');
    $routes->get('submit-ranap/(:segment)', 'Tindakan::submitFromRawatInap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit-registrasi/(:segment)', 'Tindakan::submitFromRegistrasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit-ugd/(:segment)', 'Tindakan::submitFromUGD/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'Tindakan::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Dokter Jaga
$routes->group('dokterjaga', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DokterJaga::dataDokterJaga', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'DokterJaga::tambahDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submittambah', 'DokterJaga::submitTambahDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('edit/(:any)', 'DokterJaga::editDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit', 'DokterJaga::submitEditDokterJaga', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->post('submitedit/(:segment)', 'DokterJaga::submitEditDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->delete('hapus/(:segment)', 'DokterJaga::hapusDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('panggil/(:any)', 'DokterJaga::panggilDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('terima/(:any)', 'DokterJaga::terimaDokterJaga/$1', ['filter' => 'checkpermission:1337,1,2,4001,4002']);
    $routes->get('audit', 'DokterJaga::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Pemberian Obat
$routes->group('pemberianobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemberianObat::dataPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PemberianObat::tambahPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PemberianObat::tambahPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PemberianObat::submitTambahPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PemberianObat::editPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'PemberianObat::submitEditPemberianObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PemberianObat::submitEditPemberianObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'PemberianObat::hapusPemberianObat/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'PemberianObat::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'PemberianObat::PemberianObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PemberianObat::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('pemberianobat/edit/(:segment)/(:segment)', 'PemberianObat::editPemberianObat/$1/$2');
});

// Resep Dokter
$routes->group('resepdokter', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepDokter::dataResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepDokter::tambahResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepDokter::tambahResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepDokter::submitTambahResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepDokter::editResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepDokter::submitEditResepDokter', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepDokter::submitEditResepDokter/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)', 'ResepDokter::hapusResepDokter/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepDokter::ResepDokterData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepDokter::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('resepdokter/edit/(:segment)/(:segment)', 'ResepDokter::editResepDokter/$1/$2');
    $routes->get('audit', 'ResepDokter::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

// Resep Obat
$routes->group('resepobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObat::dataResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObat::tambahResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObat::tambahResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObat::submitTambahResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObat::editResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObat::submitEditResepObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObat::submitEditResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObat::hapusResepObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'ResepObat::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'ResepObat::ResepObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObat::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObat::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('resepobat/tambah/(:segment)', 'ResepObat::tambahResepObatId/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

$routes->group('resepobatracikan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObatRacikan::dataResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObatRacikan::tambahResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObatRacikan::tambahResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObatRacikan::submitTambahResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObatRacikan::editResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObatRacikan::submitEditResepObatRacikan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObatRacikan::submitEditResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObatRacikan::hapusResepObatRacikan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepObatRacikan::ResepObatRacikanData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObatRacikan::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObatRacikan::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'ResepObatRacikan::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

$routes->group('resepobatracikandetail', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepObatRacikanDetail::dataResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepObatRacikanDetail::tambahResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepObatRacikanDetail::tambahResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepObatRacikanDetail::submitTambahResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'ResepObatRacikanDetail::editResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'ResepObatRacikanDetail::submitEditResepObatRacikanDetail', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'ResepObatRacikanDetail::submitEditResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'ResepObatRacikanDetail::hapusResepObatRacikanDetail/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'ResepObatRacikanDetail::ResepObatRacikanDetailData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepObatRacikanDetail::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepObatRacikanDetail::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'ResepObatRacikanDetail::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

// Permintaan Resep Pulang
$routes->group('permintaanreseppulang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PermintaanResepPulang::dataPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PermintaanResepPulang::tambahPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PermintaanResepPulang::tambahPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PermintaanResepPulang::submitTambahPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PermintaanResepPulang::editPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    // $routes->post('submitedit', 'PermintaanResepPulang::submitEditPermintaanResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PermintaanResepPulang::submitEditPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'PermintaanResepPulang::hapusPermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'PermintaanResepPulang::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'PermintaanResepPulang::PermintaanResepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PermintaanResepPulang::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'PermintaanResepPulang::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Resep Pulang
$routes->group('reseppulang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ResepPulang::dataResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'ResepPulang::tambahResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'ResepPulang::tambahResepPulangFromPermintaan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'ResepPulang::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'ResepPulang::submitTambahResepPulang', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:segment)/(:segment)/(:segment)/(:segment)', 'ResepPulang::editResepPulang/$1/$2/$3/$4');
    $routes->post('submitedit/(:segment)/(:segment)/(:segment)/(:segment)', 'ResepPulang::submitEditResepPulang/$1/$2/$3/$4', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)/(:segment)/(:segment)/(:segment)', 'ResepPulang::hapusResepPulang/$1/$2/$3/$4');
    $routes->get('audit', 'ResepPulang::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'ResepPulang::resepPulang/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'ResepPulang::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
});

// Permintaan Stok Obat
$routes->group('permintaanstokobat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PermintaanStokObat::dataPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PermintaanStokObat::tambahPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'PermintaanStokObat::tambahPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'PermintaanStokObat::submitTambahPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'PermintaanStokObat::editPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'PermintaanStokObat::submitEditPermintaanStokObat', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'PermintaanStokObat::submitEditPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'PermintaanStokObat::hapusPermintaanStokObat/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'PermintaanStokObat::permintaanStokObatData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('submit/(:segment)', 'PermintaanStokObat::submitFromRawatinap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'PermintaanStokObat::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'PermintaanStokObat::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

// Stok Obat Pasien
$routes->group('stokobatpasien', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'StokObatPasien::dataStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'StokObatPasien::tambahStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('tambah/(:any)', 'StokObatPasien::tambahStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submittambah', 'StokObatPasien::submitTambahStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('edit/(:any)', 'StokObatPasien::editStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit', 'StokObatPasien::submitEditStokObatPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->post('submitedit/(:segment)', 'StokObatPasien::submitEditStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->delete('hapus/(:segment)', 'StokObatPasien::hapusStokObatPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('(:segment)', 'StokObatPasien::stokObatPasienData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('cetak/(:segment)', 'StokObatPasien::cetak/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'StokObatPasien::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Pemeriksaan Ranap
$routes->group('pemeriksaanranap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PemeriksaanRanap::dataPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,3,4001,4002,4003,4004']);
    $routes->get('by-rawat/(:segment)', 'PemeriksaanRanap::dataPemeriksaanRanapDetail/$1', ['filter' => 'checkpermission:1337,1,3,4001,4002,4003,4004']);
    $routes->get('tambah', 'PemeriksaanRanap::tambahPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('tambah/(:any)', 'PemeriksaanRanap::tambahPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4,4001,4002,4003,4004']);
    $routes->post('submittambah', 'PemeriksaanRanap::submitTambahPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:any)', 'PemeriksaanRanap::editPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'PemeriksaanRanap::submitEditPemeriksaanRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'PemeriksaanRanap::submitEditPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'PemeriksaanRanap::hapusPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('from-rawatinap/(:any)', 'PemeriksaanRanap::submitFromRawatinapToPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,4001,4002']);
    $routes->get('tambah-dari-registrasi/(:segment)', 'PemeriksaanRanap::submitFromRegistrasiToPemeriksaanRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002']);
    $routes->get('audit', 'PemeriksaanRanap::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);

    // $routes->get('(:segment)', 'PemeriksaanRanap::pemeriksaanRanapData/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

// //Rekam Medis Data Pasien
// $routes->group('pasien', ['filter' => 'auth'], function ($routes) {
//     $routes->get('/', 'Pasien::dataPasien', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
//     $routes->get('tambah', 'Pasien::tambahPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->post('submittambah', 'Pasien::submitTambahPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->get('edit/(:any)', 'Pasien::editPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->post('submitedit', 'Pasien::submitEditPasien', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->post('submitedit/(:segment)', 'Pasien::submitEditPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->delete('hapus/(:segment)', 'Pasien::hapusPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->get('rekam-medis/(:segment)', 'Pasien::lihatPasienByRM/$1');
//     // (Opsional) Detail pasien
//     // $routes->get('(:segment)', 'Pasien::detailPasien/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
//     $routes->get('audit', 'Pasien::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
// });

//Catatan Observasi Kebidanan
$routes->group('catatanobservasikebidanan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiKebidanan::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiKebidanan::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiKebidanan::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'CatatanObservasiKebidanan::editCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiKebidanan::submitEditCatatanObservasiKebidanan', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'CatatanObservasiKebidanan::submitEditCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiKebidanan::hapusCatatanObservasiKebidanan/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiKebidanan::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiKebidanan::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('audit', 'CatatanObservasiKebidanan::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Catatan Observasi Ranap
$routes->group('catatanobservasiranap', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiRanap::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiRanap::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiRanap::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)/(:segment)',    'CatatanObservasiRanap::editCatatanObservasiRanap/$1/$2',    ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiRanap::submitEditCatatanObservasiRanap', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)/(:segment)', 'CatatanObservasiRanap::submitEditCatatanObservasiRanap/$1/$2', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiRanap::hapusCatatanObservasiRanap/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiRanap::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiRanap::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('audit', 'CatatanObservasiRanap::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Catatan Observasi Post Partum
$routes->group('catatanobservasipostpartum', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CatatanObservasiPostpartum::dataCatatanObservasi', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'CatatanObservasiPostpartum::tambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'CatatanObservasiPostpartum::submitTambahCatatanObservasi', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'CatatanObservasiPostpartum::editCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'CatatanObservasiPostpartum::submitEditCatatanObservasiPostpartum', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'CatatanObservasiPostpartum::submitEditCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'CatatanObservasiPostpartum::hapusCatatanObservasiPostpartum/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'CatatanObservasiPostpartum::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('(:segment)', 'CatatanObservasiPostpartum::lihatCatatanObservasiByNoRawat/$1', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('audit', 'CatatanObservasiPostpartum::tampilAudit', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
});

//Diagnosa
$routes->group('diagnosa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Diagnosa::dataDiagnosa', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
    $routes->get('tambah', 'Diagnosa::tambahDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submittambah', 'Diagnosa::submitTambahDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('edit/(:segment)', 'Diagnosa::editDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit', 'Diagnosa::submitEditDiagnosa', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->post('submitedit/(:segment)', 'Diagnosa::submitEditDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->delete('hapus/(:segment)', 'Diagnosa::hapusDiagnosa/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    // Fitur untuk prefill dari rawatinap
    $routes->get('from-rawatinap/(:segment)', 'Diagnosa::submitFromRawatinapToCatatanObservasi/$1', ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004']);
    $routes->get('audit', 'Diagnosa::tampilAudit', ['filter' => 'checkpermission:1337,1,4001,4002,4003,4004']);
});

//rekam-medis
$routes->get('rekam-medis/(:segment)', 'RekamMedis\RekamMedis::detail/$1');


//Modal Routes
$routes->get('/modalpasien/list', 'Modal\ModalPasien::listPasien');
$routes->get('/modalinstansi/list', 'Modal\ModalInstansi::listInstansi');
$routes->get('/modaldokter/list', 'Modal\ModalDokter::listDokter');
$routes->get('/modaldokterjaga/list', 'Modal\ModalDokterJaga::listDokterJaga');
$routes->get('/modalasuransi/list', 'Modal\ModalAsuransi::listAsuransi');

//Fitur Penggajian 
$fiturs = [
    ['AturanPenggajian\\', 'aturan-penggajian/', [
        ['BPJS', 'bpjs'],
        ['Golongan', 'golongan'],
        ['Jabatan', 'jabatan'],
        ['PTKP', 'ptkp'],
        ['PPH21', 'pph21'],
        ['Lembur', 'lembur'],
        ['UMR', 'umr'],
        ['THR', 'thr'],
        ['Pesangon', 'pesangon'],
        ['UPMK', 'upmk']
    ]],
    ['DataPenggajian\\', 'data-penggajian/', [
        ['Kepegawaian', 'kepegawaian'],
        ['Penggajian', 'penggajian'],
        ['THR', 'thr'],
        ['PHK', 'phk']
    ]],
    ['MasterPasien\\', '', [
        ['MasterPasien', 'masterpasien'],
        ['Instansi', 'instansi'],
        ['PasienMeninggal', 'pasienmeninggal'],
        ['KelahiranBayi', 'kelahiranbayi'],
    ]],
    ['Dokter\\', '', [
        ['Dokter', 'dokter'],
    ]],
    ['Asuransi\\', '', [
        ['Asuransi', 'asuransi'],
    ]],
    ['RekamMedis\\', '', [
        ['RekamMedis', 'rekam-medis'],
    ]],
];
$filter = ['filter' => 'checkpermission:1337,1,2,3,4001,4002,4003,4004'];
foreach ($fiturs as $fitur) {
    $folder  = $fitur[0];
    $prefiks = $fitur[1];
    $moduls  = $fitur[2];
    foreach ($moduls as $modul) {
        $m = $folder . $modul[0];
        $routes->group($prefiks . $modul[1], ['filter' => 'auth'], function ($routes) use ($m, $filter) {
            $routes->get('/',                      $m . '::tampilData', $filter); //  ojok diubah din, iki wes rapi //sepurane iki vscode ku onok auto rapi ne wkwk
            $routes->get('audit',                  $m . '::tampilAudit', $filter);
            $routes->get('tambah',                 $m . '::tampilTambah', $filter);
            $routes->post('submittambah',          $m . '::simpanTambah', $filter);
            $routes->get('edit/(:segment)',        $m . '::tampilUbah/$1', $filter);
            $routes->post('submitedit/(:segment)', $m . '::simpanUbah/$1', $filter);
            $routes->delete('hapus/(:segment)',    $m . '::hapusData/$1', $filter);
        });
    }
}
