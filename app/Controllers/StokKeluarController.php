<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StokKeluarController extends BaseController
{
    public function dataStokKeluarMedis()
    {
        $title = 'Stok Keluar Medis';

        // Retrieve the value of the 'page' parameter from the request, default to 1 if not present
        $page = $this->request->getGet('page') ?? 1;

        // Retrieve the value of the 'size' parameter from the request, default to 5 if not present
        $size = $this->request->getGet('size') ?? 5;

        // Check if the user is logged in
        // Retrieve the stored JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            // URL for fetching akun data
            $stok_keluar_url = $this->api_url . '/inventaris/stok?page=' . $page . '&size=' . $size;

            $ch_stok_keluar = curl_init($stok_keluar_url);
            curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_stok_keluar = curl_exec($ch_stok_keluar);

            if ($response_stok_keluar) {
                $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);
                if ($http_status_code_stok_keluar === 200) {
                    $stok_keluar_medis_data = json_decode($response_stok_keluar, true);
                    return view('/admin/inventaris/medis/stok_keluar/data_stok_keluar', [
                        'stok_keluar_medis_data' => $stok_keluar_medis_data['data']['stok_keluar_barang_medis'],
                        'meta_data' => $stok_keluar_medis_data['data'],
                        'title' => $title
                    ]);
                } else {
                    return "Response stok keluar data:" . $response_stok_keluar;
                }
            } else {
                return "Error fetching akun data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function tambahStokKeluarMedis()
    {
        $title = 'Tambah Stok Keluar Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventaris/medis';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $penerimaan_url = $this->api_url . '/pengadaan/penerimaan';
            $pegawai_url = $this->api_url . '/pegawai';

            // Initialize cURL for fetching medis data
            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Initialize cURL for fetching pesanan data
            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Initialize cURL for fetching penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Initialize cURL for fetching pegawai data
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute the cURL requests
            $response_medis = curl_exec($ch_medis);
            $response_pesanan = curl_exec($ch_pesanan);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $response_pegawai = curl_exec($ch_pegawai);

            // Check if the responses are successful
            if ($response_medis && $response_pesanan && $response_penerimaan && $response_pegawai) {
                // Decode the JSON responses
                $medis_data = json_decode($response_medis, true);
                $pesanan_data = json_decode($response_pesanan, true);
                $penerimaan_data = json_decode($response_penerimaan, true);
                $pegawai_data = json_decode($response_pegawai, true);

                // Render the view with the fetched data
                return view('/admin/inventaris/medis/stok_keluar/tambah_stok_keluar', [
                    'medis_data' => $medis_data['data'],
                    'pesanan_data' => $pesanan_data['data'],
                    'penerimaan_data' => $penerimaan_data['data'],
                    'pegawai_data' => $pegawai_data['data'],
                    'token' => $token,
                    'title' => $title
                ]);
            } else {
                return "Error fetching data. Response medis: $response_medis, Response pesanan: $response_pesanan, Response penerimaan: $response_penerimaan, Response pegawai: $response_pegawai";
            }

            // Close cURL sessions
            curl_close($ch_medis);
            curl_close($ch_pesanan);
            curl_close($ch_penerimaan);
            curl_close($ch_pegawai);
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
    public function submitTambahStokKeluarMedis()
    {
        if ($this->request->getPost()) {
            $idpengajuan = $this->request->getPost('idpengajuan');


            $idpemesanan = $this->request->getPost('idpemesanan');
            $nofaktur = $this->request->getPost('nofaktur');
            $tgldatang = $this->request->getPost('tgldatang');

            //Stok keluar
            $nokeluar = $this->request->getPost('nokeluar');
            $pegawaistokkeluar = $this->request->getPost('pegawaistokkeluar');
            $tglkeluar = $this->request->getPost('tglkeluar');
            $keteranganstokkeluar = $this->request->getPost('keteranganstokkeluar');

            //Transaksi keluar
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $nobatch = $this->request->getPost('nobatch');
            $nofaktur = $this->request->getPost('nofaktur');
            $jlhkeluar = $this->request->getPost('jlhkeluar');

            // $tglpengajuan = $this->request->getPost('tglpengajuan');
            // $nopengajuan = $this->request->getPost('nopengajuan');
            // $supplier = intval($this->request->getPost('supplier'));
            // $pegawaipengajuan = $this->request->getPost('pegawaipengajuan');
            // $diskonpersen = intval($this->request->getPost('diskonpersen'));
            // $diskonjumlah = intval($this->request->getPost('diskonjumlah'));
            // $pajakpersen = intval($this->request->getPost('pajakpersen'));
            // $pajakjumlah = intval($this->request->getPost('pajakjumlah'));
            // $materai = intval($this->request->getPost('materai'));
            // $catatanpengajuan = $this->request->getPost('catatanpengajuan');
            // $statuspesanan = $this->request->getPost('statuspesanan');

            $stok_keluar_url = $this->api_url . '/inventaris/stok';
            $transaksi_brgmedis_url = $this->api_url . '/inventaris/transaksi';

            $postDataStokKeluar = [
                'no_keluar' => $nokeluar,
                'id_pegawai' => $pegawaistokkeluar,
                'tanggal_stok_keluar' => $tglkeluar,
                'keterangan' => $keteranganstokkeluar,
            ];

            $tambah_stok_keluar_JSON = json_encode($postDataStokKeluar);


            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
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

                            // Execute the cURL request to obat_url
                            $response = curl_exec($ch_transaksi_brgmedis);
                        }

                        if ($response) {
                            $http_status_code_transaksi_brgmedis = curl_getinfo($ch_transaksi_brgmedis, CURLINFO_HTTP_CODE);
                            if ($http_status_code_transaksi_brgmedis === 201) {
                                return redirect()->to(base_url('stokkeluarmedis'));
                            } else {
                                // Error response dari transaksi_brgmedis_url
                                curl_close($ch_transaksi_brgmedis); // Tutup session cURL untuk transaksi_brgmedis_url di sini
                                return "Error Barang Stok Keluar: " . $response;
                            }
                        } else {
                            // Error kirim permintaan ke transaksi_brgmedis_url
                            curl_close($ch_transaksi_brgmedis); // Tutup session cURL untuk transaksi_brgmedis_url di sini
                            return "Error sending request to the transaksi barang medis API.";
                        }
                        // Check if the API request to obat_url was successful

                    } else {
                        // Error response from the API
                        curl_close($ch_stok_keluar); // Tutup session cURL untuk medis_url di sini
                        return "Error Insert Stok Keluar: " . $response_stok_keluar . $tambah_stok_keluar_JSON;
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

    public function editStokKeluarMedis($stokKeluarId)
    {
        if (session()->has('jwt_token')) {
            // Ambil data medis berdasarkan ID barang medis
            $token = session()->get('jwt_token');
            $stok_keluar_url = $this->api_url . '/inventaris/stok/' . $stokKeluarId;
            $barang_stok_keluar_url = $this->api_url . '/inventaris/transaksi/' . $stokKeluarId;

            $ch_stok_keluar = curl_init($stok_keluar_url);
            curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_stok_keluar = curl_exec($ch_stok_keluar);
            $stok_keluar_data = json_decode($response_stok_keluar, true);
            $idpengajuan = $stok_keluar_data['data']['id_pengajuan'];
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

            if ($response_stok_keluar && $response_pesanan && $response_pengajuan) {
                $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                // $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);

                $pengajuan_data = json_decode($response_pengajuan, true);
                $pesanan_data = json_decode($response_pesanan, true);
                // $barang_medis_data = json_decode($response_barang_medis, true);
                if ($http_status_code_stok_keluar === 200 && $http_status_code_pesanan === 200 && $http_status_code_pengajuan === 200) {
                    return view('/admin/inventaris/medis/stok_keluar/edit_stok_keluar', [
                        'stok_keluar_data' => $stok_keluar_data['data'],
                        'pesanan_data' => $pesanan_data['data'],
                        'pengajuan_data' => $pengajuan_data['data'],
                        'stok_keluarId' => $stokKeluarId,
                        'token' => $token,
                        'title' => 'Edit stok_keluar Medis'
                    ]);
                } else {
                    // Error: Gagal mengambil data medis
                    return "Error fetching data. HTTP Status Code stok_keluar: $http_status_code_stok_keluar, HTTP Status Code Pesanan: $http_status_code_pesanan";
                }
            } else {
                // Error: Gagal mengambil respons dari API untuk data medis
                return "Error fetching data.";
            }
            // Tutup sesi cURL untuk data medis dan obat
            curl_close($ch_stok_keluar);
            curl_close($ch_pesanan);
        } else {
            // User belum login
            return "User not logged in. Please log in first.";
        }
    }

    public function submitEditStokKeluarMedis($stok_keluarId)
    {
        if ($this->request->getPost()) {
            $idtransaksi_brgmedis = $this->request->getPost('idtransaksi_brgmedis');
            $idbrgmedis = $this->request->getPost('idbrgmedis');

            //Stok keluar
            $idstokkeluar = $this->request->getPost('idstokkeluar');
            $nokeluar = $this->request->getPost('nokeluar');
            $pegawaitagihan = $this->request->getPost('pegawaitagihan');
            $tglkeluar = intval($this->request->getPost('tglkeluar'));
            $keteranganstokkeluar = $this->request->getPost('keteranganstokkeluar');

            //Transaksi keluar
            $idtransaksibrgmedis = $this->request->getPost('idtransaksibrgmedis');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $nobatch = $this->request->getPost('nobatch');
            $no_faktur = $this->request->getPost('no_faktur');
            $jlhkeluar = $this->request->getPost('jlhkeluar');

            $stok_keluar_url = $this->api_url . '/pengadaan/stok_keluar/' . $stok_keluarId;

            $postDataStokKkeluar = [
                'no_keluar' => $nokeluar,
                'id_pegawai' => $pegawaitagihan,
                'tanggal_stok_keluar' => $tglkeluar,
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

                        for ($i = 0; $i < count($idbrgmedis); $i++) {
                            $transaksi_brgmedis_url = $this->api_url . '/pengadaan/transaksi_brgmedis/' . $idtransaksi_brgmedis[$i];
                            $postDataTransaksiBrgmedis = [
                                'id_stok_keluar' => $idstokkeluar,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'no_batch' => $nobatch[$i],
                                'no_faktur' => $no_faktur[$i],
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
                                return redirect()->to(base_url('stok_keluarmedis'));
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
    public function hapusStokKeluarMedis($stok_keluarId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            // Retrieve the stored JWT token
            $token = session()->get('jwt_token');
            $stok_keluar_url = $this->api_url . '/inventaris/stok/' . $stok_keluarId;

            $ch_stok_keluar = curl_init($stok_keluar_url);
            curl_setopt($ch_stok_keluar, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch_stok_keluar, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_stok_keluar, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_stok_keluar = curl_exec($ch_stok_keluar);
            $http_status_code_stok_keluar = curl_getinfo($ch_stok_keluar, CURLINFO_HTTP_CODE);

            if ($http_status_code_stok_keluar === 204) {
                return redirect()->to(base_url('stok_keluarmedis'));
            } else {
                // Error response from the API
                return "Error deleting stok_keluar." . $response_stok_keluar;
            }
        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
}
