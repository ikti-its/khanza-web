<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MedisController extends BaseController
{
    public function dataMedis()
    {
        $title = 'Data Medis';
        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventaris/medis?page=' . $page . '&size=' . $size;
            $medis_tanpa_params_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $transaksi_url = $this->api_url . '/inventaris/transaksi';
            $jenis_url = [
                'obat' => $this->api_url . '/inventaris/obat',
                'alkes' => $this->api_url . '/inventaris/alkes',
                'bhp' => $this->api_url . '/inventaris/bhp',
                'darah' => $this->api_url . '/inventaris/darah'
            ];

            // Inisialisasi cURL untuk masing-masing endpoint
            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            curl_close($ch_medis);

            $ch_medis_tanpa_params = curl_init($medis_tanpa_params_url);
            curl_setopt($ch_medis_tanpa_params, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis_tanpa_params, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis_tanpa_params = curl_exec($ch_medis_tanpa_params);
            $http_status_code_medis_tanpa_params = curl_getinfo($ch_medis_tanpa_params, CURLINFO_HTTP_CODE);
            curl_close($ch_medis_tanpa_params);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            curl_close($ch_satuan);

            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
            curl_close($ch_penerimaan);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);
            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
            curl_close($ch_pesanan);

            $ch_transaksi = curl_init($transaksi_url);
            curl_setopt($ch_transaksi, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_transaksi, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_transaksi = curl_exec($ch_transaksi);
            $http_status_code_transaksi = curl_getinfo($ch_transaksi, CURLINFO_HTTP_CODE);
            curl_close($ch_transaksi);

            if ($response_medis && $response_medis_tanpa_params && $response_satuan && $response_penerimaan && $response_pesanan && $response_transaksi) {
                // Handle medis data
                if ($http_status_code_medis === 200 && $http_status_code_medis_tanpa_params === 200 && $http_status_code_satuan === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pesanan === 200 && $http_status_code_transaksi === 200) {
                    $medis_data = json_decode($response_medis, true);
                    $medis_tanpa_params_data = json_decode($response_medis_tanpa_params, true);
                    $satuan_data = json_decode($response_satuan, true);
                    $transaksi_data = json_decode($response_transaksi, true);

                    // Handle jenis data
                    foreach ($jenis_url as $jenis => $url) {
                        $ch_jenis = curl_init($url);
                        curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);
                        $response_jenis = curl_exec($ch_jenis);
                        $decoded_response = json_decode($response_jenis, true);
                        $jenis_data[$jenis] = $decoded_response['data'];
                        curl_close($ch_jenis);
                    }

                    // Handle penerimaan data
                    $penerimaan_data = json_decode($response_penerimaan, true);

                    // Handle pesanan data
                    $pesanan_data = json_decode($response_pesanan, true);

                    // Check response status codes
                    if ($response_jenis) {
                        $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);
                        if ($http_status_code_jenis === 200) {

                            $this->addBreadcrumb('Inventaris', 'inventarismedis');
                            $this->addBreadcrumb('Barang Medis', 'medis');
                            $this->addBreadcrumb('Data', 'data');

                            $breadcrumbs = $this->getBreadcrumbs();

                            return view('/admin/inventaris/medis/data_medis', [
                                'medis_data' => $medis_data['data']['barang_medis'],
                                'medis_tanpa_params_data' => $medis_tanpa_params_data['data'],
                                'satuan_data' => $satuan_data['data'],
                                'obat_data' => $jenis_data['obat'],
                                'alkes_data' => $jenis_data['alkes'],
                                'bhp_data' => $jenis_data['bhp'],
                                'darah_data' => $jenis_data['darah'],
                                'penerimaan_data' => $penerimaan_data['data'],
                                'pesanan_data' => $pesanan_data['data'],
                                'transaksi_keluar_data' => $transaksi_data['data'],
                                'meta_data' => $medis_data['data'],
                                'title' => $title,
                                'breadcrumbs' => $breadcrumbs
                            ]);
                        } else {
                            return "Response jenis data:" . $response_jenis;
                        }
                    } else {
                        return "Error fetching jenis data.";
                    }
                } else {
                    return "Response medis data:" . $response_medis;
                }
            } else {
                return "Error fetching data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function tambahMedis()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_satuan = curl_exec($ch_satuan);
            if ($response_satuan) {
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                if ($http_status_code_satuan === 200) {
                    $this->addBreadcrumb('Inventaris', 'inventarismedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Data', 'data');

                    $breadcrumbs = $this->getBreadcrumbs();

                    $satuan_data = json_decode($response_satuan, true);
                    return view('/admin/inventaris/medis/tambah_medis', [
                        'satuan_data' => $satuan_data['data'],
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    return "Response satuan data:" . $response_satuan;
                }
            } else {
                return "Error fetching satuan data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function submitTambahMedis()
    {
        if ($this->request->getPost()) {
            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $kode = $this->request->getPost('kode');
                $nama = $this->request->getPost('nama');
                $kandungan = $this->request->getPost('kandungan');
                $indusfarmasi = $this->request->getPost('indusfarmasi');
                $satuan = $this->request->getPost('satuan');
                $satkecil = $this->request->getPost('satkecil');
                $jenis = $this->request->getPost('jenis');
                $kategori = $this->request->getPost('kategori');
                $golongan = $this->request->getPost('golongan');
                $hargadasar = intval($this->request->getPost('hargadasar'));
                $hargabeli = intval($this->request->getPost('hargabeli'));
                $hargaralan = intval($this->request->getPost('hargaralan'));
                $hargakelas2 = intval($this->request->getPost('hargakelas2'));
                $hargakelas3 = intval($this->request->getPost('hargakelas3'));
                $hargautama = intval($this->request->getPost('hargautama'));
                $hargavip = intval($this->request->getPost('hargavip'));
                $hargavvip = intval($this->request->getPost('hargavvip'));
                $hargaobatbebas = intval($this->request->getPost('hargaobatbebas'));
                $hargaobatkaryawan = intval($this->request->getPost('hargaobatkaryawan'));
                $stokminimum = intval($this->request->getPost('stokminimum'));
                $kadaluwarsa = $this->request->getPost('kadaluwarsa');
                if ($kadaluwarsa === "") {
                    $kadaluwarsaformat = '0001-01-01';
                } else {
                    $kadaluwarsaformat = $kadaluwarsa; // Gunakan nilai $kadaluwarsa yang sudah ada
                }
                $medis_url = $this->api_url . '/inventaris/medis';

                $postDataMedis = [
                    '' => $kode,
                    'nama' => $nama,
                    '' => $kandungan,
                    '' => $indusfarmasi,
                    '' => $satuan,
                    '' => $satkecil,
                    '' => $jenis,
                    '' => $kategori,
                    '' => $golongan,
                    '' => $hargadasar,
                    '' => $hargabeli,
                    '' => $hargaralan,
                    '' => $hargakelas2,
                    '' => $hargakelas3,
                    '' => $hargautama,
                    '' => $hargavip,
                    '' => $hargavvip,
                    '' => $hargaobatbebas,
                    '' => $hargaobatkaryawan,
                    'stok_minimum' => $stokminimum,
                    '' => $kadaluwarsaformat,
                ];
                $tambah_medis_JSON = json_encode($postDataMedis);

                $ch_medis = curl_init($medis_url);

                curl_setopt($ch_medis, CURLOPT_POST, 1);
                curl_setopt($ch_medis, CURLOPT_POSTFIELDS, ($tambah_medis_JSON));
                curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_medis_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_medis = curl_exec($ch_medis);

                if ($response_medis) {
                    $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                    if ($http_status_code_medis === 201) {

                        return redirect()->to(base_url('datamedis'));

                        curl_close($ch_medis);
                    } else {
                        return "Error Insert Medis: " . $response_medis;
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

    public function editMedis($medisId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventaris/medis/' . $medisId;
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            if ($response_medis) {
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                if ($http_status_code_medis === 200) {
                    $medis_data = json_decode($response_medis, true);

                    $ch_satuan = curl_init($satuan_url);
                    curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_satuan = curl_exec($ch_satuan);

                    if ($response_satuan) {
                        $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                        if ($http_status_code_satuan === 200) {

                            $satuan_data = json_decode($response_satuan, true);

                            $this->addBreadcrumb('Inventaris', 'inventarismedis');
                            $this->addBreadcrumb('Barang Medis', 'medis');
                            $this->addBreadcrumb('Data', 'data');
                            $this->addBreadcrumb('Ubah', 'edit');

                            $breadcrumbs = $this->getBreadcrumbs();
                            return view('/admin/inventaris/medis/edit_medis', [
                                'medis_data' => $medis_data['data'],
                                'satuan_data' => $satuan_data['data'], // Masukkan data obat ke dalam view
                                'medisId' => $medisId,
                                'title' => 'Edit Medis',
                                'breadcrumbs' => $breadcrumbs
                            ]);
                        } else {
                            return "Response Satuan:" . $response_satuan;
                        }

                        curl_close($ch_satuan);
                        curl_close($ch_medis);
                    } else {
                        return "Error fetching obat data.";
                    }
                } else {
                    return "Error fetching medis data. HTTP Status Code: $http_status_code_medis";
                }
            } else {
                return "Error fetching medis data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function submitEditMedis($medisId)
    {

        if ($this->request->getPost()) {
            $nama = $this->request->getPost('nama');
            $jenisbrgmedis = $this->request->getPost('jenisbrgmedis');
            $harga = intval($this->request->getPost('harga'));
            $stok = intval($this->request->getPost('stok'));
            $stok_minimum = intval($this->request->getPost('stok_minimum'));
            $notif_kadaluwarsa = intval($this->request->getPost('notif_kadaluwarsa'));
            $idjenisbrgmedis = $this->request->getPost('idjenisbrgmedis');
            $satuanbrgmedis = intval($this->request->getPost('satuanbrgmedis'));

            //Obat
            $industrifarmasi = intval($this->request->getPost('industrifarmasi'));
            $kandungan = $this->request->getPost('kandungan');
            $satuanobat = intval($this->request->getPost('satuanobat'));
            $isi = intval($this->request->getPost('isi'));
            $kapasitas = intval($this->request->getPost('kapasitas'));
            $jenisobat = intval($this->request->getPost('jenisobat'));
            $kategori = intval($this->request->getPost('kategoriobat'));
            $golongan = intval($this->request->getPost('golonganobat'));
            $kadaluwarsa = $this->request->getPost('kadaluwarsaobat');


            //Alkes
            $merekalkes = $this->request->getPost('merekalkes');

            //BHP
            $jumlahbhp = intval($this->request->getPost('jumlahbhp'));
            $kadaluwarsabhp = $this->request->getPost('kadaluwarsabhp');

            //Darah
            $jenisdarah = $this->request->getPost('jenisdarah');
            $keterangandarah = $this->request->getPost('keterangandarah');
            $kadaluwarsadarah = $this->request->getPost('kadaluwarsadarah');

            $postDataMedis = [
                'nama' => $nama,
                'jenis' => $jenisbrgmedis,
                'satuan' => $satuanbrgmedis,
                'harga' => $harga,
                'stok' => $stok,
                'stok_minimum' => $stok_minimum,
                'notifikasi_kadaluwarsa_hari' => $notif_kadaluwarsa,
            ];
            $edit_medis_JSON = json_encode($postDataMedis);

            $medis_url = $this->api_url . '/inventaris/medis/' . $medisId;

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');

                $ch_medis = curl_init($medis_url);

                curl_setopt($ch_medis, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_medis, CURLOPT_POSTFIELDS, $edit_medis_JSON);
                curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_medis_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_medis = curl_exec($ch_medis);

                if ($response_medis) {
                    $http_status_code = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                    if ($http_status_code === 200) {
                        $title = 'Data Medis';

                        if ($jenisbrgmedis === 'Obat') {
                            $postData = [
                                'id_barang_medis' => $medisId,
                                'industri_farmasi' => $industrifarmasi,
                                'kandungan' => $kandungan,
                                'satuan' => $satuanobat,
                                'isi' => $isi,
                                'kapasitas' => $kapasitas,
                                'jenis' => $jenisobat,
                                'kategori' => $kategori,
                                'golongan' => $golongan,
                                'kadaluwarsa' => $kadaluwarsa
                            ];
                            $postURL = $this->api_url . '/inventaris/obat/' . $idjenisbrgmedis;
                        } elseif ($jenisbrgmedis === 'Alat Kesehatan') {
                            $postData = [
                                'id_barang_medis' => $medisId,
                                'merek' => $merekalkes
                            ];
                            $postURL = $this->api_url . '/inventaris/alkes/' . $idjenisbrgmedis;
                        } elseif ($jenisbrgmedis === 'Bahan Habis Pakai') {
                            $postData = [
                                'id_barang_medis' => $medisId,
                                'jumlah' => $jumlahbhp,
                                'kadaluwarsa' => $kadaluwarsabhp
                            ];
                            $postURL = $this->api_url . '/inventaris/bhp/' . $idjenisbrgmedis;
                        } elseif ($jenisbrgmedis === 'Darah') {
                            $postData = [
                                'id_barang_medis' => $medisId,
                                'jenis' => $jenisdarah,
                                'keterangan' => $keterangandarah,
                                'kadaluwarsa' => $kadaluwarsadarah
                            ];
                            $postURL = $this->api_url . '/inventaris/darah/' . $idjenisbrgmedis;
                        } else {
                            return "Jenis Barang Medis Belum dipilih";
                        }

                        $postDataJSON = json_encode($postData);

                        $ch = curl_init($postURL);

                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJSON);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response = curl_exec($ch);

                        if ($response) {
                            $http_status_code_jenis = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            if ($http_status_code_jenis === 200) {

                                return redirect()->to(base_url('datamedis'));
                            } else {

                                return "Error Insert Data: " . $response;
                            }
                            curl_close($ch);
                            curl_close($ch_medis);
                        } else {

                            return "Error sending request to the API.";
                        }
                    } else {
                        return "Error updating medis: " . $response_medis;
                    }
                } else {
                    return "Error sending request to the API.";
                }

                curl_close($ch_medis);
            } else {
                return "Email and role are required.";
            }
        } else {
            return "Data is required.";
        }
    }
    // public function submitEditMedis($medisId)
    // {

    //     if ($this->request->getPost()) {
    //         $nama = $this->request->getPost('nama');
    //         $jenisbrgmedis = $this->request->getPost('jenisbrgmedis');
    //         $harga = intval($this->request->getPost('harga'));
    //         $stok = intval($this->request->getPost('stok'));
    //         $stok_minimum = intval($this->request->getPost('stok_minimum'));
    //         $notif_kadaluwarsa = intval($this->request->getPost('notif_kadaluwarsa'));
    //         $idjenisbrgmedis = $this->request->getPost('idjenisbrgmedis');
    //         $satuanbrgmedis = intval($this->request->getPost('satuanbrgmedis'));

    //         //Obat
    //         $industrifarmasi = intval($this->request->getPost('industrifarmasi'));
    //         $kandungan = $this->request->getPost('kandungan');
    //         $satuanobat = intval($this->request->getPost('satuanobat'));
    //         $isi = intval($this->request->getPost('isi'));
    //         $kapasitas = intval($this->request->getPost('kapasitas'));
    //         $jenisobat = intval($this->request->getPost('jenisobat'));
    //         $kategori = intval($this->request->getPost('kategoriobat'));
    //         $golongan = intval($this->request->getPost('golonganobat'));
    //         $kadaluwarsa = $this->request->getPost('kadaluwarsaobat');


    //         //Alkes
    //         $merekalkes = $this->request->getPost('merekalkes');

    //         //BHP
    //         $jumlahbhp = intval($this->request->getPost('jumlahbhp'));
    //         $kadaluwarsabhp = $this->request->getPost('kadaluwarsabhp');

    //         //Darah
    //         $jenisdarah = $this->request->getPost('jenisdarah');
    //         $keterangandarah = $this->request->getPost('keterangandarah');
    //         $kadaluwarsadarah = $this->request->getPost('kadaluwarsadarah');

    //         $postDataMedis = [
    //             'nama' => $nama,
    //             'jenis' => $jenisbrgmedis,
    //             'satuan' => $satuanbrgmedis,
    //             'harga' => $harga,
    //             'stok' => $stok,
    //             'stok_minimum' => $stok_minimum,
    //             'notifikasi_kadaluwarsa_hari' => $notif_kadaluwarsa,
    //         ];
    //         $edit_medis_JSON = json_encode($postDataMedis);

    //         $medis_url = $this->api_url . '/inventaris/medis/' . $medisId;

    //         if (session()->has('jwt_token')) {
    //             $token = session()->get('jwt_token');

    //             $ch_medis = curl_init($medis_url);

    //             curl_setopt($ch_medis, CURLOPT_CUSTOMREQUEST, "PUT");
    //             curl_setopt($ch_medis, CURLOPT_POSTFIELDS, $edit_medis_JSON);
    //             curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
    //                 'Content-Type: application/json',
    //                 'Content-Length: ' . strlen($edit_medis_JSON),
    //                 'Authorization: Bearer ' . $token,
    //             ]);

    //             $response_medis = curl_exec($ch_medis);

    //             if ($response_medis) {
    //                 $http_status_code = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
    //                 if ($http_status_code === 200) {
    //                     $title = 'Data Medis';

    //                     if ($jenisbrgmedis === 'Obat') {
    //                         $postData = [
    //                             'id_barang_medis' => $medisId,
    //                             'industri_farmasi' => $industrifarmasi,
    //                             'kandungan' => $kandungan,
    //                             'satuan' => $satuanobat,
    //                             'isi' => $isi,
    //                             'kapasitas' => $kapasitas,
    //                             'jenis' => $jenisobat,
    //                             'kategori' => $kategori,
    //                             'golongan' => $golongan,
    //                             'kadaluwarsa' => $kadaluwarsa
    //                         ];
    //                         $postURL = $this->api_url . '/inventaris/obat/' . $idjenisbrgmedis;
    //                     } elseif ($jenisbrgmedis === 'Alat Kesehatan') {
    //                         $postData = [
    //                             'id_barang_medis' => $medisId,
    //                             'merek' => $merekalkes
    //                         ];
    //                         $postURL = $this->api_url . '/inventaris/alkes/' . $idjenisbrgmedis;
    //                     } elseif ($jenisbrgmedis === 'Bahan Habis Pakai') {
    //                         $postData = [
    //                             'id_barang_medis' => $medisId,
    //                             'jumlah' => $jumlahbhp,
    //                             'kadaluwarsa' => $kadaluwarsabhp
    //                         ];
    //                         $postURL = $this->api_url . '/inventaris/bhp/' . $idjenisbrgmedis;
    //                     } elseif ($jenisbrgmedis === 'Darah') {
    //                         $postData = [
    //                             'id_barang_medis' => $medisId,
    //                             'jenis' => $jenisdarah,
    //                             'keterangan' => $keterangandarah,
    //                             'kadaluwarsa' => $kadaluwarsadarah
    //                         ];
    //                         $postURL = $this->api_url . '/inventaris/darah/' . $idjenisbrgmedis;
    //                     } else {
    //                         return "Jenis Barang Medis Belum dipilih";
    //                     }

    //                     $postDataJSON = json_encode($postData);

    //                     $ch = curl_init($postURL);

    //                     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    //                     curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJSON);
    //                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //                         'Content-Type: application/json',
    //                         'Authorization: Bearer ' . $token,
    //                     ]);

    //                     $response = curl_exec($ch);

    //                     if ($response) {
    //                         $http_status_code_jenis = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //                         if ($http_status_code_jenis === 200) {

    //                             return redirect()->to(base_url('datamedis'));
    //                         } else {

    //                             return "Error Insert Data: " . $response;
    //                         }
    //                         curl_close($ch);
    //                         curl_close($ch_medis);
    //                     } else {

    //                         return "Error sending request to the API.";
    //                     }
    //                 } else {
    //                     return "Error updating medis: " . $response_medis;
    //                 }
    //             } else {
    //                 return "Error sending request to the API.";
    //             }

    //             curl_close($ch_medis);
    //         } else {
    //             return "Email and role are required.";
    //         }
    //     } else {
    //         return "Data is required.";
    //     }
    // }
    public function hapusMedis($medisId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $medis_url = $this->api_url . '/inventaris/medis/' . $medisId;

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            if ($response_medis) {
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);

                if ($http_status_code_medis === 200) {
                    $medis_data = json_decode($response_medis, true);
                    $jenisbrgmedis = $medis_data['data']['jenis'];

                    switch ($jenisbrgmedis) {
                        case 'Obat':
                            $jenis_url = $this->api_url . '/inventaris/obat/medis/' . $medisId;
                            break;
                        case 'Alat Kesehatan':
                            $jenis_url = $this->api_url . '/inventaris/alkes/medis/' . $medisId;
                            break;
                        case 'Bahan Habis Pakai':
                            $jenis_url = $this->api_url . '/inventaris/bhp/medis/' . $medisId;
                            break;
                        case 'Darah':
                            $jenis_url = $this->api_url . '/inventaris/darah/medis/' . $medisId;
                            break;
                        default:
                            return "Jenis Barang tidak sesuai";
                    }

                    $ch_jenis = curl_init($jenis_url);
                    curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);
                    $response_jenis = curl_exec($ch_jenis);

                    if ($response_jenis) {
                        $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);

                        if ($http_status_code_jenis === 200) {
                            $jenis_data = json_decode($response_jenis, true);
                            $idjenisbrgmedis = $jenis_data['data']['id'];
                        } else {
                            return "Error fetching jenis barang medis data: " . $response_jenis;
                        }
                    } else {
                        return "Error fetching jenis barang medis data.";
                    }
                    curl_close($ch_jenis);

                    $delete_byidjenis_url = '';
                    switch ($jenisbrgmedis) {
                        case 'Obat':
                            $delete_byidjenis_url = $this->api_url . '/inventaris/obat/' . $idjenisbrgmedis;
                            break;
                        case 'Alat Kesehatan':
                            $delete_byidjenis_url = $this->api_url . '/inventaris/alkes/' . $idjenisbrgmedis;
                            break;
                        case 'Bahan Habis Pakai':
                            $delete_byidjenis_url = $this->api_url . '/inventaris/bhp/' . $idjenisbrgmedis;
                            break;
                        case 'Darah':
                            $delete_byidjenis_url = $this->api_url . '/inventaris/darah/' . $idjenisbrgmedis;
                            break;
                        default:
                            return "Jenis Barang tidak sesuai";
                    }

                    $ch_idjenis = curl_init($delete_byidjenis_url);

                    curl_setopt($ch_idjenis, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch_idjenis, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_idjenis, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_idjenis = curl_exec($ch_idjenis);
                    $http_status_code_idjenis = curl_getinfo($ch_idjenis, CURLINFO_HTTP_CODE);
                    curl_close($ch_idjenis);

                    curl_setopt($ch_medis, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response = curl_exec($ch_medis);
                    $http_status_code = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                    curl_close($ch_medis);

                    if ($http_status_code === 204 && $http_status_code_idjenis === 204) {
                        return redirect()->to(base_url('datamedis?page=1&size=5'));
                    } else {
                        return "Error delete data: " . $response . $response_idjenis;
                    }
                } else {
                    return "Error fetching medis data: " . $response_medis;
                }
            } else {
                return "Error fetching medis data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }
}
