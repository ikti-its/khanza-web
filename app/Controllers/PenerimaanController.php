<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PenerimaanController extends BaseController
{
    public function dataPenerimaanMedis()
    {
        $title = 'Data Penerimaan Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 5;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan?page=' . $page . '&size=' . $size;
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';

            // Initialize cURL for fetching penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);

            // Initialize cURL for fetching pengajuan data
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);

            // Initialize cURL for fetching pemesanan data
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);

            // Check if responses are successful
            if ($response_penerimaan && $response_pengajuan && $response_pemesanan) {
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);

                // Decode responses
                $penerimaan_data = json_decode($response_penerimaan, true);
                $pengajuan_data = json_decode($response_pengajuan, true);
                $pemesanan_data = json_decode($response_pemesanan, true);

                // Check if all responses are successful
                if ($http_status_code_penerimaan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_pemesanan === 200) {
                    // Return view with penerimaan, pengajuan, and pemesanan data
                    return view('/admin/pengadaan/medis/penerimaan/data_penerimaan', [
                        'penerimaan_data' => $penerimaan_data['data']['penerimaan_barang_medis'],
                        'pengajuan_data' => $pengajuan_data['data'],
                        'pemesanan_data' => $pemesanan_data['data'],
                        'meta_data' => $penerimaan_data['data'],
                        'title' => $title
                    ]);
                } else {
                    // Error: Failed to fetch data
                    return "Error fetching data. HTTP Status Code Penerimaan: $http_status_code_penerimaan, HTTP Status Code Pengajuan: $http_status_code_pengajuan, HTTP Status Code Pemesanan: $http_status_code_pemesanan";
                }
            } else {
                // Error: Failed to get responses from the API
                return "Error fetching data.";
            }

            // Close cURL sessions
            curl_close($ch_penerimaan);
            curl_close($ch_pengajuan);
            curl_close($ch_pemesanan);
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }

    public function tambahPenerimaanMedis()
    {
        $title = 'Tambah Penerimaan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';
            $pegawai_url = $this->api_url . '/pegawai';
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pemesanan = curl_exec($ch_pemesanan);

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pegawai = curl_exec($ch_pegawai);

            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_pengajuan = curl_exec($ch_pengajuan);
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
            if ($response_pemesanan) {
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                if ($http_status_code_pemesanan === 200 && $http_status_code_pegawai === 200 && $http_status_code_pengajuan === 200) {
                    $pemesanan_data = json_decode($response_pemesanan, true);
                    $pegawai_data = json_decode($response_pegawai, true);
                    $pengajuan_data = json_decode($response_pengajuan, true);
                    $medis_data = json_decode($response_barang_medis, true);
                    $satuan_data = json_decode($response_satuan, true);
                } else {
                    return "Response pemesanan data: " . $response_pemesanan .
                        "<br><br> Response pegawai data: " . $response_pegawai .
                        "<br><br>Response barang medis data: " . $response_barang_medis .
                        "<br><br>Response satuan data: " . $response_satuan;
                    "<br><br>Response pengajuan data: " . $response_pengajuan;
                }
            } else {
                return "Error fetching data.";
            }

            echo view('/admin/pengadaan/medis/penerimaan/tambah_penerimaan', [
                'pegawai_data' => $pegawai_data['data'],
                'pengajuan_data' => $pengajuan_data['data'],
                'pemesanan_data' => $pemesanan_data['data'],
                'medis_data' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'token' => $token,
                'title' => $title
            ]);
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function submitTambahPenerimaanMedis()
    {
        if ($this->request->getPost()) {
            $idpengajuan = $this->request->getPost('idpengajuan');

            $idpesanan = $this->request->getPost('idpesanan');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuan = $this->request->getPost('satuan');
            $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
            $harga_satuan_pemesanan = $this->request->getPost('harga_satuan_pemesanan');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $jumlah_diterima = $this->request->getPost('jumlah_diterima');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            $no_batch = $this->request->getPost('no_batch');

            $idpemesanan = $this->request->getPost('idpemesanan');
            $nofaktur = $this->request->getPost('nofaktur');
            $tgldatang = $this->request->getPost('tgldatang');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $tgljatuhtempo = $this->request->getPost('tgljatuhtempo');
            $idruangan = intval($this->request->getPost('idruangan'));
            $pegawaipenerimaan = $this->request->getPost('pegawaipenerimaan');


            $tglpengajuan = $this->request->getPost('tglpengajuan');
            $nopengajuan = $this->request->getPost('nopengajuan');
            $pegawaipengajuan = $this->request->getPost('pegawaipengajuan');
            $diskonpersen = intval($this->request->getPost('diskonpersen'));
            $diskonjumlah = intval($this->request->getPost('diskonjumlah'));
            $pajakpersen = intval($this->request->getPost('pajakpersen'));
            $pajakjumlah = intval($this->request->getPost('pajakjumlah'));
            $materai = intval($this->request->getPost('materai'));
            $catatanpengajuan = $this->request->getPost('catatanpengajuan');
            $statuspesanan = $this->request->getPost('statuspesanan');

            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';

            $postDataPenerimaan = [
                'id_pengajuan' => $idpengajuan,
                'id_pemesanan' => $idpemesanan,
                'no_faktur' => $nofaktur,
                'tanggal_datang' => $tgldatang,
                'tanggal_faktur' => $tglfaktur,
                'tanggal_jthtempo' => $tgljatuhtempo,
                'id_pegawai' => $pegawaipenerimaan,
                'id_ruangan' => $idruangan,
            ];
            $tambah_penerimaan_JSON = json_encode($postDataPenerimaan);


            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_penerimaan = curl_init($penerimaan_url);

                curl_setopt($ch_penerimaan, CURLOPT_POST, 1);
                curl_setopt($ch_penerimaan, CURLOPT_POSTFIELDS, ($tambah_penerimaan_JSON));
                curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_penerimaan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_penerimaan = curl_exec($ch_penerimaan);

                if ($response_penerimaan) {
                    $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_penerimaan === 201) {
                        $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
                        $putDataPengajuan = [
                            'tanggal_pengajuan' => $tglpengajuan,
                            'nomor_pengajuan' => $nopengajuan,
                            'id_pegawai' => $pegawaipengajuan,
                            'diskon_persen' => $diskonpersen,
                            'diskon_jumlah' => $diskonjumlah,
                            'pajak_persen' => $pajakpersen,
                            'pajak_jumlah' => $pajakjumlah,
                            'materai' => $materai,
                            'status_pesanan' => $statuspesanan,
                            'catatan' => $catatanpengajuan,
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

                                for ($i = 0; $i < count($idbrgmedis); $i++) {
                                    $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                                    $postDataPesanan = [
                                        'id_pengajuan' => $idpengajuan,
                                        'id_barang_medis' => $idbrgmedis[$i],
                                        'satuan' => intval($satuan[$i]),
                                        'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                                        'harga_satuan_pemesanan' => intval($harga_satuan_pemesanan[$i]),
                                        'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                                        'jumlah_diterima' => intval($jumlah_diterima[$i]),
                                        'kadaluwarsa' => $kadaluwarsa[$i],
                                        'no_batch' => $no_batch[$i]
                                    ];
                                    $tambah_pesanan_JSON = json_encode($postDataPesanan);
                                    $ch_pesanan = curl_init($pesanan_url);
                                    curl_setopt($ch_pesanan, CURLOPT_CUSTOMREQUEST, "PUT");
                                    curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, $tambah_pesanan_JSON);
                                    curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                                        'Content-Type: application/json',
                                        'Authorization: Bearer ' . $token,
                                    ]);

                                    // Execute the cURL request to obat_url
                                    $response = curl_exec($ch_pesanan);
                                }

                                if ($response) {
                                    $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                                    if ($http_status_code_pesanan === 200) {
                                        return redirect()->to(base_url('penerimaanmedis'));
                                    } else {
                                        // Error response dari pesanan_url
                                        curl_close($ch_pesanan); // Tutup session cURL untuk pesanan_url di sini
                                        return "Error Update Pesanan: " . $response;
                                    }
                                } else {
                                    // Error kirim permintaan ke pesanan_url
                                    curl_close($ch_pesanan); // Tutup session cURL untuk pesanan_url di sini
                                    return "Error sending request to the pesanan API.";
                                }
                                curl_close($ch_penerimaan);
                                curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                                // Tutup session cURL untuk medis_url di sini

                            } else {
                                // Error response dari obat_url
                                curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                                return "Error Update Pengajuan: " . $response_pengajuan;
                            }
                        } else {
                            // Error kirim permintaan ke obat_url
                            curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                            return "Error sending request to the obat API.";
                        }
                    } else {
                        // Error response from the API
                        curl_close($ch_penerimaan); // Tutup session cURL untuk medis_url di sini
                        return "Error Insert Penerimaan: " . $response_penerimaan;
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

    public function editPenerimaanMedis($penerimaanId)
    {
        if (session()->has('jwt_token')) {
            // Ambil data medis berdasarkan ID barang medis
            $token = session()->get('jwt_token');
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan/' . $penerimaanId;
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';
            $pegawai_url = $this->api_url . '/pegawai';
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $penerimaan_data = json_decode($response_penerimaan, true);
            $idpengajuan = $penerimaan_data['data']['id_pengajuan'];
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $idpengajuan;

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

            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);

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

            // Check if responses are successful
            if ($response_penerimaan && $response_pengajuan && $response_pesanan && $response_pemesanan && $response_pegawai && $response_barang_medis && $response_satuan) {
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

                // Decode responses
                $pengajuan_data = json_decode($response_pengajuan, true);
                $pesanan_data = json_decode($response_pesanan, true);
                $pemesanan_data = json_decode($response_pemesanan, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $medis_data = json_decode($response_barang_medis, true);
                $satuan_data = json_decode($response_satuan, true);

                // Check if all responses are successful
                if ($http_status_code_penerimaan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_pesanan === 200 && $http_status_code_pemesanan === 200 && $http_status_code_pegawai === 200 && $http_status_code_barang_medis === 200 && $http_status_code_satuan === 200) {
                    // Return view with all data
                    return view('/admin/pengadaan/medis/penerimaan/edit_penerimaan', [
                        'penerimaan_data' => $penerimaan_data['data'],
                        'pengajuan_data' => $pengajuan_data['data'],
                        'pesanan_data' => $pesanan_data['data'],
                        'pemesanan_data' => $pemesanan_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'medis_data' => $medis_data['data'],
                        'satuan_data' => $satuan_data['data'],
                        'penerimaanId' => $penerimaanId,
                        'token' => $token,
                        'title' => 'Edit penerimaan Medis'
                    ]);
                } else {
                    // Error: Failed to fetch data
                    return "Error fetching data.";
                }
            } else {
                // Error: Failed to get responses from the API
                return "Error fetching data.";
            }

            // Close cURL sessions
            curl_close($ch_penerimaan);
            curl_close($ch_pengajuan);
            curl_close($ch_pesanan);
            curl_close($ch_pemesanan);
            curl_close($ch_pegawai);
            curl_close($ch_barang_medis);
            curl_close($ch_satuan);
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }

    public function submitEditPenerimaanMedis($penerimaanId)
    {
        if ($this->request->getPost()) {
            $idpengajuan = $this->request->getPost('idpengajuan');

            $idpesanan = $this->request->getPost('idpesanan');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuan = $this->request->getPost('satuan');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $jumlah_diterima = $this->request->getPost('jumlah_diterima');
            $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
            $harga_satuan_pemesanan = $this->request->getPost('harga_satuan_pemesanan');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            $no_batch = $this->request->getPost('no_batch');

            $idpemesanan = $this->request->getPost('idpemesanan');
            $nofaktur = $this->request->getPost('nofaktur');
            $tgldatang = $this->request->getPost('tgldatang');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $tgljatuhtempo = $this->request->getPost('tgljatuhtempo');
            $idruangan = intval($this->request->getPost('idruangan'));
            $pegawaipenerimaan = $this->request->getPost('pegawaipenerimaan');

            $tglpengajuan = $this->request->getPost('tglpengajuan');
            $nopengajuan = $this->request->getPost('nopengajuan');
            $pegawaipengajuan = $this->request->getPost('pegawaipengajuan');
            $diskonpersen = intval($this->request->getPost('diskonpersen'));
            $diskonjumlah = intval($this->request->getPost('diskonjumlah'));
            $pajakpersen = intval($this->request->getPost('pajakpersen'));
            $pajakjumlah = intval($this->request->getPost('pajakjumlah'));
            $materai = intval($this->request->getPost('materai'));
            $catatanpengajuan = $this->request->getPost('catatanpengajuan');
            $statuspesanan = $this->request->getPost('statuspesanan');

            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan/' . $penerimaanId;

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $postDataPenerimaan = [
                    'id_pengajuan' => $idpengajuan,
                    'id_pemesanan' => $idpemesanan,
                    'no_faktur' => $nofaktur,
                    'tanggal_datang' => $tgldatang,
                    'tanggal_faktur' => $tglfaktur,
                    'tanggal_jthtempo' => $tgljatuhtempo,
                    'id_pegawai' => $pegawaipenerimaan,
                    'id_ruangan' => $idruangan,
                ];
                $tambah_penerimaan_JSON = json_encode($postDataPenerimaan);
                $ch_penerimaan = curl_init($penerimaan_url);

                curl_setopt($ch_penerimaan, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_penerimaan, CURLOPT_POSTFIELDS, ($tambah_penerimaan_JSON));
                curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ]);

                $response_penerimaan = curl_exec($ch_penerimaan);

                if ($response_penerimaan) {
                    $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_penerimaan === 200) {
                        for ($i = 0; $i < count($idbrgmedis); $i++) {
                            $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                            $postDataPesanan = [
                                'id_pengajuan' => $idpengajuan,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'satuan' => intval($satuan[$i]),
                                'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                                'harga_satuan_pemesanan' => intval($harga_satuan_pemesanan[$i]),
                                'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                                'jumlah_diterima' => intval($jumlah_diterima[$i]),
                                'kadaluwarsa' => $kadaluwarsa[$i],
                                'no_batch' => $no_batch[$i]
                            ];
                            $tambah_pesanan_JSON = json_encode($postDataPesanan);
                            $ch_pesanan = curl_init($pesanan_url);
                            curl_setopt($ch_pesanan, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, $tambah_pesanan_JSON);
                            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Authorization: Bearer ' . $token,
                            ]);

                            // Execute the cURL request to obat_url
                            $response = curl_exec($ch_pesanan);
                        }

                        if ($response) {
                            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_pesanan === 200) {
                                $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
                                $putDataPengajuan = [
                                    'tanggal_pengajuan' => $tglpengajuan,
                                    'nomor_pengajuan' => $nopengajuan,
                                    'id_pegawai' => $pegawaipengajuan,
                                    'diskon_persen' => $diskonpersen,
                                    'diskon_jumlah' => $diskonjumlah,
                                    'pajak_persen' => $pajakpersen,
                                    'pajak_jumlah' => $pajakjumlah,
                                    'materai' => $materai,
                                    'status_pesanan' => $statuspesanan,
                                    'catatan' => $catatanpengajuan,
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
                                if ($response_pengajuan) {
                                    $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                                    if ($http_status_code_pengajuan === 200) {

                                        return redirect()->to(base_url('penerimaanmedis'));
                                    } else {
                                        // Error response dari pesanan_url
                                        return "Error Update Pesanan: " . $response_pengajuan;
                                    }
                                } else {
                                    // Error kirim permintaan ke pesanan_url
                                    curl_close($ch_pesanan); // Tutup session cURL untuk pesanan_url di sini
                                    return "Error sending request to the pesanan API.";
                                }
                                curl_close($ch_pengajuan); // Tutup session cURL untuk pesanan_url di sini
                            } else {
                                // Error response dari pesanan_url
                                curl_close($ch_pesanan); // Tutup session cURL untuk pesanan_url di sini
                                return "Error Update Pesanan: " . $response;
                            }
                        } else {
                            // Error kirim permintaan ke pesanan_url
                            curl_close($ch_pesanan); // Tutup session cURL untuk pesanan_url di sini
                            return "Error sending request to the pesanan API.";
                        }
                        curl_close($ch_penerimaan);
                    } else {
                        // Error response from the API
                        curl_close($ch_penerimaan); // Tutup session cURL untuk medis_url di sini
                        return "Error Insert Penerimaan: " . $response_penerimaan;
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

    public function hapusPenerimaanMedis($penerimaanId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan/' . $penerimaanId;

            $ch_penerimaan = curl_init($penerimaan_url);

            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $penerimaan_data = json_decode($response_penerimaan, true);
            $pengajuanId = $penerimaan_data['data']['id_pengajuan'];
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;
            if ($response_penerimaan) {
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                if ($http_status_code_penerimaan === 200) {
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
                        'diskon_persen' => $pengajuan_data['data']['diskon_persen'],
                        'diskon_jumlah' => $pengajuan_data['data']['diskon_jumlah'],
                        'pajak_persen' => $pengajuan_data['data']['pajak_persen'],
                        'pajak_jumlah' => $pengajuan_data['data']['pajak_jumlah'],
                        'materai' => $pengajuan_data['data']['materai'],
                        'status_pesanan' => '3',
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
                            $ch_delete_penerimaan = curl_init($penerimaan_url);
                            curl_setopt($ch_delete_penerimaan, CURLOPT_CUSTOMREQUEST, "DELETE");
                            curl_setopt($ch_delete_penerimaan, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_delete_penerimaan, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $token,
                            ]);
                            // Execute the cURL request to obat_url
                            $response_penerimaan = curl_exec($ch_delete_penerimaan);
                            $http_status_code_penerimaan = curl_getinfo($ch_delete_penerimaan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_penerimaan === 204) {
                                return redirect()->to(base_url('penerimaanmedis'));
                            } else {
                                // Error response from the API
                                return "Error deleting penerimaan: " . $response_penerimaan;
                            }
                            curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                            // Tutup session cURL untuk medis_url di sini

                        } else {
                            // Error response dari obat_url
                            curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                            return "Error Update Pengajuan: " . $response_pengajuan;
                        }
                        curl_close($ch_pengajuan);
                        curl_close($ch_penerimaan);
                    } else {
                        // Error kirim permintaan ke obat_url
                        curl_close($ch_pengajuan); // Tutup session cURL untuk obat_url di sini
                        return "Error sending request to the obat API.";
                    }
                } else {
                    return "Error mendapatkan data pengajuan: " . $response_penerimaan;
                }
            } else {
                return "Error mendapatkan data pengajuan.";
            }
            //delete penerimaan

        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
}
