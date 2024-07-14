<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PengajuanController extends BaseController
{
    public function dashboardPengadaan()
    {
        $title = "Dashboard Pengadaan";
        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {

            $token = session()->get('jwt_token');

            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan?page=' . $page . '&size=' . $size;
            $pengajuan_tanpa_params_url = $this->api_url . '/pengadaan/pengajuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';

            // Initialize cURL for fetching pengajuan data
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $ch_pengajuan_tanpa_params = curl_init($pengajuan_tanpa_params_url);
            curl_setopt($ch_pengajuan_tanpa_params, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan_tanpa_params, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);


            // Initialize cURL for fetching pemesanan data
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Initialize cURL for fetching penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL requests to fetch data
            $response_pengajuan = curl_exec($ch_pengajuan);
            $response_pengajuan_tanpa_params = curl_exec($ch_pengajuan_tanpa_params);
            $response_pemesanan = curl_exec($ch_pemesanan);
            $response_penerimaan = curl_exec($ch_penerimaan);

            if ($response_pengajuan && $response_pemesanan && $response_penerimaan && $response_pengajuan_tanpa_params) {
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan_tanpa_params = curl_getinfo($ch_pengajuan_tanpa_params, CURLINFO_HTTP_CODE);

                // Check if any response has a non-200 status code and call renderErrorView
                if ($http_status_code_pengajuan !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan);
                }
                if ($http_status_code_pemesanan !== 200) {
                    return $this->renderErrorView($http_status_code_pemesanan);
                }
                if ($http_status_code_penerimaan !== 200) {
                    return $this->renderErrorView($http_status_code_penerimaan);
                }
                if ($http_status_code_pengajuan_tanpa_params !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan_tanpa_params);
                }
                $pengajuan_data = json_decode($response_pengajuan, true);
                $pemesanan_data = json_decode($response_pemesanan, true);
                $penerimaan_data = json_decode($response_penerimaan, true);
                $pengajuan_tanpa_params_data = json_decode($response_pengajuan_tanpa_params, true);

                $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                $this->addBreadcrumb('Dashboard', 'dashboardpengadaan');

                $breadcrumbs = $this->getBreadcrumbs();
                // Render the view with the fetched data
                return view("/admin/pengadaan/medis/dashboardPengadaan", [
                    'pengajuan_data' => $pengajuan_data['data']['pengajuan_barang_medis'],
                    'pengajuan_tanpa_params_data' => $pengajuan_tanpa_params_data['data'], // tambahkan data pengajuan tanpa params
                    'pemesanan_data' => $pemesanan_data['data'],
                    'penerimaan_data' => $penerimaan_data['data'],
                    'meta_data' => $pengajuan_data['data'],
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
                ]);
            } else {
                // Error handling for failed cURL requests
                return $this->renderErrorView(500);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }
    public function dataPengajuanMedis()
    {
        $title = 'Data Pengajuan Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {

            $token = session()->get('jwt_token');
            $search = $this->request->getGet('search') ?? '';
            $api_url = $this->api_url;

            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan?page=' . $page . '&size=' . $size;
            $pengajuan_tanpa_params_url = $this->api_url . '/pengadaan/pengajuan';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $persetujuan_url = $this->api_url . '/pengadaan/persetujuan';
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $pegawai_url = $this->api_url . '/pegawai';

            // Initialize cURL for fetching pengajuan data
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch pengajuan data
            $response_pengajuan = curl_exec($ch_pengajuan);

            $ch_pengajuan_tanpa_params = curl_init($pengajuan_tanpa_params_url);
            curl_setopt($ch_pengajuan_tanpa_params, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan_tanpa_params, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan_tanpa_params = curl_exec($ch_pengajuan_tanpa_params);

            // Initialize cURL for fetching pesanan data
            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch pesanan data
            $response_pesanan = curl_exec($ch_pesanan);

            // Initialize cURL for fetching persetujuan data
            $ch_persetujuan = curl_init($persetujuan_url);
            curl_setopt($ch_persetujuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_persetujuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch persetujuan data
            $response_persetujuan = curl_exec($ch_persetujuan);

            // Initialize cURL for fetching barang medis data
            $ch_barang_medis = curl_init($barang_medis_url);
            curl_setopt($ch_barang_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_barang_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch barang medis data
            $response_barang_medis = curl_exec($ch_barang_medis);

            // Initialize cURL for fetching satuan data
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch satuan data
            $response_satuan = curl_exec($ch_satuan);

            // Initialize cURL for fetching pegawai data
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL request to fetch pegawai data
            $response_pegawai = curl_exec($ch_pegawai);
            if ($response_pengajuan && $response_pesanan && $response_persetujuan && $response_barang_medis && $response_satuan && $response_pegawai && $response_pengajuan_tanpa_params) {
                // Check if the responses are successful
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan_tanpa_params = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_persetujuan = curl_getinfo($ch_persetujuan, CURLINFO_HTTP_CODE);
                $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);

                // Check if any response has a non-200 status code and call renderErrorView
                if ($http_status_code_pengajuan !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan);
                }
                if ($http_status_code_pengajuan_tanpa_params !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan_tanpa_params);
                }
                if ($http_status_code_pesanan !== 200) {
                    return $this->renderErrorView($http_status_code_pesanan);
                }
                if ($http_status_code_persetujuan !== 200) {
                    return $this->renderErrorView($http_status_code_persetujuan);
                }
                if ($http_status_code_barang_medis !== 200) {
                    return $this->renderErrorView($http_status_code_barang_medis);
                }
                if ($http_status_code_satuan !== 200) {
                    return $this->renderErrorView($http_status_code_satuan);
                }
                if ($http_status_code_pegawai !== 200) {
                    return $this->renderErrorView($http_status_code_pegawai);
                }
                // Decode the JSON response
                $pengajuan_medis_data = json_decode($response_pengajuan, true);
                $pengajuan_tanpa_params_medis_data = json_decode($response_pengajuan_tanpa_params, true);
                $pesanan_data = json_decode($response_pesanan, true);
                $persetujuan_data = json_decode($response_persetujuan, true);
                $medis_data = json_decode($response_barang_medis, true);
                $satuan_data = json_decode($response_satuan, true);
                $pegawai_data = json_decode($response_pegawai, true);

                $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                $this->addBreadcrumb('Barang Medis', 'medis');
                $this->addBreadcrumb('Pengajuan', 'data');

                $breadcrumbs = $this->getBreadcrumbs();
                session()->set('status_pesanan', $pengajuan_tanpa_params_medis_data['data']);
                return view('/admin/pengadaan/medis/pengajuan/data_pengajuan', [
                    'pengajuan_medis_data' => $pengajuan_medis_data['data']['pengajuan_barang_medis'],
                    'pesanan_data' => $pesanan_data['data'],
                    'persetujuan_data' => $persetujuan_data['data'],
                    'medis_data' => $medis_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'pegawai_data' => $pegawai_data['data'],
                    'search' => $search,
                    'meta_data' => $pengajuan_medis_data['data'],
                    'api_url' => $api_url,
                    'token' => $token,
                    'title' => $title,
                    'breadcrumbs' => $breadcrumbs
                ]);
            } else {
                // Error handling for failed cURL requests
                return $this->renderErrorView(500);
            }
            curl_close($ch_pengajuan);
            curl_close($ch_pesanan);
            curl_close($ch_persetujuan);
            curl_close($ch_barang_medis);
            curl_close($ch_satuan);
            curl_close($ch_pegawai);
        } else {
            return $this->renderErrorView(401);
        }
    }


    public function tambahPengajuanMedis()
    {
        $title = 'Tambah Pengajuan Medis';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pegawai_url = $this->api_url . '/pegawai';
            $barang_medis_url = $this->api_url . '/inventaris/medis';

            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            $ch_barang_medis = curl_init($barang_medis_url);
            curl_setopt($ch_barang_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_barang_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_barang_medis = curl_exec($ch_barang_medis);



            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_satuan = curl_exec($ch_satuan);

            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
            $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

            if ($http_status_code_pengajuan !== 200) {
                return $this->renderErrorView($http_status_code_pengajuan);
            }
            if ($http_status_code_pegawai !== 200) {
                return $this->renderErrorView($http_status_code_pegawai);
            }
            if ($http_status_code_barang_medis !== 200) {
                return $this->renderErrorView($http_status_code_barang_medis);
            }
            if ($http_status_code_satuan !== 200) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            $pengajuan_data = json_decode($response_pengajuan, true);
            $pegawai_data = json_decode($response_pegawai, true);
            $medis_data = json_decode($response_barang_medis, true);
            $satuan_data = json_decode($response_satuan, true);

            $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Pengajuan', 'data');
            $this->addBreadcrumb('Tambah', '');

            $breadcrumbs = $this->getBreadcrumbs();


            return view('/admin/pengadaan/medis/pengajuan/tambah_pengajuan', [
                'pengajuan_data' => $pengajuan_data['data'],
                'pegawai_data' => $pegawai_data['data'],
                'barang_medis' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahPengajuanMedis()
    {
        if ($this->request->getPost()) {
            $tglpengajuan = $this->request->getPost('tglpengajuan');
            $nopengajuan = $this->request->getPost('nopengajuan');
            $pegawai = $this->request->getPost('pegawai');
            $catatan = $this->request->getPost('catatan');
            $status = $this->request->getPost('status');
            $totalkeseluruhan = intval($this->request->getPost('totalkeseluruhan'));

            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuanbrgmedis = $this->request->getPost('satuanbrgmedis');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
            $harga_satuan_pemesanan = $this->request->getPost('harga_satuan_pemesanan');
            $subtotalperitem = $this->request->getPost('subtotalperitem');
            $totalperitem = $this->request->getPost('totalperitem');

            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $persetujuan_url = $this->api_url . '/pengadaan/persetujuan';

            $postDataPengajuan = [
                'tanggal_pengajuan' => $tglpengajuan,
                'nomor_pengajuan' => $nopengajuan,
                'id_pegawai' => $pegawai,
                'total_pengajuan' => $totalkeseluruhan,
                'catatan' => $catatan,
                'status_pesanan' => $status,
            ];
            $tambah_pengajuan_JSON = json_encode($postDataPengajuan);

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');

                $ch_pengajuan = curl_init($pengajuan_url);
                curl_setopt($ch_pengajuan, CURLOPT_POST, 1);
                curl_setopt($ch_pengajuan, CURLOPT_POSTFIELDS, ($tambah_pengajuan_JSON));
                curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_pengajuan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);
                $response_pengajuan = curl_exec($ch_pengajuan);
                $decode_response_pengajuan = json_decode($response_pengajuan, true);

                if ($response_pengajuan) {
                    $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_pengajuan !== 201) {
                        return $this->renderErrorView($http_status_code_pengajuan);
                    }
                    $id_pengajuan = $decode_response_pengajuan['data']['id'];
                    for ($i = 0; $i < count($idbrgmedis); $i++) {
                        $postDataPesanan = [
                            'id_pengajuan' => $id_pengajuan,
                            'id_barang_medis' => $idbrgmedis[$i],
                            'satuan' => intval($satuanbrgmedis[$i]),
                            'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                            'harga_satuan_pemesanan' => 0,
                            'total_per_item' => intval($subtotalperitem[$i]),
                            'subtotal_per_item' => intval($subtotalperitem[$i]),
                            'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                        ];
                        $tambah_pesanan_JSON = json_encode($postDataPesanan);
                        $ch_pesanan = curl_init($pesanan_url);
                        curl_setopt($ch_pesanan, CURLOPT_POST, 1);
                        curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, ($tambah_pesanan_JSON));
                        curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($tambah_pesanan_JSON),
                            'Authorization: Bearer ' . $token,
                        ]);
                        $response_pesanan = curl_exec($ch_pesanan);
                    }
                    $postDatapersetujuan = [
                        'id_pengajuan' => $id_pengajuan,
                    ];
                    $tambah_persetujuan_JSON = json_encode($postDatapersetujuan);
                    $ch_persetujuan = curl_init($persetujuan_url);
                    curl_setopt($ch_persetujuan, CURLOPT_POST, 1);
                    curl_setopt($ch_persetujuan, CURLOPT_POSTFIELDS, ($tambah_persetujuan_JSON));
                    curl_setopt($ch_persetujuan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_persetujuan, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($tambah_persetujuan_JSON),
                        'Authorization: Bearer ' . $token,
                    ]);
                    $response_persetujuan = curl_exec($ch_persetujuan);

                    if ($response_pesanan && $response_persetujuan) {
                        $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                        $http_status_code_persetujuan = curl_getinfo($ch_persetujuan, CURLINFO_HTTP_CODE);
                        if ($http_status_code_pesanan !== 201) {
                            return $this->renderErrorView($http_status_code_pesanan);
                        }
                        if ($http_status_code_persetujuan !== 201) {
                            return $this->renderErrorView($http_status_code_persetujuan);
                        }

                        return redirect()->to(base_url('pengajuanmedis'));

                        curl_close($ch_persetujuan);
                        curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                        curl_close($ch_pesanan);
                    } else {
                        return $this->renderErrorView(500);
                    }
                } else {
                    return $this->renderErrorView(500);
                }
            } else {
                return $this->renderErrorView(401);
            }
        } else {
            return "Data is required.";
        }
    }

    public function editPengajuanMedis($pengajuanId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $pengajuanId;
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $pegawai_url = $this->api_url . '/pegawai';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_barang_medis = curl_init($barang_medis_url);
            curl_setopt($ch_barang_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_barang_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_barang_medis = curl_exec($ch_barang_medis);

            curl_close($ch_barang_medis);
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);

            if ($response_pengajuan && $response_pesanan && $response_barang_medis && $response_pegawai && $response_satuan) {
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

                if ($http_status_code_pengajuan !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan);
                }
                if ($http_status_code_pesanan !== 200) {
                    return $this->renderErrorView($http_status_code_pesanan);
                }
                if ($http_status_code_barang_medis !== 200) {
                    return $this->renderErrorView($http_status_code_barang_medis);
                }
                if ($http_status_code_pegawai !== 200) {
                    return $this->renderErrorView($http_status_code_pegawai);
                }
                if ($http_status_code_satuan !== 200) {
                    return $this->renderErrorView($http_status_code_satuan);
                }

                $pengajuan_data = json_decode($response_pengajuan, true);
                if (
                    $pengajuan_data['data']['status_pesanan'] !== '1' || $pengajuan_data['data']['status_pesanan'] !== '2'
                ) {
                    return redirect('pengajuanmedis')->with('warning', 'Pengajuan telah ditolak atau disetujui sehingga tidak bisa diubah');
                }
                $pesanan_data = json_decode($response_pesanan, true);
                $medis_data = json_decode($response_barang_medis, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $satuan_data = json_decode($response_satuan, true);

                $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                $this->addBreadcrumb('Barang Medis', 'medis');
                $this->addBreadcrumb('Pengajuan', 'pengajuanmedis');
                $this->addBreadcrumb('Ubah', 'editpengajuanmedis');

                $breadcrumbs = $this->getBreadcrumbs();


                return view('/admin/pengadaan/medis/pengajuan/edit_pengajuan', [
                    'pengajuan_data' => $pengajuan_data['data'],
                    'pesanan_data' => $pesanan_data['data'],
                    'medis_data' => $medis_data['data'],
                    'pegawai_data' => $pegawai_data['data'],
                    'satuan_data' => $satuan_data['data'],
                    'pengajuanId' => $pengajuanId,
                    'title' => 'Edit Pengajuan Medis',
                    'breadcrumbs' => $breadcrumbs
                ]);
            } else {
                return $this->renderErrorView(500);
            }
            curl_close($ch_pengajuan);
            curl_close($ch_pesanan);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitEditPengajuanMedis($pengajuanId)
    {

        $tglpengajuan = $this->request->getPost('tglpengajuan');
        $nopengajuan = $this->request->getPost('nopengajuan');
        $pegawai = $this->request->getPost('pegawai');
        $totalkeseluruhan = intval($this->request->getPost('totalkeseluruhan'));
        $catatan = $this->request->getPost('catatan');
        $status = $this->request->getPost('status');

        $idpesanan = $this->request->getPost('idpesanan');
        $idbrgmedis = $this->request->getPost('idbrgmedis');
        $satuanbrgmedis = $this->request->getPost('satuanbrgmedis');
        $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
        $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
        $subtotalperitem = $this->request->getPost('subtotalperitem');
        $totalperitem = $this->request->getPost('totalperitem');
        $kadaluwarsa = $this->request->getPost('kadaluwarsa');

        $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;




        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $putDataPengajuan = [
                'tanggal_pengajuan' => $tglpengajuan,
                'nomor_pengajuan' => $nopengajuan,
                'id_pegawai' => $pegawai,
                'total_pengajuan' => $totalkeseluruhan,
                'catatan' => $catatan,
                'status_pesanan' => $status,
            ];

            $update_pengajuan_JSON = json_encode($putDataPengajuan);
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch_pengajuan, CURLOPT_POSTFIELDS, $update_pengajuan_JSON);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response_pengajuan = curl_exec($ch_pengajuan);

            if ($response_pengajuan) {
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                if ($http_status_code_pengajuan !== 200) {
                    return $this->renderErrorView($http_status_code_pengajuan);
                }

                for ($i = 0; $i < count($idbrgmedis); $i++) {
                    $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                    $postDataPesanan = [
                        'id_pengajuan' => $pengajuanId,
                        'id_barang_medis' => $idbrgmedis[$i],
                        'satuan' => intval($satuanbrgmedis[$i]),
                        'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                        'harga_satuan_pemesanan' => 0,
                        'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                        'total_per_item' => intval($subtotalperitem[$i]),
                        'subtotal_per_item' => intval($subtotalperitem[$i]),
                        'kadaluwarsa' => $kadaluwarsa[$i],
                    ];
                    $edit_pesanan_JSON = json_encode($postDataPesanan);
                    $ch_pesanan = curl_init($pesanan_url);
                    curl_setopt($ch_pesanan, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, $edit_pesanan_JSON);
                    curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($edit_pesanan_JSON),
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response = curl_exec($ch_pesanan);
                }

                if ($response) {
                    $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_pesanan !== 200) {
                        return $this->renderErrorView($http_status_code_pesanan);
                    }
                    return redirect()->to(base_url('pengajuanmedis'));
                } else {
                    curl_close($ch_pesanan);
                    return $this->renderErrorView(500);
                }
                curl_close($ch_pengajuan);
                curl_close($ch_pesanan);
            } else {
                return $this->renderErrorView(500);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function hapusPengajuanMedis($pengajuanId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $pengajuan_url =  $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;
            $pesanan_url =  $this->api_url . '/pengadaan/pesanan/pengajuan/' . $pengajuanId;

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);
            $pesanan_data = json_decode($response_pesanan, true);
            $jumlah_pesanan = $pesanan_data['data'];
            foreach ($jumlah_pesanan as $pesanan) {
                $pesanan_id = $pesanan['id'];
                $delete_byidpesanan_url = $this->api_url . '/pengadaan/pesanan/' . $pesanan_id;

                $ch_delete_pesanan = curl_init($delete_byidpesanan_url);
                curl_setopt($ch_delete_pesanan, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch_delete_pesanan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_delete_pesanan, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                ]);
                $response_pesanan = curl_exec($ch_delete_pesanan);
                $http_status_code_pesanan = curl_getinfo($ch_delete_pesanan, CURLINFO_HTTP_CODE);
            }
            $ch_delete_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_delete_pengajuan, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch_delete_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_delete_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pengajuan = curl_exec($ch_delete_pengajuan);
            $http_status_code_pengajuan = curl_getinfo($ch_delete_pengajuan, CURLINFO_HTTP_CODE);

            if ($http_status_code_pesanan !== 204) {
                return $this->renderErrorView($http_status_code_pesanan);
            }
            if ($http_status_code_pengajuan !== 204) {
                return $this->renderErrorView($http_status_code_pengajuan);
            }

            return redirect()->to(base_url('pengajuanmedis'));
        } else {
            return $this->renderErrorView(401);
        }
    }
}
