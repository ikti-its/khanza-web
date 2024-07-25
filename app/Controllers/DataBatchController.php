<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DataBatchController extends BaseController
{
    public function data()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Data Batch Medis';
            $batch_url = $this->api_url . '/inventory/batch';
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

            $ch_batch = curl_init($batch_url);
            curl_setopt($ch_batch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_batch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_batch = curl_exec($ch_batch);
            $http_status_code_batch = curl_getinfo($ch_batch, CURLINFO_HTTP_CODE);
            curl_close($ch_batch);

            if ($http_status_code_ruangan !== 201) {
                return $this->renderErrorView($http_status_code_ruangan);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_gudang !== 200) {
                return $this->renderErrorView($http_status_code_gudang);
            }
            if ($http_status_code_batch !== 200) {
                return $this->renderErrorView($http_status_code_batch);
            }
            if ($http_status_code_barang !== 200) {
                return $this->renderErrorView($http_status_code_barang);
            }

            $this->addBreadcrumb('Inventaris', 'inventarismedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Data Batch', 'databatch');

            $breadcrumbs = $this->getBreadcrumbs();
            $ruangan_data = json_decode($response_ruangan, true);
            $satuan_data = json_decode($response_satuan, true);
            $gudang_data = json_decode($response_gudang, true);
            $batch_data = json_decode($response_batch, true);
            $barang_data = json_decode($response_barang, true);
            return view('/admin/inventaris/medis/data_batch/data', [
                'batch_data' => $batch_data['data'],
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

    public function tambah()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Stok Opname Medis';
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
            $this->addBreadcrumb('Data Batch', 'databatch');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();
            $ruangan_data = json_decode($response_ruangan, true);
            $satuan_data = json_decode($response_satuan, true);
            $gudang_data = json_decode($response_gudang, true);
            $barang_data = json_decode($response_barang, true);
            return view('/admin/inventaris/medis/data_batch/tambah_batch', [
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
            $batch_url = $this->api_url . '/inventory/batch';

            $token = session()->get('jwt_token');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $nobatch = $this->request->getPost('nobatch');
            $asal = $this->request->getPost('asal');
            $tgldatang = $this->request->getPost('tgldatang');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            if ($kadaluwarsa === "") {
                $kadaluwarsaformat = "0001-01-01";
            } else {
                $kadaluwarsaformat = $kadaluwarsa;
            }
            $nofaktur = $this->request->getPost('nofaktur');
            $jumlah = $this->request->getPost('jumlah');
            $sisa = $this->request->getPost('sisa');
            $hargadasar = $this->request->getPost('hargadasar');
            $hargabeli = $this->request->getPost('hargabeli');
            $hargaralan = $this->request->getPost('hargaralan');
            $hargakelas1 = $this->request->getPost('hargakelas1');
            $hargakelas2 = $this->request->getPost('hargakelas2');
            $hargakelas3 = $this->request->getPost('hargakelas3');
            $hargavip = $this->request->getPost('hargavip');
            $hargavvip = $this->request->getPost('hargavvip');
            $hargautama = $this->request->getPost('hargautama');
            $hargaapotekluar = $this->request->getPost('hargaapotekluar');
            $hargaobatbebas = $this->request->getPost('hargaobatbebas');
            $hargakaryawan = $this->request->getPost('hargakaryawan');
            $postDataBatch = [
                'no_batch' => $nobatch,
                'no_faktur' => $nofaktur,
                'id_barang_medis' => $idbrgmedis,
                'tanggal_datang' => $tgldatang,
                'kadaluwarsa' => $kadaluwarsaformat,
                'asal' => $asal,
                'h_dasar' => intval($hargadasar),
                'h_beli' => intval($hargabeli),
                'h_ralan' => intval($hargaralan),
                'h_kelas1' => intval($hargakelas1),
                'h_kelas2' => intval($hargakelas2),
                'h_kelas3' => intval($hargakelas3),
                'h_utama' => intval($hargautama),
                'h_vip' => intval($hargavip),
                'h_vvip' => intval($hargavvip),
                'h_beliluar' => intval($hargaapotekluar),
                'h_jualbebas' => intval($hargaobatbebas),
                'h_karyawan' => intval($hargakaryawan),
                'jumlah_beli' => intval($jumlah),
                'sisa' => intval($sisa),
            ];
            $tambah_batch_JSON = json_encode($postDataBatch);
            $ch_batch = curl_init($batch_url);
            curl_setopt($ch_batch, CURLOPT_POST, 1);
            curl_setopt($ch_batch, CURLOPT_POSTFIELDS, ($tambah_batch_JSON));
            curl_setopt($ch_batch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_batch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($tambah_batch_JSON),
                'Authorization: Bearer ' . $token,
            ]);
            $response_batch = curl_exec($ch_batch);
            $http_status_code_batch = curl_getinfo($ch_batch, CURLINFO_HTTP_CODE);
            if ($http_status_code_batch === 201) {

                return redirect()->to(base_url('batchmedis'));
            } else {
                return $response_batch;
            }
        } else {
            return $this->renderErrorView(401);
        }
    }
    public function editDataBatch($batchid)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Edit Batch Medis';
            $batch_url = $this->api_url . '/inventory/batch/' . $batchid;
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

            $ch_batch = curl_init($batch_url);
            curl_setopt($ch_batch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_batch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_batch = curl_exec($ch_batch);
            $http_status_code_batch = curl_getinfo($ch_batch, CURLINFO_HTTP_CODE);
            curl_close($ch_batch);

            if ($http_status_code_ruangan !== 201) {
                return $this->renderErrorView($http_status_code_ruangan);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_gudang !== 200) {
                return $this->renderErrorView($http_status_code_gudang);
            }
            if ($http_status_code_batch !== 200) {
                return $this->renderErrorView($http_status_code_batch);
            }
            if ($http_status_code_barang !== 200) {
                return $this->renderErrorView($http_status_code_barang);
            }

            $this->addBreadcrumb('Inventaris', 'inventarismedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Data Batch', 'databatch');
            $this->addBreadcrumb('Edit', 'edit');

            $breadcrumbs = $this->getBreadcrumbs();
            $ruangan_data = json_decode($response_ruangan, true);
            $satuan_data = json_decode($response_satuan, true);
            $gudang_data = json_decode($response_gudang, true);
            $batch_data = json_decode($response_batch, true);
            $barang_data = json_decode($response_barang, true);
            return view('/admin/inventaris/medis/data_batch/edit_batch', [
                'batch_data' => $batch_data['data'],
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
    public function submitEdit()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $nobatch = $this->request->getPost('nobatch');
            $asal = $this->request->getPost('asal');
            $tgldatang = $this->request->getPost('tgldatang');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            $nofaktur = $this->request->getPost('nofaktur');
            $jumlah = $this->request->getPost('jumlah');
            $sisa = $this->request->getPost('sisa');
            $hargadasar = $this->request->getPost('hargadasar');
            $hargabeli = $this->request->getPost('hargabeli');
            $hargaralan = $this->request->getPost('hargaralan');
            $hargakelas2 = $this->request->getPost('hargakelas2');
            $hargakelas3 = $this->request->getPost('hargakelas3');
            $hargautama = $this->request->getPost('hargautama');
            $hargaapotekluar = $this->request->getPost('hargaapotekluar');
            $hargaobatbebas = $this->request->getPost('hargaobatbebas');
            $hargakaryawan = $this->request->getPost('hargakaryawan');
        } else {
            return $this->renderErrorView(401);
        }
    }
}
