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
            $jenis_url = [
                'obat' => $this->api_url . '/inventaris/obat',
                'alkes' => $this->api_url . '/inventaris/alkes',
                'bhp' => $this->api_url . '/inventaris/bhp',
                'darah' => $this->api_url . '/inventaris/darah'
            ];

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            $ch_medis_tanpa_params = curl_init($medis_tanpa_params_url);
            curl_setopt($ch_medis_tanpa_params, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis_tanpa_params, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis_tanpa_params = curl_exec($ch_medis_tanpa_params);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);

            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);

            if ($response_medis && $response_medis_tanpa_params && $response_satuan && $response_penerimaan && $response_pesanan) {
                // Handle medis data
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                if ($http_status_code_medis === 200) {
                    $medis_data = json_decode($response_medis, true);
                    $medis_tanpa_params_data = json_decode($response_medis_tanpa_params, true);
                    $satuan_data = json_decode($response_satuan, true);

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
                                'meta_data' => $medis_data['data'],
                                'title' => $title
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
                    $satuan_data = json_decode($response_satuan, true);
                    return view('/admin/inventaris/medis/tambah_medis', [
                        'satuan_data' => $satuan_data['data'],
                        'title' => $title
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
            $nama = $this->request->getPost('nama');
            $jenisbrgmedis = $this->request->getPost('jenisbrgmedis');
            $harga = intval($this->request->getPost('harga'));
            $stok = intval($this->request->getPost('stok'));
            if ($jenisbrgmedis === 'Obat') {
                $satbrgmedis = intval($this->request->getPost('satuanobat'));
            } elseif ($jenisbrgmedis === 'Alat Kesehatan') {
                $satbrgmedis = intval($this->request->getPost('satuanalkes'));
            } elseif ($jenisbrgmedis === 'Bahan Habis Pakai') {
                $satbrgmedis = intval($this->request->getPost('satuanbhp'));
            } elseif ($jenisbrgmedis === 'Darah') {
                $satbrgmedis = intval($this->request->getPost('satuandarah'));
            }
            //Obat
            $industrifarmasi = intval($this->request->getPost('industrifarmasi'));
            $kandungan = $this->request->getPost('kandungan');
            $satkecil = intval($this->request->getPost('satkecil'));
            $isi = intval($this->request->getPost('isi'));
            $kapasitas = intval($this->request->getPost('kapasitas'));
            $jenisobat = intval($this->request->getPost('jenisobat'));
            $kategori = intval($this->request->getPost('kategoriobat'));
            $golongan = intval($this->request->getPost('golonganobat'));
            $kadaluwarsa = $this->request->getPost('kadaluwarsaobat');
            $notifkadaluwarsa = intval($this->request->getPost('notifkadaluwarsa'));
            $stokminimum = intval($this->request->getPost('stokminimum'));

            //Alkes
            $merekalkes = $this->request->getPost('merekalkes');

            //BHP
            $jumlahbhp = intval($this->request->getPost('jumlahbhp'));
            $kadaluwarsabhp = $this->request->getPost('kadaluwarsabhp');

            //Darah
            $keterangandarah = $this->request->getPost('keterangandarah');
            $jenisdarah = $this->request->getPost('jenisdarah');
            $kadaluwarsadarah = $this->request->getPost('kadaluwarsadarah');

            $medis_url = $this->api_url . '/inventaris/medis';
            $obat_url = $this->api_url . '/inventaris/obat';
            $alkes_url = $this->api_url . '/inventaris/alkes';
            $bhp_url = $this->api_url . '/inventaris/bhp';
            $darah_url = $this->api_url . '/inventaris/darah';

            // Prepare the data to be sent to the API
            $postDataMedis = [
                'nama' => $nama,
                'satuan' => $satbrgmedis,
                'jenis' => $jenisbrgmedis,
                'harga' => $harga,
                'stok' => $stok,
                'stok_minimum' => $stokminimum,
                'notifikasi_kadaluwarsa_hari' => $notifkadaluwarsa,
            ];
            $tambah_medis_JSON = json_encode($postDataMedis);
            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');

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
                $response_data = json_decode($response_medis, true);

                if ($response_medis) {
                    $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                    if ($http_status_code_medis === 201) {
                        $id_barang_medis = $response_data['data']['id'];
                        if ($jenisbrgmedis === 'Obat') {
                            $postDataJSON = [
                                'id_barang_medis' => $id_barang_medis,
                                'industri_farmasi' => $industrifarmasi,
                                'kandungan' => $kandungan,
                                'satuan' => $satkecil,
                                'isi' => $isi,
                                'kapasitas' => $kapasitas,
                                'jenis' => $jenisobat,
                                'kategori' => $kategori,
                                'golongan' => $golongan,
                                'kadaluwarsa' => $kadaluwarsa
                            ];
                            $postURL = $obat_url;
                            $tambah_data_JSON = json_encode($postDataJSON);
                        } elseif ($jenisbrgmedis === 'Alat Kesehatan') {
                            $postDataJSON = [
                                'id_barang_medis' => $id_barang_medis,
                                'merek' => $merekalkes
                            ];
                            $postURL = $alkes_url;
                            $tambah_data_JSON = json_encode($postDataJSON);
                        } elseif ($jenisbrgmedis === 'Bahan Habis Pakai') {
                            $postDataJSON = [
                                'id_barang_medis' => $id_barang_medis,
                                'jumlah' => $jumlahbhp,
                                'kadaluwarsa' => $kadaluwarsabhp
                            ];
                            $postURL = $bhp_url;
                            $tambah_data_JSON = json_encode($postDataJSON);
                        } elseif ($jenisbrgmedis === 'Darah') {
                            $postDataJSON = [
                                'id_barang_medis' => $id_barang_medis,
                                'jenis' => $jenisdarah,
                                'keterangan' => $keterangandarah,
                                'kadaluwarsa' => $kadaluwarsadarah
                            ];
                            $postURL = $darah_url;
                            $tambah_data_JSON = json_encode($postDataJSON);
                        } else {
                            return "Jenis Barang Medis Belum dipilih";
                        }

                        $ch = curl_init($postURL);

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, ($tambah_data_JSON));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($tambah_data_JSON),
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response = curl_exec($ch);

                        if ($response) {
                            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            if ($http_status_code === 201) {
                                return redirect()->to(base_url('datamedis'));
                            } else {
                                return "Error Insert: " . $response;
                            }
                            curl_close($ch_medis);
                            curl_close($ch);
                        } else {
                            return "Error sending request to the obat API.";
                        }
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
                    // Data medis berhasil diambil
                    $medis_data = json_decode($response_medis, true);
                    if ($medis_data['data']['jenis'] === 'Obat') {
                        $jenis_url = $this->api_url . '/inventaris/obat/medis/' . $medisId;
                    } elseif ($medis_data['data']['jenis'] === 'Alat Kesehatan') {
                        $jenis_url = $this->api_url . '/inventaris/alkes/medis/' . $medisId;
                    } elseif ($medis_data['data']['jenis'] === 'Bahan Habis Pakai') {
                        $jenis_url = $this->api_url . '/inventaris/bhp/medis/' . $medisId;
                    } elseif ($medis_data['data']['jenis'] === 'Darah') {
                        $jenis_url = $this->api_url . '/inventaris/darah/medis/' . $medisId;
                    } else {
                        echo "Jenis Barang tidak sesuai";
                    }

                    $ch = curl_init($jenis_url);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_jenis = curl_exec($ch);

                    $ch_satuan = curl_init($satuan_url);
                    curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_satuan = curl_exec($ch_satuan);

                    if ($response_jenis && $response_satuan) {
                        $http_status_code_jenis = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                        if ($http_status_code_jenis === 200 && $http_status_code_satuan === 200) {
                            $jenis_data = json_decode($response_jenis, true);
                            $satuan_data = json_decode($response_satuan, true);

                            return view('/admin/inventaris/medis/edit_medis', [
                                'medis_data' => $medis_data['data'],
                                'jenis_data' => $jenis_data['data'], // Masukkan data obat ke dalam view
                                'satuan_data' => $satuan_data['data'], // Masukkan data obat ke dalam view
                                'medisId' => $medisId,
                                'title' => 'Edit Medis'
                            ]);
                        } else {
                            return "Response jenis:" . $response_jenis . "<br><br>Response Satuan:" . $response_satuan;
                        }
                        curl_close($ch);
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

                    if ($http_status_code_idjenis === 204) {
                        curl_setopt($ch_medis, CURLOPT_CUSTOMREQUEST, "DELETE");
                        curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response = curl_exec($ch_medis);
                        $http_status_code = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                        curl_close($ch_medis);

                        if ($http_status_code === 204) {
                            return redirect()->to(base_url('datamedis?page=1&size=5'));
                        } else {
                            return "Error deleting medis: " . $response;
                        }
                    } else {
                        return "Error deleting related jenis barang medis: " . $response_idjenis;
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
