<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TagihanController extends BaseController
{
    public function dataTagihanMedis()
    {
        $title = 'Data Tagihan Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 5;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $tagihan_url = $this->api_url . '/pengadaan/tagihan?page=' . $page . '&size=' . $size;
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $pegawai_url = $this->api_url . '/pegawai';

            // Fetch tagihan data
            $ch_tagihan = curl_init($tagihan_url);
            curl_setopt($ch_tagihan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_tagihan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_tagihan = curl_exec($ch_tagihan);
            $http_status_code_tagihan = curl_getinfo($ch_tagihan, CURLINFO_HTTP_CODE);

            // Fetch pengajuan data
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pengajuan = curl_exec($ch_pengajuan);
            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pesanan = curl_exec($ch_pesanan);
            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);

            // Fetch penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_penerimaan = curl_exec($ch_penerimaan);
            $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);

            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pemesanan = curl_exec($ch_pemesanan);
            $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

            // Fetch medis data
            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_medis = curl_exec($ch_medis);
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            // Fetch pegawai data
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pegawai = curl_exec($ch_pegawai);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);

            if ($response_tagihan && $response_pengajuan && $response_penerimaan && $response_pemesanan && $response_pegawai && $response_pesanan && $response_satuan && $response_medis) {
                if ($http_status_code_tagihan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pemesanan === 200 && $http_status_code_pegawai === 200 && $http_status_code_pesanan === 200 && $http_status_code_satuan === 200 && $http_status_code_medis === 200) {
                    $tagihan_data = json_decode($response_tagihan, true);
                    $pengajuan_data = json_decode($response_pengajuan, true);
                    $penerimaan_data = json_decode($response_penerimaan, true);
                    $pemesanan_data = json_decode($response_pemesanan, true);
                    $pegawai_data = json_decode($response_pegawai, true);
                    $pesanan_data = json_decode($response_pesanan, true);
                    $satuan_data = json_decode($response_satuan, true);
                    $medis_data = json_decode($response_medis, true);

                    $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Tagihan', 'tagihanmedis');

                    $breadcrumbs = $this->getBreadcrumbs();
                    return view('/admin/pengadaan/medis/tagihan/data_tagihan', [
                        'tagihan_medis_data' => $tagihan_data['data']['tagihan_barang_medis'],
                        'pengajuan_data' => $pengajuan_data['data'], // Assuming pengajuan data is stored in 'data' key
                        'pemesanan_data' => $pemesanan_data['data'], // Assuming pemesanan data is stored in 'data' key
                        'penerimaan_data' => $penerimaan_data['data'], // Assuming penerimaan data is stored in 'data' key
                        'pegawai_data' => $pegawai_data['data'], // Assuming pegawai data is stored in 'data' key
                        'pesanan_data' => $pesanan_data['data'], // Assuming pesanan data is stored in 'data' key
                        'satuan_data' => $satuan_data['data'], // Assuming satuan data is stored in 'data' key
                        'medis_data' => $medis_data['data'], // Assuming medis data is stored in 'data' key
                        'meta_data' => $tagihan_data['data'],
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    return "Error: Response tagihan data: $response_tagihan, Response pengajuan data: $response_pengajuan, Response penerimaan data: $response_penerimaan, Response pemesanan data: $response_pemesanan, Response pegawai data: $response_pegawai, Response pesanan data: $response_pesanan, Response satuan data: $response_satuan, Response medis data: $response_medis";
                }
            } else {
                return "Error fetching data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    // public function tambahTagihanMedis()
    // {
    //     $title = 'Tambah Tagihan Medis';
    //     if (session()->has('jwt_token')) {
    //         $token = session()->get('jwt_token');
    //         $medis_url = $this->api_url . '/inventaris/medis';
    //         $satuan_url = $this->api_url . '/inventaris/satuan';
    //         $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
    //         $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';
    //         $pegawai_url = $this->api_url . '/pegawai';

    //         // Initialize cURL for fetching pengajuan data
    //         $ch_pengajuan = curl_init($pengajuan_url);
    //         curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
    //             'Authorization: Bearer ' . $token,
    //         ]);
    //         $response_pengajuan = curl_exec($ch_pengajuan);

    //         // Initialize cURL for fetching penerimaan data
    //         $ch_penerimaan = curl_init($penerimaan_url);
    //         curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
    //             'Authorization: Bearer ' . $token,
    //         ]);
    //         $response_penerimaan = curl_exec($ch_penerimaan);

    //         // Initialize cURL for fetching pegawai data
    //         $ch_pegawai = curl_init($pegawai_url);
    //         curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
    //             'Authorization: Bearer ' . $token,
    //         ]);
    //         $response_pegawai = curl_exec($ch_pegawai);

    //         // Initialize cURL for fetching medis data
    //         $ch_medis = curl_init($medis_url);
    //         curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
    //             'Authorization: Bearer ' . $token,
    //         ]);
    //         $response_medis = curl_exec($ch_medis);

    //         // Initialize cURL for fetching satuan data
    //         $ch_satuan = curl_init($satuan_url);
    //         curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
    //             'Authorization: Bearer ' . $token,
    //         ]);
    //         $response_satuan = curl_exec($ch_satuan);

    //         if ($response_pengajuan && $response_penerimaan && $response_pegawai && $response_medis && $response_satuan) {
    //             $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
    //             $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
    //             $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
    //             $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
    //             $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

    //             if ($http_status_code_pengajuan === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pegawai === 200 && $http_status_code_medis === 200 && $http_status_code_satuan === 200) {
    //                 // Decode responses
    //                 $pengajuan_data = json_decode($response_pengajuan, true);
    //                 $penerimaan_data = json_decode($response_penerimaan, true);
    //                 $pegawai_data = json_decode($response_pegawai, true);
    //                 $medis_data = json_decode($response_medis, true);
    //                 $satuan_data = json_decode($response_satuan, true);

    //                 // Pass all data to the view
    //                 return view('/admin/pengadaan/medis/tagihan/tambah_tagihan', [
    //                     'pengajuan_data' => $pengajuan_data['data'],
    //                     'penerimaan_data' => $penerimaan_data['data'],
    //                     'pegawai_data' => $pegawai_data['data'],
    //                     'medis_data' => $medis_data['data'],
    //                     'satuan_data' => $satuan_data['data'],
    //                     'token' => $token,
    //                     'title' => $title
    //                 ]);
    //             } else {
    //                 // Error: Failed to fetch data
    //                 return "Error fetching data. Pengajuan Response: " . $response_pengajuan .
    //                     ", Penerimaan Response: " . $response_penerimaan .
    //                     ", Pegawai Response: " . $response_pegawai .
    //                     ", Medis Response: " . $response_medis .
    //                     ", Satuan Response: " . $response_satuan;
    //             }
    //         } else {
    //             // Error: Failed to get responses from the API
    //             return "Error fetching data.";
    //         }

    //         // Close cURL sessions
    //         curl_close($ch_pengajuan);
    //         curl_close($ch_penerimaan);
    //         curl_close($ch_pegawai);
    //         curl_close($ch_medis);
    //         curl_close($ch_satuan);
    //     } else {
    //         // User not logged in
    //         return "User not logged in. Please log in first.";
    //     }
    // }
    public function tambahTagihanMedisbyId($idpenerimaan)
    {
        $title = 'Tambah Tagihan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan'; // Updated URL for pemesanan
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan/' . $idpenerimaan;
            $pegawai_url = $this->api_url . '/pegawai';

            // Initialize cURL for fetching penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $penerimaan_data = json_decode($response_penerimaan, true);
            $idpengajuan = $penerimaan_data['data']['id_pengajuan'];

            // Initialize cURL for fetching pengajuan data
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);

            // Initialize cURL for fetching pesanan data based on id_pengajuan
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $idpengajuan;
            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);

            // Initialize cURL for fetching pegawai data
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            // Initialize cURL for fetching medis data
            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            // Initialize cURL for fetching satuan data
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);

            // Check if all responses are successful
            if ($response_pengajuan && $response_pemesanan && $response_penerimaan && $response_pegawai && $response_medis && $response_satuan && $response_pesanan) {
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);

                if ($http_status_code_pengajuan === 200 && $http_status_code_pemesanan === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pegawai === 200 && $http_status_code_medis === 200 && $http_status_code_satuan === 200 && $http_status_code_pesanan === 200) {
                    // Decode responses
                    $pengajuan_data = json_decode($response_pengajuan, true);
                    $pemesanan_data = json_decode($response_pemesanan, true);
                    $pegawai_data = json_decode($response_pegawai, true);
                    $medis_data = json_decode($response_medis, true);
                    $satuan_data = json_decode($response_satuan, true);
                    $pesanan_data = json_decode($response_pesanan, true);

                    $this->addBreadcrumb('Pengadaan', 'pengadaanbrgmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Tagihan', 'tagihanmedis');
                    $this->addBreadcrumb('Tambah', 'tambahtagihanmedis');

                    $breadcrumbs = $this->getBreadcrumbs();
                    // Pass all data to the view
                    return view('/admin/pengadaan/medis/tagihan/tagihan', [
                        'pengajuan_data' => $pengajuan_data['data'],
                        'pemesanan_data' => $pemesanan_data['data'],
                        'penerimaan_data' => $penerimaan_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'medis_data' => $medis_data['data'],
                        'satuan_data' => $satuan_data['data'],
                        'pesanan_data' => $pesanan_data['data'], // tambahkan pesanan_data ke dalam view
                        'token' => $token,
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    // Error: Failed to fetch data
                    return "Error fetching data. Pengajuan Response: " . $response_pengajuan .
                        ", Penerimaan Response: " . $response_penerimaan .
                        ", Pegawai Response: " . $response_pegawai .
                        ", Medis Response: " . $response_medis .
                        ", Satuan Response: " . $response_satuan .
                        ", Pesanan Response: " . $response_pesanan;
                }
            } else {
                // Error: Failed to get responses from the API
                return "Error fetching data.";
            }

            // Close cURL sessions
            curl_close($ch_pengajuan);
            curl_close($ch_penerimaan);
            curl_close($ch_pegawai);
            curl_close($ch_medis);
            curl_close($ch_satuan);
            curl_close($ch_pesanan);
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }


    public function submitTambahTagihanMedis()
    {
        if ($this->request->getPost()) {

            // $idpesanan = $this->request->getPost('idpesanan');
            // $idbrgmedis = $this->request->getPost('idbrgmedis');
            // $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            // $jumlah_diterima = $this->request->getPost('jumlah_diterima');
            // $harga_satuan = $this->request->getPost('harga_satuan');
            // $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            // $no_batch = $this->request->getPost('no_batch');

            $idpengajuan = $this->request->getPost('idpengajuan');
            $idpemesanan = $this->request->getPost('idpemesanan');
            $idpenerimaan = $this->request->getPost('idpenerimaan');
            $tglbayar = $this->request->getPost('tglbayar');
            $pegawaitagihan = $this->request->getPost('pegawaitagihan');
            $jlhbayar = intval($this->request->getPost('jlhbayar'));
            $keterangantagihan = $this->request->getPost('keterangantagihan');
            $akunbayar = intval($this->request->getPost('akunbayar'));
            $nobukti = $this->request->getPost('nobukti');


            $statuspesanan = $this->request->getPost('statuspesanan');

            $tagihan_url = $this->api_url . '/pengadaan/tagihan';

            $postDataTagihan = [
                'id_pengajuan' => $idpengajuan,
                'id_pemesanan' => $idpemesanan,
                'id_penerimaan' => $idpenerimaan,
                'tanggal_bayar' => $tglbayar,
                'id_pegawai' => $pegawaitagihan,
                'jumlah_bayar' => $jlhbayar,
                'keterangan' => $keterangantagihan,
                'no_bukti' => $nobukti,
                'id_akun_bayar' => $akunbayar,
            ];
            $tambah_tagihan_JSON = json_encode($postDataTagihan);


            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_tagihan = curl_init($tagihan_url);

                curl_setopt($ch_tagihan, CURLOPT_POST, 1);
                curl_setopt($ch_tagihan, CURLOPT_POSTFIELDS, ($tambah_tagihan_JSON));
                curl_setopt($ch_tagihan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_tagihan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_tagihan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_tagihan = curl_exec($ch_tagihan);

                if ($response_tagihan) {
                    $http_status_code_tagihan = curl_getinfo($ch_tagihan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_tagihan === 201) {
                        $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
                        $ch_pengajuan = curl_init($pengajuan_url);
                        curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);
                        $response_pengajuan = curl_exec($ch_pengajuan);
                        $pengajuan_data = json_decode($response_pengajuan, true);
                        $putDataPengajuan = [
                            'tanggal_pengajuan' => $pengajuan_data['data']['tanggal_pengajuan'],
                            'nomor_pengajuan' => $pengajuan_data['data']['nomor_pengajuan'],
                            'id_pegawai' => $pengajuan_data['data']['id_pegawai'],
                            'catatan' => $pengajuan_data['data']['catatan'],
                            'total_pengajuan' => $pengajuan_data['data']['total_pengajuan'],
                            'status_pesanan' => $statuspesanan,
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

                        // Execute the cURL request to obat_url
                        $response_pengajuan = curl_exec($ch_pengajuan);


                        // Check if the API request to obat_url was successful
                        if ($response_pengajuan) {
                            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_pengajuan === 200) {
                                // Data berhasil ditambahkan ke obat_url
                                return redirect()->to(base_url('tagihanmedis'));
                            } else {
                                return "Error Update Pengajuan: " . $response_pengajuan;
                            }
                            curl_close($ch_tagihan);
                            curl_close($ch_pengajuan);
                        } else {
                            return "Error sending request to the obat API.";
                        }
                    } else {
                        return "Error Insert Tagihan: " . $response_tagihan . $idpengajuan;
                    }
                } else {
                    // Error sending request to the API
                    return "Error sending request to the API.";
                }
            } else {
                // Email or role is empty
                return "User not logged in. Please log in first.";
            }
        } else {
            // Email or role is empty
            return "Data is required.";
        }
    }

    public function editTagihanMedis($tagihanId)
    {
        if (session()->has('jwt_token')) {
            // Ambil data medis berdasarkan ID barang medis
            $token = session()->get('jwt_token');
            $tagihan_url = $this->api_url . '/pengadaan/tagihan/' . $tagihanId;
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';
            $pegawai_url = $this->api_url . '/pegawai';
            $medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_tagihan = curl_init($tagihan_url);
            curl_setopt($ch_tagihan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_tagihan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_tagihan = curl_exec($ch_tagihan);
            $tagihan_data = json_decode($response_tagihan, true);
            $idpengajuan = $tagihan_data['data']['id_pengajuan'];
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $idpengajuan;

            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);

            // Ambil data satuan
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);

            // Ambil data medis
            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            $ch_penerimaan = curl_init($penerimaan_url); // Initialize curl for penerimaan data
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);

            $ch_pegawai = curl_init($pegawai_url); // Initialize curl for pegawai data
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            if ($response_tagihan && $response_pesanan && $response_pengajuan && $response_pemesanan && $response_penerimaan && $response_pegawai && $response_satuan && $response_medis) {
                $http_status_code_tagihan = curl_getinfo($ch_tagihan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);

                $pengajuan_data = json_decode($response_pengajuan, true);
                $pemesanan_data = json_decode($response_pemesanan, true);
                $pesanan_data = json_decode($response_pesanan, true);
                $penerimaan_data = json_decode($response_penerimaan, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $satuan_data = json_decode($response_satuan, true);
                $medis_data = json_decode($response_medis, true);

                if ($http_status_code_tagihan === 200 && $http_status_code_pesanan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_pemesanan === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pegawai === 200 && $http_status_code_satuan === 200 && $http_status_code_medis === 200) {
                    $this->addBreadcrumb('Pengadaan', 'pengadaanbrgmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Tagihan', 'tagihanmedis');
                    $this->addBreadcrumb('Edit', 'edittagihanmedis');

                    $breadcrumbs = $this->getBreadcrumbs();
                    return view('/admin/pengadaan/medis/tagihan/edit_tagihan', [
                        'tagihan_data' => $tagihan_data['data'],
                        'pesanan_data' => $pesanan_data['data'],
                        'pengajuan_data' => $pengajuan_data['data'],
                        'pemesanan_data' => $pemesanan_data['data'],
                        'penerimaan_data' => $penerimaan_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'satuan_data' => $satuan_data['data'], // Include satuan data
                        'medis_data' => $medis_data['data'], // Include medis data
                        'tagihanId' => $tagihanId,
                        'token' => $token,
                        'title' => 'Edit tagihan Medis',
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    // Error handling if any of the requests fail
                    return "Error fetching data. HTTP Status Code tagihan: $http_status_code_tagihan, HTTP Status Code Pengajuan: $http_status_code_pengajuan, HTTP Status Code Pesanan: $http_status_code_pesanan, HTTP Status Code Penerimaan: $http_status_code_penerimaan, HTTP Status Code Pegawai: $http_status_code_pegawai, HTTP Status Code Satuan: $http_status_code_satuan, HTTP Status Code Medis: $http_status_code_medis";
                }
            } else {
                // Error handling if any of the requests fail
                return "Error fetching data.";
            }

            // Tutup sesi cURL untuk data medis dan obat
            curl_close($ch_tagihan);
            curl_close($ch_pesanan);
            curl_close($ch_pengajuan);
            curl_close($ch_pemesanan);
            curl_close($ch_penerimaan);
            curl_close($ch_pegawai);
            curl_close($ch_satuan);
            curl_close($ch_medis);
        } else {
            // User belum login
            return "User not logged in. Please log in first.";
        }
    }
    public function submitEditTagihanMedis($tagihanId)
    {
        if ($this->request->getPost()) {
            $idpengajuan = $this->request->getPost('idpengajuan');
            $statuspesanan = $this->request->getPost('statuspesanan');

            $idpemesanan = $this->request->getPost('idpemesanan');
            $idpenerimaan = $this->request->getPost('idpenerimaan');
            $tglbayar = $this->request->getPost('tglbayar');
            $jlhbayar = intval($this->request->getPost('jlhbayar'));
            $keterangantagihan = $this->request->getPost('keterangantagihan');
            $akunbayar = intval($this->request->getPost('akunbayar'));
            $nobukti = $this->request->getPost('nobukti');
            $pegawaitagihan = $this->request->getPost('pegawaitagihan');


            $tagihan_url = $this->api_url . '/pengadaan/tagihan/' . $tagihanId;

            $postDataTagihan = [
                'id_pengajuan' => $idpengajuan,
                'id_pemesanan' => $idpemesanan,
                'id_penerimaan' => $idpenerimaan,
                'tanggal_bayar' => $tglbayar,
                'jumlah_bayar' => $jlhbayar,
                'id_pegawai' => $pegawaitagihan,
                'keterangan' => $keterangantagihan,
                'no_bukti' => $nobukti,
                'id_akun_bayar' => $akunbayar,
            ];
            $tambah_tagihan_JSON = json_encode($postDataTagihan);


            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_tagihan = curl_init($tagihan_url);

                curl_setopt($ch_tagihan, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_tagihan, CURLOPT_POSTFIELDS, ($tambah_tagihan_JSON));
                curl_setopt($ch_tagihan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_tagihan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ]);

                $response_tagihan = curl_exec($ch_tagihan);

                if ($response_tagihan) {
                    $http_status_code_tagihan = curl_getinfo($ch_tagihan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_tagihan === 200) {

                        $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
                        $ch_pengajuan = curl_init($pengajuan_url);
                        curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);
                        $response_pengajuan = curl_exec($ch_pengajuan);
                        $pengajuan_data = json_decode($response_pengajuan, true);
                        $putDataPengajuan = [
                            'tanggal_pengajuan' => $pengajuan_data['data']['tanggal_pengajuan'],
                            'nomor_pengajuan' => $pengajuan_data['data']['nomor_pengajuan'],
                            'id_pegawai' => $pengajuan_data['data']['id_pegawai'],
                            'catatan' => $pengajuan_data['data']['catatan'],
                            'total_pengajuan' => $pengajuan_data['data']['total_pengajuan'],
                            'status_pesanan' => $statuspesanan,
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


                        // Check if the API request to obat_url was successful
                        if ($response_pengajuan) {
                            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_pengajuan === 200) {
                                // Data berhasil ditambahkan ke obat_url
                                return redirect()->to(base_url('tagihanmedis'));
                            } else {
                                return "Error Update Pengajuan: " . $response_pengajuan;
                            }
                            curl_close($ch_tagihan);
                            curl_close($ch_pengajuan);
                        } else {
                            return "Error sending request to the obat API.";
                        }
                        curl_close($ch_tagihan);
                    } else {
                        // Error response from the API
                        curl_close($ch_tagihan); // Tutup session cURL untuk medis_url di sini
                        return "Error Insert Tagihan: " . $response_tagihan;
                    }
                } else {
                    // Error sending request to the API
                    return "Error sending request to the API.";
                }
            } else {
                // Email or role is empty
                return "User not logged in. Please log in first.";
            }
        } else {
            // Email or role is empty
            return "Data is required.";
        }
    }

    public function hapusTagihanMedis($tagihanId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');
            $tagihan_url = $this->api_url . '/pengadaan/tagihan/' . $tagihanId;

            $ch_tagihan = curl_init($tagihan_url);

            curl_setopt($ch_tagihan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_tagihan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_tagihan = curl_exec($ch_tagihan);
            $tagihan_data = json_decode($response_tagihan, true);
            $pengajuanId = $tagihan_data['data']['id_pengajuan'];
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;
            if ($response_tagihan) {
                $http_status_code_tagihan = curl_getinfo($ch_tagihan, CURLINFO_HTTP_CODE);
                if ($http_status_code_tagihan === 200) {
                    $ch_pengajuan = curl_init($pengajuan_url);

                    curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);
                    $response_pengajuan = curl_exec($ch_pengajuan);
                    $pengajuan_data = json_decode($response_pengajuan, true);

                    $putDataPengajuan = [
                        'tanggal_pengajuan' => $pengajuan_data['data']['tanggal_pengajuan'],
                        'nomor_pengajuan' => $pengajuan_data['data']['nomor_pengajuan'],
                        'id_pengajuan' => $pengajuan_data['data']['id_pengajuan'],
                        'total_pengajuan' => $pengajuan_data['data']['total_pengajuan'],
                        'status_pesanan' => '5',
                        'catatan' => $pengajuan_data['data']['catatan'],
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

                    // Check if the API request to obat_url was successful
                    if ($response_pengajuan) {
                        $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                        if ($http_status_code_pengajuan === 200) {
                            // Data berhasil ditambahkan ke obat_url
                            $ch_delete_tagihan = curl_init($tagihan_url);
                            curl_setopt($ch_delete_tagihan, CURLOPT_CUSTOMREQUEST, "DELETE");
                            curl_setopt($ch_delete_tagihan, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_delete_tagihan, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $token,
                            ]);
                            $response_delete_tagihan = curl_exec($ch_delete_tagihan);

                            $http_status_code_delete_tagihan = curl_getinfo($ch_delete_tagihan, CURLINFO_HTTP_CODE);

                            if ($http_status_code_delete_tagihan === 204) {
                                curl_close($ch_delete_tagihan);
                                return redirect()->to(base_url('tagihanmedis'));
                            } else {
                                // Error response from the API
                                return "Error deleting tagihan." . $response_delete_tagihan;
                            }
                            curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                            // Tutup session cURL untuk medis_url di sini

                        } else {
                            // Error response dari obat_url
                            curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                            return "Error Update Pengajuan: " . $response_pengajuan;
                        }
                        curl_close($ch_pengajuan);
                        curl_close($ch_tagihan);
                    } else {
                        // Error kirim permintaan ke obat_url
                        curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                        return "Error sending request to the obat API.";
                    }
                } else {
                    return "Error mendapatkan data pengajuan: " . $response_tagihan;
                }
            } else {
                return "Error mendapatkan data pengajuan.";
            }
            //delete tagihan

        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
}
