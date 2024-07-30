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

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $penerimaan_url = $this->api_url . '/inventory/penerimaan';
            $detail_url = $this->api_url . '/inventory/detail';
            $medis_url = $this->api_url . '/inventory/barang';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $supplier_url = $this->api_url . '/ref/inventory/supplier';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $pegawai_url = $this->api_url . '/pegawai';

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

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pegawai = curl_exec($ch_pegawai);

            if ($response_penerimaan && $response_pegawai && $response_ruangan) {
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);

                $penerimaan_data = json_decode($response_penerimaan, true);
                $detail_data = json_decode($response_detail, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $medis_data = json_decode($response_medis, true);
                $satuan_data = json_decode($response_satuan, true);
                $supplier_data = json_decode($response_supplier, true);
                $ruangan_data = json_decode($response_ruangan, true);

                if ($http_status_code_detail === 200 && $http_status_code_penerimaan === 200 && $http_status_code_pegawai === 200 && $http_status_code_satuan === 201 && $http_status_code_ruangan === 201) {
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

                    return "Error fetching data. HTTP Status Code Penerimaan: $http_status_code_penerimaan" . $response_penerimaan . $response_detail;
                }
            } else {

                return "Error fetching data.";
            }

            curl_close($ch_penerimaan);
            curl_close($ch_ruangan);
            curl_close($ch_pegawai);
        } else {
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
            $ubahmaster = $this->request->getPost('ubahmaster');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $hargabeli = $this->request->getPost('harga_satuan_pemesanan');
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
            $pajakpersen = $this->request->getPost('pajakpersenpemesanan');
            $pajakjumlah = $this->request->getPost('pajakjumlahpemesanan');
            $tagihan = $this->request->getPost('totalpemesanan');
            $materai = $this->request->getPost('materaipemesanan');
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
                    'pajak_persen' => intval($pajakpersen),
                    'pajak_jumlah' => intval($pajakjumlah),
                    'tagihan' => intval($tagihan),
                    'materai' => intval($materai)
                ];

                $tambah_penerimaan_JSON = json_encode($postDataPenerimaan);
                $ch_penerimaan = curl_init($penerimaan_url);
                curl_setopt($ch_penerimaan, CURLOPT_POST, 1);
                curl_setopt($ch_penerimaan, CURLOPT_POSTFIELDS, $tambah_penerimaan_JSON);
                curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_penerimaan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_penerimaan = curl_exec($ch_penerimaan);
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                curl_close($ch_penerimaan);

                if ($response_penerimaan && $http_status_code_penerimaan === 201) {
                    $penerimaan_data = json_decode($response_penerimaan, true);
                    $idpenerimaan = $penerimaan_data['data']['id'];

                    for ($i = 0; $i < count($idbrgmedis); $i++) {
                        $detail_url = $this->api_url . '/inventory/detail';
                        $kadaluwarsaformat = $kadaluwarsa[$i] === "" ? "0001-01-01" : $kadaluwarsa[$i];
                        $ubah_master_value = isset($ubahmaster[$i]) && $ubahmaster[$i] === "1" ? "1" : "0";

                        $postDatadetail = [
                            'id_penerimaan' => $idpenerimaan,
                            'id_barang_medis' => $idbrgmedis[$i],
                            'id_satuan' => intval($satuan[$i]),
                            'ubah_master' => '0',
                            'jumlah' => intval($jumlah_pesanan[$i]),
                            'h_pesan' => intval($hargabeli[$i]),
                            'subtotal_per_item' => intval($subtotal_per_item[$i]),
                            'diskon_persen' => intval($diskon_persen[$i]),
                            'diskon_jumlah' => intval($diskon_jumlah[$i]),
                            'total_per_item' => intval($total_per_item[$i]),
                            'jumlah_diterima' => intval($jumlah_pesanan[$i]),
                            'kadaluwarsa' => $kadaluwarsaformat,
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

                        $response_detail = curl_exec($ch_detail);
                        $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
                        curl_close($ch_detail);

                        if (!$response_detail || $http_status_code_detail !== 201) {
                            return $this->renderErrorView($http_status_code_detail);
                        }

                        if ($ubah_master_value === "1") {
                            $medis_url = $this->api_url . '/inventory/barang/' . $idbrgmedis[$i];
                            $ch_medis = curl_init($medis_url);
                            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $token,
                            ]);
                            $response_medis = curl_exec($ch_medis);
                            $medis_data = json_decode($response_medis, true);
                            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                            curl_close($ch_medis);

                            if ($http_status_code_medis === 200) {
                                $h_dasar = round((intval($total_per_item[$i]) / intval($jumlah_pesanan[$i])) * (1 - (intval($diskon_persen[$i]) / 100)));
                                $h_belikoma = round(($h_dasar * (1 + (intval($pajakpersen) / 100))));
                                $h_beli = (int) round($h_belikoma * 1.15);
                                $postDataMedis = [
                                    'kode_barang' => $medis_data['data']['kode_barang'],
                                    'nama' => $medis_data['data']['nama'],
                                    'isi' => $medis_data['data']['isi'],
                                    'kapasitas' => $medis_data['data']['kapasitas'],
                                    'kandungan' => $medis_data['data']['kandungan'],
                                    'id_industri' => $medis_data['data']['id_industri'],
                                    'id_satbesar' => $medis_data['data']['id_satbesar'],
                                    'id_satuan' => $medis_data['data']['id_satuan'],
                                    'id_jenis' => $medis_data['data']['id_jenis'],
                                    'id_kategori' => $medis_data['data']['id_kategori'],
                                    'id_golongan' => $medis_data['data']['id_golongan'],
                                    'h_dasar' => $h_dasar,
                                    'h_beli' => $h_belikoma,
                                    'h_ralan' => $h_beli,
                                    'h_kelas1' => $h_beli,
                                    'h_kelas2' => $h_beli,
                                    'h_kelas3' => $h_beli,
                                    'h_utama' => $h_beli,
                                    'h_vip' => $h_beli,
                                    'h_vvip' => $h_beli,
                                    'h_beliluar' => $h_beli,
                                    'h_jualbebas' => $h_beli,
                                    'h_karyawan' => $h_beli,
                                    'stok_minimum' => $medis_data['data']['stok_minimum'],
                                    'kadaluwarsa' => $kadaluwarsaformat,
                                ];

                                $tambah_medis_JSON = json_encode($postDataMedis);
                                $ch_medis_put = curl_init($medis_url);
                                curl_setopt($ch_medis_put, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch_medis_put, CURLOPT_POSTFIELDS, $tambah_medis_JSON);
                                curl_setopt($ch_medis_put, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_medis_put, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($tambah_medis_JSON),
                                    'Authorization: Bearer ' . $token,
                                ]);

                                $response_medis_put = curl_exec($ch_medis_put);
                                $http_status_code_medis_put = curl_getinfo($ch_medis_put, CURLINFO_HTTP_CODE);
                                curl_close($ch_medis_put);

                                if ($http_status_code_medis_put !== 200) {
                                    return $this->renderErrorView($http_status_code_medis_put);
                                }
                            } else {
                                return $this->renderErrorView($http_status_code_medis);
                            }
                        }

                        $gudang_url = $this->api_url . '/inventory/gudang/barang/' . $idbrgmedis[$i];
                        $ch_gudang = curl_init($gudang_url);
                        curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response_gudang = curl_exec($ch_gudang);
                        $gudang_data = json_decode($response_gudang, true);
                        $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
                        curl_close($ch_gudang);

                        if ($http_status_code_gudang !== 200) {
                            return $this->renderErrorView($http_status_code_gudang);
                        }

                        foreach ($gudang_data['data'] as $gudang) {
                            if ($gudang['id_ruangan'] === intval($idruangan)) {
                                $gudang_put_url = $this->api_url . '/inventory/gudang/' . $gudang['id'];
                                $postGudangMedis = [
                                    'id_barang_medis' => $idbrgmedis[$i],
                                    'id_ruangan' => intval($idruangan),
                                    'stok' => $gudang['stok'] + intval($jumlah_pesanan[$i]),
                                    'no_batch' => '',
                                    'no_faktur' => '',
                                ];

                                $tambah_gudang_JSON = json_encode($postGudangMedis);
                                $ch_gudang_put = curl_init($gudang_put_url);
                                curl_setopt($ch_gudang_put, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch_gudang_put, CURLOPT_POSTFIELDS, $tambah_gudang_JSON);
                                curl_setopt($ch_gudang_put, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_gudang_put, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($tambah_gudang_JSON),
                                    'Authorization: Bearer ' . $token,
                                ]);

                                $response_gudang_put = curl_exec($ch_gudang_put);
                                $http_status_code_gudang_put = curl_getinfo($ch_gudang_put, CURLINFO_HTTP_CODE);
                                curl_close($ch_gudang_put);

                                if ($http_status_code_gudang_put !== 200) {
                                    return $this->renderErrorView($http_status_code_gudang_put);
                                }
                            }
                        }
                    }
                    return redirect()->to(base_url('penerimaanmedis'));
                } else {
                    return $this->renderErrorView($http_status_code_penerimaan);
                }
            } else {
                return $this->renderErrorView(401);
            }
        } else {
            return "Data is required.";
        }
    }

    public function editPenerimaanMedis($penerimaanId)
    {
        $title = 'Edit Penerimaan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pegawai_url = $this->api_url . '/pegawai';
            $penerimaan_url = $this->api_url . '/inventory/penerimaan/' . $penerimaanId;
            $detail_url = $this->api_url . '/inventory/detail';
            $barang_medis_url = $this->api_url . '/inventory/barang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $supplier_url = $this->api_url . '/ref/inventory/supplier';

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

            $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
            $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
            $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
            $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
            $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);

            if ($http_status_code_penerimaan !== 200) {
                return $this->renderErrorView($http_status_code_penerimaan);
            }
            if ($http_status_code_detail !== 200) {
                return $this->renderErrorView($http_status_code_detail);
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
            if ($http_status_code_supplier !== 201) {
                return $this->renderErrorView($http_status_code_supplier);
            }
            $penerimaan_data = json_decode($response_penerimaan, true);
            $detail_data = json_decode($response_detail, true);
            $pegawai_data = json_decode($response_pegawai, true);
            $medis_data = json_decode($response_barang_medis, true);
            $satuan_data = json_decode($response_satuan, true);
            $supplier_data = json_decode($response_supplier, true);
            $ruangan_data = json_decode($response_ruangan, true);

            echo view('/admin/pengadaan/medis/penerimaan/edit_penerimaan', [
                'penerimaan_data' => $penerimaan_data['data'],
                'detail_data' => $detail_data['data'],
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

    public function submitEditPenerimaanMedis($penerimaanId)
    {
        if ($this->request->getPost()) {
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuan = $this->request->getPost('satuanbeli');
            $ubahmaster = $this->request->getPost('ubahmaster') ?? '0';
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $jumlah_pesanan_tetap = $this->request->getPost('jumlah_pesanan_tetap');
            $hargabeli = $this->request->getPost('harga_satuan_pemesanan');
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
            $pajakpersen = $this->request->getPost('pajakpersenpemesanan');
            $pajakjumlah = $this->request->getPost('pajakjumlahpemesanan');
            $tagihan = $this->request->getPost('totalpemesanan');
            $materai = $this->request->getPost('materaipemesanan');
            $pegawaipenerimaan = $this->request->getPost('pegawaipenerimaan');

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $penerimaan_url = $this->api_url . '/inventory/penerimaan/' . $penerimaanId;

                $postDataPenerimaan = [
                    'no_pemesanan' => $nopemesanan,
                    'no_faktur' => $nofaktur,
                    'id_supplier' => intval($supplier),
                    'tanggal_datang' => $tglpenerimaan,
                    'tanggal_faktur' => $tglfaktur,
                    'tanggal_jthtempo' => $tgljatuhtempo,
                    'id_pegawai' => $pegawaipenerimaan,
                    'id_ruangan' => intval($idruangan),
                    'pajak_persen' => intval($pajakpersen),
                    'pajak_jumlah' => intval($pajakjumlah),
                    'tagihan' => intval($tagihan),
                    'materai' => intval($materai)
                ];

                $tambah_penerimaan_JSON = json_encode($postDataPenerimaan);
                $ch_penerimaan = curl_init($penerimaan_url);
                curl_setopt($ch_penerimaan, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_penerimaan, CURLOPT_POSTFIELDS, $tambah_penerimaan_JSON);
                curl_setopt($ch_penerimaan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_penerimaan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_penerimaan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_penerimaan = curl_exec($ch_penerimaan);
                $http_status_code_penerimaan = curl_getinfo($ch_penerimaan, CURLINFO_HTTP_CODE);
                curl_close($ch_penerimaan);

                if ($response_penerimaan && $http_status_code_penerimaan === 200) {
                    for ($i = 0; $i < count($idbrgmedis); $i++) {
                        $detail_url = $this->api_url . '/inventory/detail/' . $penerimaanId . "/" . $idbrgmedis[$i];
                        $kadaluwarsaformat = $kadaluwarsa[$i] === "" ? "0001-01-01" : $kadaluwarsa[$i];
                        $ubah_master_value = isset($ubahmaster[$i]) && $ubahmaster[$i] === "1" ? "1" : "0";
                        $postDatadetail = [
                            'id_penerimaan' => $penerimaanId,
                            'id_barang_medis' => $idbrgmedis[$i],
                            'id_satuan' => intval($satuan[$i]),
                            'ubah_master' => '0',
                            'jumlah' => intval($jumlah_pesanan[$i]),
                            'h_pesan' => intval($hargabeli[$i]),
                            'subtotal_per_item' => intval($subtotal_per_item[$i]),
                            'diskon_persen' => intval($diskon_persen[$i]),
                            'diskon_jumlah' => intval($diskon_jumlah[$i]),
                            'total_per_item' => intval($total_per_item[$i]),
                            'jumlah_diterima' => intval($jumlah_pesanan[$i]),
                            'kadaluwarsa' => $kadaluwarsaformat,
                            'no_batch' => $no_batch[$i]
                        ];

                        $tambah_detail_JSON = json_encode($postDatadetail);
                        $ch_detail = curl_init($detail_url);
                        curl_setopt($ch_detail, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($ch_detail, CURLOPT_POSTFIELDS, $tambah_detail_JSON);
                        curl_setopt($ch_detail, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_detail, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($tambah_detail_JSON),
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response_detail = curl_exec($ch_detail);
                        $http_status_code_detail = curl_getinfo($ch_detail, CURLINFO_HTTP_CODE);
                        curl_close($ch_detail);

                        if (!$response_detail || $http_status_code_detail !== 200) {
                            return $this->renderErrorView($http_status_code_detail);
                        }

                        if ($ubah_master_value === "1") {
                            $medis_url = $this->api_url . '/inventory/barang/' . $idbrgmedis[$i];
                            $ch_medis = curl_init($medis_url);
                            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $token,
                            ]);
                            $response_medis = curl_exec($ch_medis);
                            $medis_data = json_decode($response_medis, true);
                            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                            curl_close($ch_medis);

                            if ($http_status_code_medis === 200) {
                                $h_dasar = round((intval($total_per_item[$i]) / intval($jumlah_pesanan[$i])) * (1 - (intval($diskon_persen[$i]) / 100)));
                                $h_belikoma = round(($h_dasar * (1 + (intval($pajakpersen) / 100))));
                                $h_beli = (int) round($h_belikoma * 1.15);
                                $postDataMedis = [
                                    'kode_barang' => $medis_data['data']['kode_barang'],
                                    'nama' => $medis_data['data']['nama'],
                                    'isi' => $medis_data['data']['isi'],
                                    'kapasitas' => $medis_data['data']['kapasitas'],
                                    'kandungan' => $medis_data['data']['kandungan'],
                                    'id_industri' => $medis_data['data']['id_industri'],
                                    'id_satbesar' => $medis_data['data']['id_satbesar'],
                                    'id_satuan' => $medis_data['data']['id_satuan'],
                                    'id_jenis' => $medis_data['data']['id_jenis'],
                                    'id_kategori' => $medis_data['data']['id_kategori'],
                                    'id_golongan' => $medis_data['data']['id_golongan'],
                                    'h_dasar' => $h_dasar,
                                    'h_beli' => $h_belikoma,
                                    'h_ralan' => $h_beli,
                                    'h_kelas1' => $h_beli,
                                    'h_kelas2' => $h_beli,
                                    'h_kelas3' => $h_beli,
                                    'h_utama' => $h_beli,
                                    'h_vip' => $h_beli,
                                    'h_vvip' => $h_beli,
                                    'h_beliluar' => $h_beli,
                                    'h_jualbebas' => $h_beli,
                                    'h_karyawan' => $h_beli,
                                    'stok_minimum' => $medis_data['data']['stok_minimum'],
                                    'kadaluwarsa' => $kadaluwarsaformat,
                                ];

                                $tambah_medis_JSON = json_encode($postDataMedis);
                                $ch_medis_put = curl_init($medis_url);
                                curl_setopt($ch_medis_put, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch_medis_put, CURLOPT_POSTFIELDS, $tambah_medis_JSON);
                                curl_setopt($ch_medis_put, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_medis_put, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($tambah_medis_JSON),
                                    'Authorization: Bearer ' . $token,
                                ]);
                                $response_medis_put = curl_exec($ch_medis_put);
                                $http_status_code_medis_put = curl_getinfo($ch_medis_put, CURLINFO_HTTP_CODE);
                                curl_close($ch_medis_put);

                                if ($http_status_code_medis_put !== 200) {
                                    return $this->renderErrorView($http_status_code_medis_put);
                                }
                            } else {
                                return $this->renderErrorView($http_status_code_medis);
                            }
                        }

                        $gudang_url = $this->api_url . '/inventory/gudang/barang/' . $idbrgmedis[$i];
                        $ch_gudang = curl_init($gudang_url);
                        curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $token,
                        ]);

                        $response_gudang = curl_exec($ch_gudang);
                        $gudang_data = json_decode($response_gudang, true);
                        $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
                        curl_close($ch_gudang);

                        if ($http_status_code_gudang !== 200) {
                            return $this->renderErrorView($http_status_code_gudang);
                        }

                        foreach ($gudang_data['data'] as $gudang) {
                            if ($gudang['id_ruangan'] === intval($idruangan)) {
                                $gudang_put_url = $this->api_url . '/inventory/gudang/' . $gudang['id'];
                                $postGudangMedis = [
                                    'id_barang_medis' => $idbrgmedis[$i],
                                    'id_ruangan' => intval($idruangan),
                                    'stok' => $gudang['stok'] - intval($jumlah_pesanan_tetap[$i]) + intval($jumlah_pesanan[$i]),
                                    'no_batch' => '',
                                    'no_faktur' => '',
                                ];

                                $tambah_gudang_JSON = json_encode($postGudangMedis);
                                $ch_gudang_put = curl_init($gudang_put_url);
                                curl_setopt($ch_gudang_put, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch_gudang_put, CURLOPT_POSTFIELDS, $tambah_gudang_JSON);
                                curl_setopt($ch_gudang_put, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch_gudang_put, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($tambah_gudang_JSON),
                                    'Authorization: Bearer ' . $token,
                                ]);

                                $response_gudang_put = curl_exec($ch_gudang_put);
                                $http_status_code_gudang_put = curl_getinfo($ch_gudang_put, CURLINFO_HTTP_CODE);
                                curl_close($ch_gudang_put);

                                if ($http_status_code_gudang_put !== 200) {
                                    return $this->renderErrorView($http_status_code_gudang_put);
                                }
                            }
                        }
                    }
                    return redirect()->to(base_url('penerimaanmedis'));
                } else {
                    return $this->renderErrorView($http_status_code_penerimaan);
                }
            } else {
                return $this->renderErrorView(401);
            }
        } else {
            return "Data is required.";
        }
    }

    public function hapusPenerimaanMedis($penerimaanId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $penerimaanUrl = $this->api_url . '/inventory/penerimaan/' . $penerimaanId;
            $detailUrl = $this->api_url . '/inventory/detail';

            $chPenerimaan = curl_init($penerimaanUrl);
            curl_setopt($chPenerimaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chPenerimaan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $responsePenerimaan = curl_exec($chPenerimaan);
            $httpStatusCodePenerimaan = curl_getinfo($chPenerimaan, CURLINFO_HTTP_CODE);
            curl_close($chPenerimaan);

            if ($httpStatusCodePenerimaan !== 200) {
                return "Error fetching penerimaan data: " . $responsePenerimaan;
            }
            $penerimaanData = json_decode($responsePenerimaan, true);


            $chDetail = curl_init($detailUrl);
            curl_setopt($chDetail, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chDetail, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $responseDetail = curl_exec($chDetail);
            $httpStatusCodeDetail = curl_getinfo($chDetail, CURLINFO_HTTP_CODE);
            curl_close($chDetail);

            if ($httpStatusCodeDetail !== 200) {
                return "Error fetching detail data: " . $responseDetail;
            }
            $detailData = json_decode($responseDetail, true);
            $detail = $detailData['data'];
            foreach ($detail as $dtl) {
                if ($dtl['id_penerimaan'] === $penerimaanId) {
                    $gudangUrl = $this->api_url . '/inventory/gudang/barang/' . $dtl['id_barang_medis'];

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
                        if ($gudang['id_ruangan'] === $penerimaanData['data']['id_ruangan']) {
                            $gudangUrl = $this->api_url . '/inventory/gudang/' . $gudang['id'];
                            $postGudangMedis = [
                                'id_barang_medis' => $dtl['id_barang_medis'],
                                'id_ruangan' => $penerimaanData['data']['id_ruangan'],
                                'stok' => $gudang['stok'] - $dtl['jumlah'],
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
                    $deleteDetailUrl = $this->api_url . '/inventory/detail/' . $dtl['id_penerimaan'] . "/" . $dtl['id_barang_medis'];
                    $chDetailDelete = curl_init($deleteDetailUrl);
                    curl_setopt($chDetailDelete, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($chDetailDelete, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chDetailDelete, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $responseDeleteDetail = curl_exec($chDetailDelete);
                    $httpStatusCodeDeleteDetail = curl_getinfo($chDetailDelete, CURLINFO_HTTP_CODE);
                    curl_close($chDetailDelete);

                    if ($httpStatusCodeDeleteDetail !== 204) {
                        return "Error deleting detail: " . $responseDeleteDetail;
                    }
                }
            }
            $chPenerimaanDelete = curl_init($penerimaanUrl);
            curl_setopt($chPenerimaanDelete, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($chPenerimaanDelete, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chPenerimaanDelete, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $responsePenerimaanDelete = curl_exec($chPenerimaanDelete);
            $httpStatusCodePenerimaanDelete = curl_getinfo($chPenerimaanDelete, CURLINFO_HTTP_CODE);
            curl_close($chPenerimaanDelete);

            if ($httpStatusCodePenerimaanDelete === 204) {
                return redirect()->to(base_url('penerimaanmedis'));
            } else {
                return "Error deleting penerimaan_keluar: " . $responsePenerimaanDelete;
            }
        }
        return $this->renderErrorView(401);
    }
}
