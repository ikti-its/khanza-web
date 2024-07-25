<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StokOpnameController extends BaseController
{
     public function data()
     {
          if (session()->has('jwt_token')) {
               $token = session()->get('jwt_token');
               $title = 'Tambah Stok Opname Medis';
               $opname_url = $this->api_url . '/inventory/opname';
               $barang_url = $this->api_url . '/inventory/barang';
               $gudang_url = $this->api_url . '/inventory/gudang';
               $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
               $satuan_url = $this->api_url . '/ref/inventory/satuan';

               $ch_opname = curl_init($opname_url);
               curl_setopt($ch_opname, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_opname, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_opname = curl_exec($ch_opname);
               $http_status_code_opname = curl_getinfo($ch_opname, CURLINFO_HTTP_CODE);
               curl_close($ch_opname);
               $ch_satuan = curl_init($satuan_url);
               curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_satuan = curl_exec($ch_satuan);
               $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
               curl_close($ch_satuan);
               $ch_ruangan = curl_init($ruangan_url);
               curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_ruangan = curl_exec($ch_ruangan);
               $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
               curl_close($ch_ruangan);

               $ch_gudang = curl_init($gudang_url);
               curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_gudang = curl_exec($ch_gudang);
               $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
               curl_close($ch_gudang);

               $ch_barang = curl_init($barang_url);
               curl_setopt($ch_barang, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_barang, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_barang = curl_exec($ch_barang);
               $http_status_code_barang = curl_getinfo($ch_barang, CURLINFO_HTTP_CODE);
               curl_close($ch_barang);

               if ($http_status_code_opname !== 200) {
                    return $this->renderErrorView($http_status_code_opname);
               }
               if ($http_status_code_ruangan !== 201) {
                    return $this->renderErrorView($http_status_code_ruangan);
               }
               if ($http_status_code_satuan !== 201) {
                    return $this->renderErrorView($http_status_code_satuan);
               }
               if ($http_status_code_gudang !== 200) {
                    return $this->renderErrorView($http_status_code_gudang);
               }
               if ($http_status_code_barang !== 200) {
                    return $this->renderErrorView($http_status_code_barang);
               }

               $this->addBreadcrumb('Inventaris', 'inventarismedis');
               $this->addBreadcrumb('Barang Medis', 'medis');
               $this->addBreadcrumb('Stok Opname', 'stokopname');

               $breadcrumbs = $this->getBreadcrumbs();
               $opname_data = json_decode($response_opname, true);
               $ruangan_data = json_decode($response_ruangan, true);
               $satuan_data = json_decode($response_satuan, true);
               $gudang_data = json_decode($response_gudang, true);
               $barang_data = json_decode($response_barang, true);
               return view('/admin/inventaris/medis/stok_opname/data', [
                    'barang_data' => $barang_data['data'],
                    'gudang_data' => $gudang_data['data'],
                    'opname_data' => $opname_data['data'],
                    'ruangan_data' => $ruangan_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
               ]);
          } else {
               return $this->renderErrorView(401);
          }
     }
     public function tambah()
     {
          if (session()->has('jwt_token')) {
               $token = session()->get('jwt_token');
               $title = 'Tambah Stok Opname Medis';
               $barang_url = $this->api_url . '/inventory/barang';
               $gudang_url = $this->api_url . '/inventory/gudang';
               $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
               $satuan_url = $this->api_url . '/ref/inventory/satuan';

               $ch_satuan = curl_init($satuan_url);
               curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_satuan = curl_exec($ch_satuan);
               $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
               curl_close($ch_satuan);
               $ch_ruangan = curl_init($ruangan_url);
               curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_ruangan = curl_exec($ch_ruangan);
               $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
               curl_close($ch_ruangan);

               $ch_gudang = curl_init($gudang_url);
               curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_gudang = curl_exec($ch_gudang);
               $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
               curl_close($ch_gudang);

               $ch_barang = curl_init($barang_url);
               curl_setopt($ch_barang, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_barang, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_barang = curl_exec($ch_barang);
               $http_status_code_barang = curl_getinfo($ch_barang, CURLINFO_HTTP_CODE);
               curl_close($ch_barang);

               if ($http_status_code_ruangan !== 201) {
                    return $this->renderErrorView($http_status_code_ruangan);
               }
               if ($http_status_code_satuan !== 201) {
                    return $this->renderErrorView($http_status_code_satuan);
               }
               if ($http_status_code_gudang !== 200) {
                    return $this->renderErrorView($http_status_code_gudang);
               }
               if ($http_status_code_barang !== 200) {
                    return $this->renderErrorView($http_status_code_barang);
               }

               $this->addBreadcrumb('Inventaris', 'inventarismedis');
               $this->addBreadcrumb('Barang Medis', 'medis');
               $this->addBreadcrumb('Stok Opname', 'stokopname');
               $this->addBreadcrumb('Tambah', 'tambah');

               $breadcrumbs = $this->getBreadcrumbs();
               $ruangan_data = json_decode($response_ruangan, true);
               $satuan_data = json_decode($response_satuan, true);
               $gudang_data = json_decode($response_gudang, true);
               $barang_data = json_decode($response_barang, true);
               return view('/admin/inventaris/medis/stok_opname/tambah_opname', [
                    'barang_data' => $barang_data['data'],
                    'gudang_data' => $gudang_data['data'],
                    'ruangan_data' => $ruangan_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
               ]);
          } else {
               return $this->renderErrorView(401);
          }
     }
     public function submitTambah()
     {
          if (session()->has('jwt_token')) {
               $opname_url = $this->api_url . '/inventory/opname';

               $token = session()->get('jwt_token');
               $tanggal = $this->request->getPost('tanggal');
               $lokasi = intval($this->request->getPost('lokasi'));
               $catatan = $this->request->getPost('catatan');
               $real = $this->request->getPost('real');
               $idbrgmedis = $this->request->getPost('idbrgmedis');
               $harga = $this->request->getPost('harga');
               $stok = $this->request->getPost('stok');
               $nobatch = $this->request->getPost('nobatch');
               $nofaktur = $this->request->getPost('nofaktur');
               for ($i = 0; $i < count($idbrgmedis); $i++) {
                    $postDataOpname = [
                         'tanggal' => $tanggal,
                         'id_ruangan' => $lokasi,
                         'keterangan' => $catatan,
                         'real' => intval($real[$i]),
                         'id_barang_medis' => $idbrgmedis[$i],
                         'h_beli' => intval($harga[$i]),
                         'stok' => intval($stok[$i]),
                         'no_batch' => $nobatch[$i],
                         'no_faktur' => $nofaktur[$i],
                    ];
                    $tambah_opname_JSON = json_encode($postDataOpname);
                    $ch_opname = curl_init($opname_url);
                    curl_setopt($ch_opname, CURLOPT_POST, 1);
                    curl_setopt($ch_opname, CURLOPT_POSTFIELDS, ($tambah_opname_JSON));
                    curl_setopt($ch_opname, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_opname, CURLOPT_HTTPHEADER, [
                         'Content-Type: application/json',
                         'Content-Length: ' . strlen($tambah_opname_JSON),
                         'Authorization: Bearer ' . $token,
                    ]);
                    $response_opname = curl_exec($ch_opname);
                    $http_status_code_opname = curl_getinfo($ch_opname, CURLINFO_HTTP_CODE);
               }
               if ($http_status_code_opname === 201) {
                    for ($j = 0; $j < count($idbrgmedis); $j++) {
                         $gudang_url = $this->api_url . '/inventory/gudang/' . $idbrgmedis[$j] . '/' . $lokasi;

                         $postGudangMedis = [
                              'id_barang_medis' => $idbrgmedis[$j],
                              'id_ruangan' => $lokasi,
                              'stok' => intval($real[$j]),
                              'no_batch' => $nobatch[$j],
                              'no_faktur' => $nofaktur[$j],
                         ];

                         $tambah_gudang_JSON = json_encode($postGudangMedis);

                         // Perform the update
                         $ch_gudang = curl_init($gudang_url);
                         curl_setopt($ch_gudang, CURLOPT_CUSTOMREQUEST, "PUT");
                         curl_setopt($ch_gudang, CURLOPT_POSTFIELDS, $tambah_gudang_JSON);
                         curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                         curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                              'Content-Type: application/json',
                              'Content-Length: ' . strlen($tambah_gudang_JSON),
                              'Authorization: Bearer ' . $token,
                         ]);

                         $response_gudang = curl_exec($ch_gudang);
                         $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);

                         curl_close($ch_gudang);
                    }
                    if ($http_status_code_gudang === 200) {
                         return redirect()->to(base_url('stokopnamemedis'));
                    } else {
                         return $response_gudang . $tambah_gudang_JSON . $tambah_opname_JSON;
                    }
               } else {
                    return $response_opname;
               }
          } else {

               return $this->renderErrorView(401);
          }
     }
     public function sisastok()
     {
          $title = 'Sisa Stok Medis';

          if (session()->has('jwt_token')) {
               $token = session()->get('jwt_token');
               $medis_url = $this->api_url . '/inventory/barang';
               $gudang_url = $this->api_url . '/inventory/gudang';
               $ruangan_url = $this->api_url . '/ref/inventory/ruangan';


               $ch_medis = curl_init($medis_url);
               curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_medis = curl_exec($ch_medis);
               $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
               curl_close($ch_medis);
               $ch_gudang = curl_init($gudang_url);
               curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_gudang = curl_exec($ch_gudang);
               $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
               curl_close($ch_gudang);
               $ch_ruangan = curl_init($ruangan_url);
               curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
               ]);
               $response_ruangan = curl_exec($ch_ruangan);
               $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
               curl_close($ch_ruangan);


               if ($http_status_code_medis !== 200) {
                    return $this->renderErrorView($http_status_code_medis);
               }
               if ($http_status_code_gudang !== 200) {
                    return $this->renderErrorView($http_status_code_gudang);
               }
               if ($http_status_code_ruangan !== 201) {
                    return $this->renderErrorView($http_status_code_ruangan);
               }

               $medis_data = json_decode($response_medis, true);
               $gudang_data = json_decode($response_gudang, true);
               $ruangan_data = json_decode($response_ruangan, true);


               $this->addBreadcrumb('Inventaris', 'inventarismedis');
               $this->addBreadcrumb('Barang Medis', 'medis');
               $this->addBreadcrumb('Sisa stok', 'sisatok');

               $breadcrumbs = $this->getBreadcrumbs();

               return view('/admin/inventaris/medis/sisa_stok', [
                    'medis_data' => $medis_data['data'],
                    'gudang_data' => $gudang_data['data'],
                    'ruangan_data' => $ruangan_data['data'],
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
               ]);
          } else {
               return $this->renderErrorView(401);
          }
     }
}
