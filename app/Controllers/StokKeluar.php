<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StokKeluar extends BaseController
{
    public function dataStokKeluarMedis()
    {
        $title = 'Stok Keluar Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $stok_keluar_url = $this->api_url . '/inventory/stok';
            $transaksi_keluar_url = $this->api_url . '/inventory/transaksi';
            $medis_url = $this->api_url . '/inventory/barang';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $pegawai_url = $this->api_url . '/pegawai';

            $ch_stok_keluar = curl_init($stok_keluar_url);
            curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_transaksi_keluar = curl_init($transaksi_keluar_url);
            curl_setopt($ch_transaksi_keluar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_transaksi_keluar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_stok_keluar = curl_exec($ch_stok_keluar);
            $response_transaksi_keluar = curl_exec($ch_transaksi_keluar);
            $response_medis = curl_exec($ch_medis);
            $response_satuan = curl_exec($ch_satuan);
            $response_ruangan = curl_exec($ch_ruangan);
            $response_pegawai = curl_exec($ch_pegawai);

            if ($response_stok_keluar && $response_transaksi_keluar && $response_medis && $response_satuan && $response_pegawai) {
                $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);
                $http_status_code_transaksi_keluar = curl_getinfo($ch_transaksi_keluar, CURLINFO_HTTP_CODE);
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);

                if ($http_status_code_stok_keluar === 200 && $http_status_code_transaksi_keluar === 200 && $http_status_code_medis === 200 && $http_status_code_satuan === 201 && $http_status_code_pegawai === 200) {
                    $stok_keluar_medis_data = json_decode($response_stok_keluar, true);
                    $transaksi_keluar_data = json_decode($response_transaksi_keluar, true);
                    $medis_data = json_decode($response_medis, true);
                    $satuan_data = json_decode($response_satuan, true);
                    $ruangan_data = json_decode($response_ruangan, true);
                    $pegawai_data = json_decode($response_pegawai, true);

                    $this->addBreadcrumb('Inventaris', 'inventarismedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Stok Keluar', 'stokkeluarmedis');
                    $breadcrumbs = $this->getBreadcrumbs();

                    return view('/admin/inventaris/medis/stok_keluar/data_stok_keluar', [
                        'stok_keluar_medis_data' => $stok_keluar_medis_data['data'],
                        'transaksi_keluar_data' => $transaksi_keluar_data['data'],
                        'medis_data' => $medis_data['data'],
                        'satuan_data' => $satuan_data['data'],
                        'ruangan_data' => $ruangan_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    return "Error fetching data. HTTP Status Codes: Stok Keluar ($http_status_code_stok_keluar), Transaksi Keluar ($http_status_code_transaksi_keluar), Medis ($http_status_code_medis), Satuan ($http_status_code_satuan), Pegawai ($http_status_code_pegawai)";
                }
            } else {
                return "Error fetching data.";
            }
            curl_close($ch_stok_keluar);
            curl_close($ch_transaksi_keluar);
            curl_close($ch_medis);
            curl_close($ch_satuan);
            curl_close($ch_pegawai);
        } else {
            return $this->renderErrorView(401);
        }
    }



    public function tambahStokKeluarMedis()
    {
        $title = 'Tambah Stok Keluar Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventory/barang';
            $gudang_url = $this->api_url . '/inventory/gudang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $pegawai_url = $this->api_url . '/pegawai';

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_gudang = curl_init($gudang_url);
            curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_medis = curl_exec($ch_medis);
            $response_ruangan = curl_exec($ch_ruangan);
            $response_satuan = curl_exec($ch_satuan);
            $response_gudang = curl_exec($ch_gudang);
            $response_pegawai = curl_exec($ch_pegawai);

            if ($response_medis && $response_pegawai && $response_ruangan) {
                $medis_data = json_decode($response_medis, true);
                $ruangan_data = json_decode($response_ruangan, true);
                $satuan_data = json_decode($response_satuan, true);
                $gudang_data = json_decode($response_gudang, true);
                $pegawai_data = json_decode($response_pegawai, true);

                $this->addBreadcrumb('Inventaris', 'inventarismedis');
                $this->addBreadcrumb('Barang Medis', 'medis');
                $this->addBreadcrumb('Stok Keluar', 'stokkeluarmedis');
                $this->addBreadcrumb('Tambah', 'tambahstokkeluarmedis');

                $breadcrumbs = $this->getBreadcrumbs();
                return view('/admin/inventaris/medis/stok_keluar/tambah_stok_keluar', [
                    'medis_data' => $medis_data['data'],
                    'ruangan_data' => $ruangan_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'gudang_data' => $gudang_data['data'],
                    'pegawai_data' => $pegawai_data['data'],
                    'token' => $token,
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
                ]);
            } else {
                return "Error fetching data. Response medis: $response_medis, Response pegawai: $response_pegawai";
            }
            curl_close($ch_medis);
            curl_close($ch_ruangan);
            curl_close($ch_pegawai);
        } else {
            return $this->renderErrorView(401);
        }
    }
    public function submitTambahStokKeluarMedis()
    {
        if ($this->request->getPost()) {
            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $nofaktur = $this->request->getPost('nofaktur');
                $nokeluar = $this->request->getPost('nokeluar');
                $pegawaistokkeluar = $this->request->getPost('pegawaistokkeluar');
                $tglkeluar = $this->request->getPost('tglkeluar');
                $keteranganstokkeluar = $this->request->getPost('keteranganstokkeluar');
                $asalruangan = intval($this->request->getPost('asalruangan'));
                $idbrgmedis = $this->request->getPost('idbrgmedis');
                $nobatch = $this->request->getPost('nobatch');
                $nofaktur = $this->request->getPost('nofaktur');
                $jlhkeluar = $this->request->getPost('jlhkeluar');

                $stok_keluar_url = $this->api_url . '/inventory/stok';
                $transaksi_brgmedis_url = $this->api_url . '/inventory/transaksi';

                $postDataStokKeluar = [
                    'no_keluar' => $nokeluar,
                    'id_pegawai' => $pegawaistokkeluar,
                    'tanggal_stok_keluar' => $tglkeluar,
                    'id_ruangan' => $asalruangan,
                    'keterangan' => $keteranganstokkeluar,
                ];

                $tambah_stok_keluar_JSON = json_encode($postDataStokKeluar);


                $ch_stok_keluar = curl_init($stok_keluar_url);

                curl_setopt($ch_stok_keluar, CURLOPT_POST, 1);
                curl_setopt($ch_stok_keluar, CURLOPT_POSTFIELDS, ($tambah_stok_keluar_JSON));
                curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_stok_keluar_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_stok_keluar = curl_exec($ch_stok_keluar);

                if ($response_stok_keluar) {
                    $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);
                    if ($http_status_code_stok_keluar === 201) {

                        $decode_id_stok_keluar = json_decode($response_stok_keluar, true);
                        for ($i = 0; $i < count($idbrgmedis); $i++) {
                            $idstokkeluar = $decode_id_stok_keluar['data']['id'];
                            $postDataTransaksi = [
                                'id_stok_keluar' => $idstokkeluar,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'no_batch' => $nobatch[$i],
                                'no_faktur' => $nofaktur[$i],
                                'jumlah_keluar' => intval($jlhkeluar[$i]),
                            ];

                            $tambah_transaksi_brgmedis_JSON = json_encode($postDataTransaksi);
                            $ch_transaksi_brgmedis = curl_init($transaksi_brgmedis_url);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_CUSTOMREQUEST, "POST");
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_POSTFIELDS, $tambah_transaksi_brgmedis_JSON);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($tambah_transaksi_brgmedis_JSON),
                                'Authorization: Bearer ' . $token,
                            ]);
                            $response = curl_exec($ch_transaksi_brgmedis);
                        }

                        if ($response) {
                            $http_status_code_transaksi_brgmedis = curl_getinfo($ch_transaksi_brgmedis, CURLINFO_HTTP_CODE);
                            if ($http_status_code_transaksi_brgmedis === 201) {
                                for ($j = 0; $j < count($idbrgmedis); $j++) {
                                    $gudang_url = $this->api_url . '/inventory/gudang/barang/' . $idbrgmedis[$j];
                                    $ch_gudang = curl_init($gudang_url);
                                    curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                                        'Authorization: Bearer ' . $token,
                                    ]);
                                    $response_gudang = curl_exec($ch_gudang);
                                    $gudang_data = json_decode($response_gudang, true);
                                    $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
                                    curl_close($ch_gudang);

                                    $gudang = $gudang_data['data'];
                                    foreach ($gudang as $gdg) {
                                        $gudang_update_url = $this->api_url . '/inventory/gudang/' . $gdg['id'];
                                        if ($gdg['id_ruangan'] === $asalruangan) {
                                            $postGudangMedis = [
                                                'id_barang_medis' => $idbrgmedis[$j],
                                                'id_ruangan' => $asalruangan,
                                                'stok' => $gdg['stok'] - intval($jlhkeluar[$j]),
                                                'no_batch' => $nobatch[$j],
                                                'no_faktur' => $nofaktur[$j],
                                            ];

                                            $tambah_gudang_JSON = json_encode($postGudangMedis);

                                            $ch_gudang_update = curl_init($gudang_update_url);
                                            curl_setopt($ch_gudang_update, CURLOPT_CUSTOMREQUEST, "PUT");
                                            curl_setopt($ch_gudang_update, CURLOPT_POSTFIELDS, $tambah_gudang_JSON);
                                            curl_setopt($ch_gudang_update, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch_gudang_update, CURLOPT_HTTPHEADER, [
                                                'Content-Type: application/json',
                                                'Content-Length: ' . strlen($tambah_gudang_JSON),
                                                'Authorization: Bearer ' . $token,
                                            ]);

                                            $response_gudang_update = curl_exec($ch_gudang_update);
                                            $http_status_code_gudang_update = curl_getinfo($ch_gudang_update, CURLINFO_HTTP_CODE);

                                            curl_close($ch_gudang_update);
                                        }
                                    }
                                }
                                if ($http_status_code_gudang_update === 200) {
                                    return redirect()->to(base_url('stokkeluarmedis'));
                                } else {
                                    return $response_gudang_update . $tambah_gudang_JSON;
                                }
                            } else {
                                curl_close($ch_transaksi_brgmedis);
                                return "Error Barang Stok Keluar: " . $response;
                            }
                        } else {
                            curl_close($ch_transaksi_brgmedis);
                            return "Error sending request to the transaksi barang medis API.";
                        }
                    } else {
                        curl_close($ch_stok_keluar);
                        return "Error Insert Stok Keluar: " . $response_stok_keluar . $tambah_stok_keluar_JSON;
                    }
                } else {
                    return "Error sending request to the API.";
                }
            } else {
                return $this->renderErrorView(401);
            }
        } else {
            return "Data is required.";
        }
    }

    public function editStokKeluarMedis($stokKeluarId)
    {
        if (session()->has('jwt_token')) {
            $title = "Edit Stok Keluar Medis";
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventory/barang';
            $gudang_url = $this->api_url . '/inventory/gudang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';

            $pegawai_url = $this->api_url . '/pegawai';

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_gudang = curl_init($gudang_url);
            curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_medis = curl_exec($ch_medis);
            $response_ruangan = curl_exec($ch_ruangan);
            $response_satuan = curl_exec($ch_satuan);
            $response_gudang = curl_exec($ch_gudang);
            $response_pegawai = curl_exec($ch_pegawai);
            if ($response_medis && $response_pegawai && $response_ruangan) {
                $medis_data = json_decode($response_medis, true);
                $ruangan_data = json_decode($response_ruangan, true);
                $satuan_data = json_decode($response_satuan, true);
                $gudang_data = json_decode($response_gudang, true);

                $pegawai_data = json_decode($response_pegawai, true);

                $this->addBreadcrumb('Inventaris', 'inventarismedis');
                $this->addBreadcrumb('Barang Medis', 'medis');
                $this->addBreadcrumb('Stok Keluar', 'stokkeluarmedis');
                $this->addBreadcrumb('Tambah', 'tambahstokkeluarmedis');

                $breadcrumbs = $this->getBreadcrumbs();
                return view('/admin/inventaris/medis/stok_keluar/edit_stok_keluar', [
                    'medis_data' => $medis_data['data'],
                    'ruangan_data' => $ruangan_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'gudang_data' => $gudang_data['data'],
                    'pegawai_data' => $pegawai_data['data'],
                    'token' => $token,
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
                ]);
            } else {
                return "Error fetching data. Response medis: $response_medis, Response pegawai: $response_pegawai";
            }
            curl_close($ch_medis);
            curl_close($ch_ruangan);
            curl_close($ch_pegawai);
        } else {
            return $this->renderErrorView(401);
        }
    }




    public function submitEditStokKeluarMedis($stok_keluarId)
    {
        if ($this->request->getPost()) {
            $idbrgmedis = $this->request->getPost('idbrgmedis');

            $idstokkeluar = $this->request->getPost('idstokkeluar');
            $nokeluar = $this->request->getPost('nokeluar');
            $pegawaitagihan = $this->request->getPost('pegawaistokkeluar');
            $tglkeluar = $this->request->getPost('tglkeluar');
            $asalruangan = $this->request->getPost('asalruangan');
            $tujuanruangan = $this->request->getPost('tujuanruangan');
            $keteranganstokkeluar = $this->request->getPost('keteranganstokkeluar');

            $idtransaksibrgmedis = $this->request->getPost('idtransaksibrgmedis');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $nobatch = $this->request->getPost('nobatch');
            $nofaktur = $this->request->getPost('nofaktur');
            $jlhkeluar = $this->request->getPost('jlhkeluar');

            $stok_keluar_url = $this->api_url . '/inventaris/stok/' . $stok_keluarId;

            $postDataStokKkeluar = [
                'no_keluar' => $nokeluar,
                'id_pegawai' => $pegawaitagihan,
                'tanggal_stok_keluar' => $tglkeluar,
                'asal_ruangan' => intval($asalruangan),
                'tujuan_ruangan' => intval($tujuanruangan),
                'keterangan' => $keteranganstokkeluar,
            ];
            $edit_stok_keluar_JSON = json_encode($postDataStokKkeluar);

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_stok_keluar = curl_init($stok_keluar_url);

                curl_setopt($ch_stok_keluar, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_stok_keluar, CURLOPT_POSTFIELDS, $edit_stok_keluar_JSON);
                curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_stok_keluar_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_stok_keluar = curl_exec($ch_stok_keluar);

                if ($response_stok_keluar) {
                    $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);
                    if ($http_status_code_stok_keluar === 200) {
                        for ($i = 0; $i < count($idtransaksibrgmedis); $i++) {
                            $transaksi_brgmedis_url = $this->api_url . '/inventaris/transaksi/' . $idtransaksibrgmedis[$i];
                            $postDataTransaksiBrgmedis = [
                                'id_stok_keluar' => $idstokkeluar,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'no_batch' => $nobatch[$i],
                                'no_faktur' => $nofaktur[$i],
                                'jumlah_keluar' => intval($jlhkeluar[$i]),
                            ];
                            $edit_transaksi_brgmedis_JSON = json_encode($postDataTransaksiBrgmedis);
                            $ch_transaksi_brgmedis = curl_init($transaksi_brgmedis_url);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_POSTFIELDS, $edit_transaksi_brgmedis_JSON);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_transaksi_brgmedis, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($edit_transaksi_brgmedis_JSON),
                                'Authorization: Bearer ' . $token,
                            ]);

                            $response = curl_exec($ch_transaksi_brgmedis);
                        }

                        if ($response) {
                            $http_status_code_transaksi_brgmedis = curl_getinfo($ch_transaksi_brgmedis, CURLINFO_HTTP_CODE);
                            if ($http_status_code_transaksi_brgmedis === 200) {
                                return redirect()->to(base_url('stokkeluarmedis'));
                            } else {
                                curl_close($ch_transaksi_brgmedis);
                                return "Error Update Transaksi Brgmedis: " . $response;
                            }
                        } else {
                            curl_close($ch_transaksi_brgmedis);
                            return "Error sending request to the transaksi brgmedis API.";
                        }
                        curl_close($ch_stok_keluar);
                        curl_close($ch_transaksi_brgmedis);
                    } else {
                        curl_close($ch_stok_keluar);
                        return "Error Update StokKkeluar: " . $response_stok_keluar;
                    }
                } else {
                    return "Error sending request to the API.";
                }
            } else {
                return "User not logged in. Please log in first.";
            }
        } else {
            return "Data is required.";
        }
    }

    public function hapusStokKeluarMedis($stokKeluarId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $stokUrl = $this->api_url . '/inventory/stok/' . $stokKeluarId;
            $transaksiUrl = $this->api_url . '/inventory/transaksi';

            $chTransaksi = curl_init($transaksiUrl);
            curl_setopt($chTransaksi, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chTransaksi, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $responseTransaksi = curl_exec($chTransaksi);
            $httpStatusCodeTransaksi = curl_getinfo($chTransaksi, CURLINFO_HTTP_CODE);
            curl_close($chTransaksi);

            if ($httpStatusCodeTransaksi !== 200) {
                return "Error fetching transaksi data: " . $responseTransaksi;
            }
            $transaksiData = json_decode($responseTransaksi, true);
            $tr = $transaksiData['data'];

            $chStok = curl_init($stokUrl);
            curl_setopt($chStok, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chStok, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $responseStok = curl_exec($chStok);
            $httpStatusCodeStok = curl_getinfo($chStok, CURLINFO_HTTP_CODE);
            curl_close($chStok);

            if ($httpStatusCodeStok !== 200) {
                return $responseStok;
            }
            $stokData = json_decode($responseStok, true);

            foreach ($tr as $transaksi) {
                if ($transaksi['id_stok_keluar'] === $stokKeluarId) {
                    $gudangUrl = $this->api_url . '/inventory/gudang/barang/' . $transaksi['id_barang_medis'];

                    $chGudang = curl_init($gudangUrl);
                    curl_setopt($chGudang, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chGudang, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);
                    $responseGudang = curl_exec($chGudang);
                    $gudangData = json_decode($responseGudang, true);
                    $httpStatusCodeGudang = curl_getinfo($chGudang, CURLINFO_HTTP_CODE);
                    curl_close($chGudang);

                    if ($httpStatusCodeGudang !== 200) {
                        return "Error fetching gudang data: " . $responseGudang;
                    }
                    $gudang_items = $gudangData['data'];
                    foreach ($gudang_items as $gudang) {
                        if ($gudang['id_ruangan'] === $stokData['data']['id_ruangan']) {
                            $gudangUrl = $this->api_url . '/inventory/gudang/' . $gudang['id'];
                            $postGudangMedis = [
                                'id_barang_medis' => $transaksi['id_barang_medis'],
                                'id_ruangan' => $stokData['data']['id_ruangan'],
                                'stok' => $gudang['stok'] + $transaksi['jumlah_keluar'],
                                'no_batch' => $gudang['no_batch'],
                                'no_faktur' => $gudang['no_faktur'],
                            ];

                            $tambahGudangJSON = json_encode($postGudangMedis);

                            $chGudangUpdate = curl_init($gudangUrl);
                            curl_setopt($chGudangUpdate, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($chGudangUpdate, CURLOPT_POSTFIELDS, $tambahGudangJSON);
                            curl_setopt($chGudangUpdate, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($chGudangUpdate, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($tambahGudangJSON),
                                'Authorization: Bearer ' . $token,
                            ]);

                            $responseGudangUpdate = curl_exec($chGudangUpdate);
                            $httpStatusCodeGudangUpdate = curl_getinfo($chGudangUpdate, CURLINFO_HTTP_CODE);
                            curl_close($chGudangUpdate);

                            if ($httpStatusCodeGudangUpdate !== 200) {
                                return "Error updating gudang: " . $responseGudangUpdate;
                            }
                        }
                    }
                    $deleteTransaksiUrl = $this->api_url . '/inventory/transaksi/' . $transaksi['id'];
                    $chTransaksiDelete = curl_init($deleteTransaksiUrl);
                    curl_setopt($chTransaksiDelete, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($chTransaksiDelete, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chTransaksiDelete, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $responseDeleteTransaksi = curl_exec($chTransaksiDelete);
                    $httpStatusCodeDeleteTransaksi = curl_getinfo($chTransaksiDelete, CURLINFO_HTTP_CODE);
                    curl_close($chTransaksiDelete);

                    if ($httpStatusCodeDeleteTransaksi !== 204) {
                        return "Error deleting transaksi: " . $responseDeleteTransaksi;
                    }
                }
            }
            $chStokDelete = curl_init($stokUrl);
            curl_setopt($chStokDelete, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($chStokDelete, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chStokDelete, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $responseStokDelete = curl_exec($chStokDelete);
            $httpStatusCodeStokDelete = curl_getinfo($chStokDelete, CURLINFO_HTTP_CODE);
            curl_close($chStokDelete);

            if ($httpStatusCodeStokDelete === 204) {
                return redirect()->to(base_url('stokkeluarmedis'));
            } else {
                return "Error deleting stok_keluar: " . $responseStokDelete;
            }
        }

        return $this->renderErrorView(401);
    }
}


    // $gudang_url = $this->api_url . '/inventory/gudang/' . $medisId;

    // $ch_gudang = curl_init($gudang_url);
    // curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
    //     'Authorization: Bearer ' . $token,
    // ]);
    // $response_gudang = curl_exec($ch_gudang);
    // $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
    // curl_close($ch_gudang);
    // $gudang_data=json_decode($response_gudang, true);
    // foreach($gudang_data as $gudang){

    // }
    // $ch_gudang = curl_init($gudang_url);
    // curl_setopt($ch_gudang, CURLOPT_CUSTOMREQUEST, "DELETE");
    // curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
    //     'Authorization: Bearer ' . $token,
    // ]);

    // $response_gudang = curl_exec($ch_gudang);
    // $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
    // curl_close($ch_gudang);




//     {
//         // Check if the user is logged in
//         if (session()->has('jwt_token')) {
//             // Retrieve the stored JWT token
//             $token = session()->get('jwt_token');
//             $stok_keluar_url = $this->api_url . '/inventory/stok/' . $stok_keluarId;
//             $transaksi_url = $this->api_url . '/inventory/transaksi/stok/' . $stok_keluarId;

//             $ch_stok = curl_init($stok_keluar_url);
//             curl_setopt($ch_stok, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch_stok, CURLOPT_HTTPHEADER, [
//                 'Authorization: Bearer ' . $token,
//             ]);
//             $response_stok = curl_exec($ch_stok);
//             $stok_data = json_decode($response_stok, true);
//             $ch_transaksi = curl_init($transaksi_url);
//             curl_setopt($ch_transaksi, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch_transaksi, CURLOPT_HTTPHEADER, [
//                 'Authorization: Bearer ' . $token,
//             ]);
//             $response_transaksi = curl_exec($ch_transaksi);
//             $transaksi_data = json_decode($response_transaksi, true);
//             foreach ($transaksi_data as $transaksi) {
//                 foreach ($stok_data as $stok) {
//                     if ($transaksi['data']['id_stok_keluar'] === $stok['data']['id']) {
//                         $transaksi_id = $transaksi['data']['id'];
//                         $delete_byidtransaksi_url = $this->api_url . '/inventory/transaksi/' . $transaksi_id;

//                         $ch_delete_transaksi = curl_init($delete_byidtransaksi_url);
//                         curl_setopt($ch_delete_transaksi, CURLOPT_CUSTOMREQUEST, "DELETE");
//                         curl_setopt($ch_delete_transaksi, CURLOPT_RETURNTRANSFER, true);
//                         curl_setopt($ch_delete_transaksi, CURLOPT_HTTPHEADER, [
//                             'Authorization: Bearer ' . $token,
//                         ]);
//                         $response_byidtransaksi = curl_exec($ch_delete_transaksi);
//                     }
//                 }
//             }
//             $ch_stok_keluar = curl_init($stok_keluar_url);
//             curl_setopt($ch_stok_keluar, CURLOPT_CUSTOMREQUEST, "DELETE");
//             curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
//             curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
//                 'Authorization: Bearer ' . $token,
//             ]);
//             $response_stok_keluar = curl_exec($ch_stok_keluar);
//             $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);

//             if ($http_status_code_stok_keluar === 204) {
//                 return redirect()->to(base_url('stokkeluarmedis'));
//             } else {

//                 return "Error deleting stok_keluar." . $response_byidtransaksi . $response_stok_keluar;
//             }
//         } else {
//             // User not logged in
//             return "User not logged in. Please log in first.";
//         }
//     }
// }
    //     $gudang_url = $this->api_url . '/inventory/gudang/' . $transaksi['data']['id_barang_medis'] . '/' . $jumlah_stok['id_ruangan'];
            //     $ch_gudang = curl_init($gudang_url);
            //     curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
            //     curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
            //         'Authorization: Bearer ' . $token,
            //     ]);
            //     $response_gudang = curl_exec($ch_gudang);
            //     $gudang_data = json_decode($response_gudang, true);
            //     $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
            //     curl_close($ch_gudang);

            //     $postGudangMedis = [
            //         'id_barang_medis' => $transaksi['data']['id_barang_medis'],
            //         'id_ruangan' => $jumlah_stok['id_ruangan'],
            //         'stok' => $gudang_data['data']['stok'] + $transaksi['data']['jumlah_keluar'],
            //         'no_batch' => $transaksi['data']['no_batch'],
            //         'no_faktur' => $transaksi['data']['no_faktur'],
            //     ];

            //     $tambah_gudang_JSON = json_encode($postGudangMedis);

            //     // Perform the update
            //     $ch_gudang = curl_init($gudang_url);
            //     curl_setopt($ch_gudang, CURLOPT_CUSTOMREQUEST, "PUT");
            //     curl_setopt($ch_gudang, CURLOPT_POSTFIELDS, $tambah_gudang_JSON);
            //     curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
            //     curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
            //         'Content-Type: application/json',
            //         'Content-Length: ' . strlen($tambah_gudang_JSON),
            //         'Authorization: Bearer ' . $token,
            //     ]);

            //     $response_gudang = curl_exec($ch_gudang);
            //     $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);

            //     curl_close($ch_gudang);
            // }
