<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MutasiGudangController extends BaseController
{
    public function data()
    {
        return view('/admin/inventaris/medis/mutasi_gudang/data');
    }
    public function tambah()
    {

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Mutasi Antar Gudang Medis';
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

            $breadcrumbs = $this->getBreadcrumbs();
            $ruangan_data = json_decode($response_ruangan, true);
            $satuan_data = json_decode($response_satuan, true);
            $gudang_data = json_decode($response_gudang, true);
            $barang_data = json_decode($response_barang, true);
            return view('/admin/inventaris/medis/mutasi_gudang/tambah_mutasi', [
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
            $mutasi_url = $this->api_url . '/inventory/mutasi';

            $token = session()->get('jwt_token');

            $tanggal = $this->request->getPost('tanggal');

            $asallokasi = $this->request->getPost('asallokasi');
            $tujuanlokasi = $this->request->getPost('tujuanlokasi');
            $catatan = $this->request->getPost('catatan');
            $jumlah = $this->request->getPost('jumlah');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $harga = $this->request->getPost('harga');
            $nobatch = $this->request->getPost('nobatch');
            $nofaktur = $this->request->getPost('nofaktur');


            $all_mutasi_success = true;
            for ($i = 0; $i < count($idbrgmedis); $i++) {
                $postDataMutasi = [
                    'id_barang_medis' => $idbrgmedis[$i],
                    'jumlah' => intval($jumlah[$i]),
                    'harga' => intval($harga[$i]),
                    'id_ruangandari' => intval($asallokasi),
                    'id_ruanganke' => intval($tujuanlokasi),
                    'tanggal' => $tanggal,
                    'keterangan' => $catatan
                ];
                $tambah_mutasi_JSON = json_encode($postDataMutasi);
                $ch_mutasi = curl_init($mutasi_url);
                curl_setopt($ch_mutasi, CURLOPT_POST, 1);
                curl_setopt($ch_mutasi, CURLOPT_POSTFIELDS, ($tambah_mutasi_JSON));
                curl_setopt($ch_mutasi, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_mutasi, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_mutasi_JSON),
                    'Authorization: Bearer ' . $token,
                ]);
                $response_mutasi = curl_exec($ch_mutasi);
                $http_status_code_mutasi = curl_getinfo($ch_mutasi, CURLINFO_HTTP_CODE);
                if ($http_status_code_mutasi !== 201) {
                    $all_mutasi_success = false;
                    break;
                }
            }

            if ($all_mutasi_success) {
                $all_gudang_success = true;
                for ($j = 0; $j < count($idbrgmedis); $j++) {
                    $gudang_url = $this->api_url . '/inventory/gudang/' . $idbrgmedis[$j] . '/' . $asallokasi;
                    $tujuangudang_url = $this->api_url . '/inventory/gudang/' . $idbrgmedis[$j] . '/' . $tujuanlokasi;
                    $ch_gudang = curl_init($gudang_url);
                    curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);
                    $response_gudang = curl_exec($ch_gudang);
                    $gudang_data = json_decode($response_gudang, true);
                    $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
                    curl_close($ch_gudang);

                    if ($http_status_code_gudang !== 201 || !isset($gudang_data['data']['stok'])) {
                        $all_gudang_success = false;
                        break;
                    }

                    $postAsalGudangMedis = [
                        'id_barang_medis' => $idbrgmedis[$j],
                        'id_ruangan' => $asallokasi,
                        'stok' => $gudang_data['data']['stok'] - intval($jumlah[$j]),
                        'no_batch' => $nobatch[$j],
                        'no_faktur' => $nofaktur[$j],
                    ];
                    $tambah_asalgudang_JSON = json_encode($postAsalGudangMedis);

                    $ch_asalgudang = curl_init($gudang_url);
                    curl_setopt($ch_asalgudang, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch_asalgudang, CURLOPT_POSTFIELDS, $tambah_asalgudang_JSON);
                    curl_setopt($ch_asalgudang, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_asalgudang, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($tambah_asalgudang_JSON),
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_asalgudang = curl_exec($ch_asalgudang);
                    $http_status_code_asalgudang = curl_getinfo($ch_asalgudang, CURLINFO_HTTP_CODE);
                    curl_close($ch_asalgudang);

                    if ($http_status_code_asalgudang !== 201) {
                        $all_gudang_success = false;
                        break;
                    }

                    $postTujuanGudangMedis = [
                        'id_barang_medis' => $idbrgmedis[$j],
                        'id_ruangan' => $tujuanlokasi,
                        'stok' => $gudang_data['data']['stok'] + intval($jumlah[$j]),
                        'no_batch' => $nobatch[$j],
                        'no_faktur' => $nofaktur[$j],
                    ];
                    $tambah_tujuangudang_JSON = json_encode($postTujuanGudangMedis);

                    $ch_tujuangudang = curl_init($tujuangudang_url);
                    curl_setopt($ch_tujuangudang, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch_tujuangudang, CURLOPT_POSTFIELDS, $tambah_tujuangudang_JSON);
                    curl_setopt($ch_tujuangudang, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_tujuangudang, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($tambah_tujuangudang_JSON),
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_tujuangudang = curl_exec($ch_tujuangudang);
                    $http_status_code_tujuangudang = curl_getinfo($ch_tujuangudang, CURLINFO_HTTP_CODE);
                    curl_close($ch_tujuangudang);

                    if ($http_status_code_tujuangudang !== 201) {
                        $all_gudang_success = false;
                        break;
                    }
                }

                if ($all_gudang_success) {
                    return redirect()->to(base_url('stokopnamemedis'));
                } else {
                    return $response_asalgudang . $response_tujuangudang . $response_mutasi;
                }
            } else {
                return $response_mutasi . $tambah_mutasi_JSON;
            }
        } else {

            return $this->renderErrorView(401);
        }
    }
}
