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
$routes->get('/datauserpegawai', 'userPegawaiController::lihatPegawai', ['filter' => 'ijin']);
$routes->get('/detailberkaspegawai/(:segment)', 'userPegawaiController::detailBerkasPegawai/$1', ['filter' => 'auth']);

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
$routes->get('/tesmenukehadiran', 'userPegawaiController::lihatOpsiHadir', ['filter' => 'auth']);

$routes->get('/lihatstatuscuti', 'userAdminController::lihatStatusCuti', ['filter' => 'auth']);
$routes->post('/submiteditstatuscuti/(:segment)', 'userAdminController::submitEditStatusCuti/$1', ['filter' => 'auth']);

$routes->get('/kehadiranmanual', 'userPegawaiController::LihatAbsen', ['filter' => 'auth']);
$routes->get('/absenmasuk/(:segment)', 'userPegawaiController::LihatAbsenMasuk/$1', ['filter' => 'auth']);
$routes->post('/submittambahabsenmasuk', 'userPegawaiController::submitTambahAbsenMasuk', ['filter' => 'auth']);

$routes->post('/submittambahabsenswafoto', 'userPegawaiController::submitPresensiSwafoto', ['filter' => 'auth']);

// $routes->post('/absenpulang', 'userPegawaiController::submitAbsenPulang', ['filter' => 'auth']);
$routes->get('/absenpulang/(:segment)', 'userPegawaiController::LihatAbsenPulang/$1', ['filter' => 'auth']);
$routes->post('/submittambahabsenpulang', 'userPegawaiController::submitTambahAbsenPulang', ['filter' => 'auth']);

$routes->get('/admin', 'authController::dashboard', ['filter' => 'auth']);
$routes->post('/admin', 'authController::login', ['filter' => 'noauth']);


// $routes->set404Override('App\Controllers\ErrorController::show404');

// $routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
//     $routes->get('error/400', 'ErrorHandler::show400');
//     $routes->get('error/401', 'ErrorHandler::show401');
//     $routes->get('error/403', 'ErrorHandler::show403');
//     $routes->get('error/500', 'ErrorHandler::show500');
// });

// $routes->get('test-400', 'ErrorHandler::show400');
// $routes->get('test-401', 'ErrorHandler::show401');
$routes->get('test-403', 'ErrorHandler::show403');
// $routes->get('test-404', 'ErrorHandler::show404');
// $routes->get('test-500', 'ErrorHandler::show500');
