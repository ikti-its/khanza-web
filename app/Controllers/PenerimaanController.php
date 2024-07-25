<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class PenerimaanController extends BaseController
{
    public function dataPenerimaanMedis()
    {
        $title = 'Data Penerimaan Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 5;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $penerimaan_url = $this->api_url . '/inventory/penerimaan';
            $detail_url = $this->api_url . '/inventory/detail';
            $medis_url = $this->api_url . '/inventory/barang';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $supplier_url = $this->api_url . '/ref/inventory/supplier';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $pegawai_url = $this->api_url . '/pegawai';

            // Initialize cURL for fetching penerimaan data
            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
            $ch_detail = curl_init($detail_url);
            curl_setopt($ch_detail, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_detail, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_detail = curl_exec($ch_detail);

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_supplier = curl_exec($ch_supplier);
            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_ruangan = curl_exec($ch_ruangan);

            // Initialize cURL for fetching pegawai data
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            // Check if responses are successful
            if ($response_penerimaan && $response_pegawai && $response_ruangan) {
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);

                // Decode responses
                $penerimaan_data = json_decode($response_penerimaan, true);
                $detail_data = json_decode($response_detail, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $medis_data = json_decode($response_medis, true);
                $satuan_data = json_decode($response_satuan, true);
                $supplier_data = json_decode($response_supplier, true);
                $ruangan_data = json_decode($response_ruangan, true);

                if ($http_status_code_detail === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pegawai === 200 && $http_status_code_satuan === 201 && $http_status_code_ruangan === 201) {
                    // Return view with penerimaan, pengajuan, pemesanan, pegawai, medis, satuan, and pesanan data
                    $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Penerimaan', 'penerimaanmedis');

                    $breadcrumbs = $this->getBreadcrumbs();
                    return view('/admin/pengadaan/medis/penerimaan/data_penerimaan', [
                        'penerimaan_data' => $penerimaan_data['data'],
                        'detail_data' => $detail_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'medis_data' => $medis_data['data'],
                        'satuan_data' => $satuan_data['data'],
                        'supplier_data' => $supplier_data['data'],
                        'ruangan_data' => $ruangan_data['data'],
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {

                    return "Error fetching data. HTTP Status Code Penerimaan: $http_status_code_penerimaan".$response_detail;
                }
            } else {

                return "Error fetching data.";
            }

            // Close cURL sessions
            curl_close($ch_penerimaan);
            curl_close($ch_ruangan);
            curl_close($ch_pegawai);
        } else {
            // User not logged in
            return $this->renderErrorView(401);
        }
    }

    public function tambahPenerimaanMedisbyId($idpemesanan)
    {
        $title = 'Tambah Penerimaan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $api_url = $this->api_url;
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan/' . $idpemesanan;
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $supplier_url = $this->api_url . '/inventaris/supplier';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $pegawai_url = $this->api_url . '/pegawai';

            // Ambil data pegawai
            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
            curl_close($ch_pegawai);

            // Ambil data pemesanan
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);
            $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
            curl_close($ch_pemesanan);

            // Ambil idpengajuan dari data pemesanan
            $pemesanan_data = json_decode($response_pemesanan, true);
            $idpengajuan = $pemesanan_data['data']['id_pengajuan'];

            // Ambil data pengajuan
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);
            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
            curl_close($ch_pengajuan);

            // Ambil data pesanan berdasarkan idpengajuan
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $idpengajuan;
            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);
            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
            curl_close($ch_pesanan);

            // Ambil data barang medis
            $ch_barang_medis = curl_init($barang_medis_url);
            curl_setopt($ch_barang_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_barang_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_barang_medis = curl_exec($ch_barang_medis);
            $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
            curl_close($ch_barang_medis);

            // Ambil data supplier
            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_supplier = curl_exec($ch_supplier);
            $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);
            curl_close($ch_supplier);

            // Ambil data satuan
            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            curl_close($ch_satuan);

            // Cek response dan status code
            if ($http_status_code_pegawai === 200 && $http_status_code_pemesanan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_pesanan === 200 && $http_status_code_barang_medis === 200 && $http_status_code_supplier === 200 && $http_status_code_satuan === 200) {
                $pegawai_data = json_decode($response_pegawai, true);
                $pemesanan_data = json_decode($response_pemesanan, true);
                $pengajuan_data = json_decode($response_pengajuan, true);
                if ($pengajuan_data['data']['status_pesanan'] !== '3') {
                    return redirect('pemesananmedis')->with('warning', 'Status harus dalam pemesanan!');
                }
                $pesanan_data = json_decode($response_pesanan, true);
                $medis_data = json_decode($response_barang_medis, true);
                $supplier_data = json_decode($response_supplier, true);
                $satuan_data = json_decode($response_satuan, true);
            } else {
                // Handle jika ada response tidak berhasil
                return "Response pegawai data:" . $response_pegawai .
                    "<br><br>Response pemesanan data:" . $response_pemesanan .
                    "<br><br>Response pengajuan data:" . $response_pengajuan .
                    "<br><br>Response pesanan data:" . $response_pesanan .
                    "<br><br>Response barang medis data:" . $response_barang_medis .
                    "<br><br>Response Supplier:" . $response_supplier .
                    "<br><br>Response Satuan:" . $response_satuan;
            }

            $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Penerimaan', 'penerimaanmedis');
            $this->addBreadcrumb('Tambah', 'tambahpenerimaanmedis');

            $breadcrumbs = $this->getBreadcrumbs();
            // Tampilkan view dengan data yang sudah diambil
            echo view('/admin/pengadaan/medis/penerimaan/penerimaan', [
                'pegawai_data' => $pegawai_data['data'],
                'pemesanan_data' => $pemesanan_data['data'],
                'pengajuan_data' => $pengajuan_data['data'],
                'pesanan_data' => $pesanan_data['data'],
                'medis_data' => $medis_data['data'],
                'supplier_data' => $supplier_data['data'],
                'satuan_data' => $satuan_data['data'],
                'api_url' => $api_url,
                'token' => $token,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return "User not logged in. Please log in first.";
        }
    }

    public function tambahPenerimaanMedis()
    {
        $title = 'Tambah Penerimaan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pegawai_url = $this->api_url . '/pegawai';
            $barang_medis_url = $this->api_url . '/inventory/barang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $supplier_url = $this->api_url . '/ref/inventory/supplier';

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
            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_supplier = curl_exec($ch_supplier);

            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_ruangan = curl_exec($ch_ruangan);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
            $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
            $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);
            if ($http_status_code_pegawai !== 200) {
                return $this->renderErrorView($http_status_code_pegawai);
            }
            if ($http_status_code_barang_medis !== 200) {
                return $this->renderErrorView($http_status_code_barang_medis);
            }
            if ($http_status_code_ruangan !== 201) {
                return $this->renderErrorView($http_status_code_ruangan);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_supplier !== 201) {
                return $this->renderErrorView($http_status_code_supplier);
            }
            $pegawai_data = json_decode($response_pegawai, true);
            $medis_data = json_decode($response_barang_medis, true);
            $satuan_data = json_decode($response_satuan, true);
            $supplier_data = json_decode($response_supplier, true);
            $ruangan_data = json_decode($response_ruangan, true);

            echo view('/admin/pengadaan/medis/penerimaan/tambah_penerimaan', [
                'pegawai_data' => $pegawai_data['data'],
                'medis_data' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'supplier_data' => $supplier_data['data'],
                'ruangan_data' => $ruangan_data['data'],
                'token' => $token,
                'title' => $title
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahPenerimaanMedis()
    {
        if ($this->request->getPost()) {
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuan = $this->request->getPost('satuanbeli');

            $ubahmaster = $this->request->getPost('ubahmaster') ?? '0';
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $subtotal_per_item = $this->request->getPost('subtotalperitem');
            $diskon_persen = $this->request->getPost('diskonpersenperitem');
            $diskon_jumlah = $this->request->getPost('diskonjumlahperitem');
            $total_per_item = $this->request->getPost('totalperitem');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');

            $no_batch = $this->request->getPost('no_batch');

            $nopemesanan = $this->request->getPost('nopemesanan');
            $nofaktur = $this->request->getPost('nofaktur');
            $supplier = $this->request->getPost('supplier');
            $tglpenerimaan = $this->request->getPost('tglpenerimaan');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $tgljatuhtempo = $this->request->getPost('tgljatuhtempo');
            $idruangan = $this->request->getPost('idruangan');
            $pegawaipenerimaan = $this->request->getPost('pegawaipenerimaan');

            if (session()->has('jwt_token')) {

                $token = session()->get('jwt_token');
                $penerimaan_url = $this->api_url . '/inventory/penerimaan';

                $postDataPenerimaan = [
                    'no_pemesanan' => $nopemesanan,
                    'no_faktur' => $nofaktur,
                    'id_supplier' => intval($supplier),
                    'tanggal_datang' => $tglpenerimaan,
                    'tanggal_faktur' => $tglfaktur,
                    'tanggal_jthtempo' => $tgljatuhtempo,
                    'id_pegawai' => $pegawaipenerimaan,
                    'id_ruangan' => intval($idruangan),
                ];
                $tambah_penerimaan_JSON = json_encode($postDataPenerimaan);
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
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);

                if ($response_penerimaan) {
                    if ($http_status_code_penerimaan === 201) {
                        $penerimaan_data = json_decode($response_penerimaan, true);
                        $idpenerimaan = $penerimaan_data['data']['id'];
                        for ($i = 0; $i < count($idbrgmedis); $i++) {
                            $detail_url = $this->api_url . '/inventory/penerimaan';
                            if ($kadaluwarsa[$i] === "") {
                                $kadaluwarsaformat[$i] = '0001-01-01';
                            } else {
                                $kadaluwarsaformat[$i] = $kadaluwarsa[$i];
                            }
                            $postDatadetail = [
                                'id_penerimaan' => $idpenerimaan,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'id_satuan' => intval($satuan[$i]),
                                'ubah_master' => $ubahmaster[$i],
                                'jumlah' => 0,
                                'subtotal_per_item' => intval($subtotal_per_item[$i]),
                                'diskon_persen' => intval($diskon_persen[$i]),
                                'diskon_jumlah' => intval($diskon_jumlah[$i]),
                                'total_per_item' => intval($total_per_item[$i]),
                                'jumlah_diterima' => intval($jumlah_pesanan[$i]),
                                'kadaluwarsa' => $kadaluwarsaformat[$i],
                                'no_batch' => $no_batch[$i]
                            ];
                            $tambah_detail_JSON = json_encode($postDatadetail);
                            $ch_detail = curl_init($detail_url);
                            curl_setopt($ch_detail, CURLOPT_POST, 1);
                            curl_setopt($ch_detail, CURLOPT_POSTFIELDS, $tambah_detail_JSON);
                            curl_setopt($ch_detail, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_detail, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($tambah_detail_JSON),
                                'Authorization: Bearer ' . $token,
                            ]);

                            // Execute the cURL request to obat_url
                            $response = curl_exec($ch_detail);
                        }
                        $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
                        if ($response) {
                            if ($http_status_code_detail === 201) {
                                return redirect()->to(base_url('penerimaanmedis'));
                            } else {
                                return $response . $tambah_detail_JSON . $tambah_penerimaan_JSON;
                            }
                        } else {
                            return $response;
                        }
                    } else {
                        return $this->renderErrorView($http_status_code_penerimaan);
                    }
                } else {
                    return $this->renderErrorView(500);
                }
            } else {
                // Email or role is empty
                return $this->renderErrorView(401);
            }
        } else {
            // Email or role is empty
            return "Data is required.";
        }
    }

    public function editPenerimaanMedis($penerimaanId)
    {
        $title = 'Edit Penerimaan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pegawai_url = $this->api_url . '/pegawai';
            $penerimaan_url = $this->api_url . '/inventory/penerimaan' . $penerimaanId;
            $barang_medis_url = $this->api_url . '/inventory/barang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';

            $ch_penerimaan = curl_init($penerimaan_url);
            curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_penerimaan = curl_exec($ch_penerimaan);
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

            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $response_ruangan = curl_exec($ch_ruangan);
            $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
            $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
            $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            if ($http_status_code_penerimaan !== 200) {
                return $this->renderErrorView($http_status_code_penerimaan);
            }
            if ($http_status_code_pegawai !== 200) {
                return $this->renderErrorView($http_status_code_pegawai);
            }
            if ($http_status_code_barang_medis !== 200) {
                return $this->renderErrorView($http_status_code_barang_medis);
            }
            if ($http_status_code_ruangan !== 201) {
                return $this->renderErrorView($http_status_code_ruangan);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            $penerimaan_data = json_decode($response_penerimaan, true);
            $pegawai_data = json_decode($response_pegawai, true);
            $medis_data = json_decode($response_barang_medis, true);
            $satuan_data = json_decode($response_satuan, true);
            $ruangan_data = json_decode($response_ruangan, true);



            echo view('/admin/pengadaan/medis/penerimaan/edit_penerimaan', [
                'penerimaan_data' => $penerimaan_data['data'],
                'pegawai_data' => $pegawai_data['data'],
                'medis_data' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'ruangan_data' => $ruangan_data['data'],
                'token' => $token,
                'title' => $title
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitEditPenerimaanMedis($penerimaanId)
    {
        if ($this->request->getPost()) {
            $idpengajuan = $this->request->getPost('idpengajuan');

            $idpesanan = $this->request->getPost('idpesanan');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuan = $this->request->getPost('satuanbeli');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $jumlah_diterima = $this->request->getPost('jumlah_diterima');
            $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
            $harga_satuan_pemesanan = $this->request->getPost('harga_satuan_pemesanan');
            $diskonpersenperitem = $this->request->getPost('diskonpersenperitem');
            $diskonjumlahperitem = $this->request->getPost('diskonjumlahperitem');
            $subtotalperitem = $this->request->getPost('subtotalperitem');
            $totalperitem = $this->request->getPost('totalperitem');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');

            $no_batch = $this->request->getPost('no_batch');

            $idpemesanan = $this->request->getPost('idpemesanan');
            $nofaktur = $this->request->getPost('nofaktur');
            $tgldatang = $this->request->getPost('tgldatang');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $tgljatuhtempo = $this->request->getPost('tgljatuhtempo');
            $idruangan = intval($this->request->getPost('idruangan'));
            $pegawaipenerimaan = $this->request->getPost('pegawaipenerimaan');

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
                            if ($kadaluwarsa[$i] === "") {
                                $kadaluwarsaformat[$i] = '0001-01-01';
                            } else {
                                $kadaluwarsaformat[$i] = $kadaluwarsa[$i]; // Gunakan nilai $kadaluwarsa yang sudah ada
                            }
                            $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                            $postDataPesanan = [
                                'id_pengajuan' => $idpengajuan,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'satuan' => intval($satuan[$i]),
                                'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                                'harga_satuan_pemesanan' => intval($harga_satuan_pemesanan[$i]),
                                'diskon_persen' => intval($diskonpersenperitem[$i]),
                                'diskon_jumlah' => intval($diskonjumlahperitem[$i]),
                                'total_per_item' => intval($totalperitem[$i]),
                                'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                                'jumlah_diterima' => intval($jumlah_diterima[$i]),
                                'subtotal_per_item' => intval($subtotalperitem[$i]),
                                'kadaluwarsa' => $kadaluwarsaformat[$i],
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
                                    'total_pengajuan' => $pengajuan_data['data']['total_pengajuan'],
                                    'catatan' => $pengajuan_data['data']['catatan'],
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
                                if ($response_pengajuan) {
                                    $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                                    if ($http_status_code_pengajuan === 200) {

                                        return redirect()->to(base_url('penerimaanmedis'));
                                    } else {
                                        // Error response dari pesanan_url
                                        return "Error Update Pengajuan: " . $response_pengajuan;
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
                                return "Error Update Pesanan: " . $response . $tambah_pesanan_JSON;
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
                        'total_pengajuan' => $pengajuan_data['data']['total_pengajuan'],
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
                                $put_byidpesanan_url = $this->api_url . '/pengadaan/pesanan/' . $pesanan_id;
                                $putDataPesanan = [
                                    'id_pengajuan' => $pesanan['id_pengajuan'],
                                    'id_barang_medis' => $pesanan['id_barang_medis'],
                                    'satuan' => $pesanan['satuan'],
                                    'harga_satuan_pengajuan' => $pesanan['harga_satuan_pengajuan'],
                                    'harga_satuan_pemesanan' => $pesanan['harga_satuan_pemesanan'],
                                    'jumlah_pesanan' => $pesanan['jumlah_pesanan'],
                                    'jumlah_diterima' => 0,
                                    'total_per_item' => $pesanan['subtotal_per_item'],
                                    'subtotal_per_item' => $pesanan['subtotal_per_item'],
                                    'diskon_persen' => $pesanan['diskon_persen'],
                                    'diskon_jumlah' => $pesanan['diskon_jumlah'],
                                    'kadaluwarsa' => '0001-01-01',
                                    'no_batch' => '',
                                ];
                                $update_pesanan_JSON = json_encode($putDataPesanan);
                                $ch_put_pesanan = curl_init($put_byidpesanan_url);
                                curl_setopt($ch_put_pesanan, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch_put_pesanan, CURLOPT_POSTFIELDS, $update_pesanan_JSON);
                                curl_setopt($ch_put_pesanan, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_put_pesanan, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($update_pesanan_JSON),
                                    'Authorization: Bearer ' . $token,
                                ]);
                                $response_put_pesanan = curl_exec($ch_put_pesanan);
                                $http_status_code_pesanan = curl_getinfo($ch_put_pesanan, CURLINFO_HTTP_CODE);
                            }
                            if ($http_status_code_pesanan !== 200) {
                                return $this->renderErrorView($http_status_code_pesanan);
                            }
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
