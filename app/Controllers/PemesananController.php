<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

require_once FCPATH . '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PemesananController extends BaseController
{
    public function dataPemesananMedis()
    {
        $title = 'Data Pemesanan Medis';

        $page = $this->request->getGet('page') ?? 1;
        $size = $this->request->getGet('size') ?? 10;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // URLs
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan?page=' . $page . '&size=' . $size;
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $pesanan_url = $this->api_url . '/pengadaan/pesanan';
            $pegawai_url = $this->api_url . '/pegawai';
            $supplier_url = $this->api_url . '/inventaris/supplier';
            $medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';

            // Initialize cURL for each endpoint
            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
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

            $ch_pegawai = curl_init($pegawai_url);
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute cURL requests
            $response_pemesanan = curl_exec($ch_pemesanan);
            $response_pengajuan = curl_exec($ch_pengajuan);
            $response_pesanan = curl_exec($ch_pesanan);
            $response_medis = curl_exec($ch_medis);
            $response_satuan = curl_exec($ch_satuan);
            $response_pegawai = curl_exec($ch_pegawai);
            $response_supplier = curl_exec($ch_supplier);

            // Check for successful responses
            if (
                $response_pemesanan && $response_pengajuan && $response_pegawai && $response_supplier &&
                $response_pesanan && $response_medis && $response_satuan
            ) {

                // Cek kode status HTTP untuk masing-masing tanggapan
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

                if (
                    $http_status_code_pemesanan === 200 && $http_status_code_pengajuan === 200 &&
                    $http_status_code_pegawai === 200 && $http_status_code_supplier === 200 &&
                    $http_status_code_pesanan === 200 && $http_status_code_medis === 200 &&
                    $http_status_code_satuan === 200
                ) {
                    // Dekode tanggapan JSON
                    $pemesanan_medis_data = json_decode($response_pemesanan, true);
                    $pengajuan_medis_data = json_decode($response_pengajuan, true);
                    $pegawai_data = json_decode($response_pegawai, true);
                    $supplier_data = json_decode($response_supplier, true);
                    $pesanan_data = json_decode($response_pesanan, true);
                    $medis_data = json_decode($response_medis, true);
                    $satuan_data = json_decode($response_satuan, true);

                    // Lanjutkan dengan menambahkan breadcrumb dan mengembalikan view dengan data
                    $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Pemesanan', 'pemesananmedis');

                    $breadcrumbs = $this->getBreadcrumbs();

                    return view('/admin/pengadaan/medis/pemesanan/data_pemesanan', [
                        'pemesanan_medis_data' => $pemesanan_medis_data['data']['pemesanan_barang_medis'],
                        'pengajuan_medis_data' => $pengajuan_medis_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'supplier_data' => $supplier_data['data'],
                        'pesanan_data' => $pesanan_data['data'],
                        'medis_data' => $medis_data['data'], // Tambahkan data medis ke dalam array
                        'satuan_data' => $satuan_data['data'], // Tambahkan data satuan ke dalam array
                        'meta_data' => $pemesanan_medis_data['data'],
                        'title' => $title,
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    return "Failed to fetch data.";
                }
            } else {
                return "Error fetching data.";
            }
        } else {
            return "User not logged in. Please log in first.";
        }
    }


    public function cetakPemesananBrgMedis($id)
    {
        // Fetch data based on $id
        $token = session()->get('jwt_token');
        if (session()->has('jwt_token')) {
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan/' . $id;
            $medis_url = $this->api_url . '/inventaris/medis';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $supplier_url = $this->api_url . '/inventaris/supplier';
            $pegawai_url = $this->api_url . '/pegawai';
            $persetujuan_url = $this->api_url . '/pengadaan/persetujuan';

            // Initialize cURL for each endpoint
            $ch_pemesanan = curl_init($pemesanan_url);
            $ch_medis = curl_init($medis_url);
            $ch_satuan = curl_init($satuan_url);
            $ch_supplier = curl_init($supplier_url);
            $ch_pegawai = curl_init($pegawai_url);
            $ch_persetujuan = curl_init($persetujuan_url);


            // Set cURL options for Pemesanan endpoint
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Set cURL options for Medis endpoint
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Set cURL options for Satuan endpoint
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Set cURL options for Supplier endpoint
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Set cURL options for Pegawai endpoint
            curl_setopt($ch_pegawai, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pegawai, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Set cURL options for Persetujuan endpoint
            curl_setopt($ch_persetujuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_persetujuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);

            // Execute cURL requests
            $response_pemesanan = curl_exec($ch_pemesanan);
            $response_medis = curl_exec($ch_medis);
            $response_satuan = curl_exec($ch_satuan);
            $response_supplier = curl_exec($ch_supplier);
            $response_pegawai = curl_exec($ch_pegawai);
            $response_persetujuan = curl_exec($ch_persetujuan);

            // Handle errors if any
            if ($response_pemesanan && $response_medis && $response_satuan && $response_supplier && $response_pegawai && $response_persetujuan) {
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_persetujuan = curl_getinfo($ch_persetujuan, CURLINFO_HTTP_CODE);

                // Check if all requests are successful (HTTP status 200)
                if ($http_status_code_pemesanan === 200 && $http_status_code_medis === 200 && $http_status_code_satuan === 200 && $http_status_code_supplier === 200 && $http_status_code_pegawai === 200 && $http_status_code_persetujuan === 200) {
                    $pemesanan_medis_data = json_decode($response_pemesanan, true);
                    $medis_data = json_decode($response_medis, true);
                    $satuan_data = json_decode($response_satuan, true);
                    $supplier_data = json_decode($response_supplier, true);
                    $pegawai_data = json_decode($response_pegawai, true);
                    $persetujuan_data = json_decode($response_persetujuan, true);
                    $id_pengajuan = $pemesanan_medis_data['data']['id_pengajuan'];
                    $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $id_pengajuan;
                    // Fetch data from Pesanan endpoint
                    $ch_pesanan = curl_init($pesanan_url);
                    curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                    ]);

                    $response_pesanan = curl_exec($ch_pesanan);

                    if ($response_pesanan) {
                        $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                        if ($http_status_code_pesanan === 200) {
                            $pesanan_data = json_decode($response_pesanan, true);

                            // Generate PDF with both Pemesanan and Pesanan data
                            $html = view('/admin/pengadaan/medis/pemesanan/cetak_pemesanan', [
                                'pemesanan_medis_data' => $pemesanan_medis_data['data'],
                                'pesanan_data' => $pesanan_data['data'],
                                'medis_data' => $medis_data['data'],
                                'satuan_data' => $satuan_data['data'],
                                'supplier_data' => $supplier_data['data'],
                                'pegawai_data' => $pegawai_data['data'],
                                'persetujuan_data' => $persetujuan_data['data'],

                                'title' => 'Data Pemesanan Medis'
                            ]);

                            $this->generatePDF($html);
                        } else {
                            return "Failed to fetch pesanan data.";
                        }
                    } else {
                        return "Error fetching pesanan data.";
                    }
                    // Generate PDF with all fetched data

                } else {
                    return "Failed to fetch one or more data.";
                }
            } else {
                return "Error fetching one or more data.";
            }

            // Close cURL sessions
            curl_close($ch_pemesanan);
            curl_close($ch_medis);
            curl_close($ch_satuan);
            curl_close($ch_supplier);
            curl_close($ch_pegawai);
            curl_close($ch_persetujuan);
        } else {
            return "JWT token not found.";
        }
    }


    private function generatePDF($html)
    {

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Surat Pemesanan.pdf", array("Attachment" => false));
    }


    public function tambahPemesananMedis()
    {
        $title = 'Tambah Pemesanan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $api_url = $this->api_url;
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan';
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $supplier_url = $this->api_url . '/inventaris/supplier';
            $pegawai_url = $this->api_url . '/pegawai';

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

            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_supplier = curl_exec($ch_supplier);

            if ($response_pegawai && $response_pengajuan && $response_barang_medis && $response_supplier) {
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);

                if ($http_status_code_pegawai === 200 && $http_status_code_pengajuan === 200 && $http_status_code_supplier === 200) {
                    $pegawai_data = json_decode($response_pegawai, true);
                    $pengajuan_data = json_decode($response_pengajuan, true);
                    $medis_data = json_decode($response_barang_medis, true);
                    $supplier_data = json_decode($response_supplier, true);
                } else {
                    return "Response pegawai data:" . $response_pegawai .
                        "<br><br>Response pengajuan data:" . $response_pengajuan .
                        "<br><br>Response barang medis data:" . $response_barang_medis .
                        "<br><br>Response Supplier:" . $response_supplier;
                }
            } else {
                return "Error fetching pegawai data.";
            }

            echo view('/admin/pengadaan/medis/pemesanan/tambah_pemesanan', [
                'pegawai_data' => $pegawai_data['data'],
                'pengajuan_data' => $pengajuan_data['data'],
                'medis_data' => $medis_data['data'],
                'supplier_data' => $supplier_data['data'],
                'api_url' => $api_url,
                'token' => $token,
                'title' => $title
            ]);
        } else {
            return "User not logged in. Please log in first.";
        }
    }
    public function tambahPemesananMedisbyId($idpengajuan)
    {
        $title = 'Tambah Pemesanan Medis';
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $api_url = $this->api_url;
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $idpengajuan;
            $pesanan_url = $this->api_url . '/pengadaan/pesanan/pengajuan/' . $idpengajuan;
            $persetujuan_url = $this->api_url . '/pengadaan/persetujuan/' . $idpengajuan;
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

            // Ambil data pengajuan
            $ch_pengajuan = curl_init($pengajuan_url);
            curl_setopt($ch_pengajuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pengajuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pengajuan = curl_exec($ch_pengajuan);
            $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
            curl_close($ch_pengajuan);

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

            // Ambil data pesanan
            $ch_pesanan = curl_init($pesanan_url);
            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pesanan = curl_exec($ch_pesanan);
            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
            curl_close($ch_pesanan);

            // Cek response dan status code
            if ($http_status_code_pegawai === 200 && $http_status_code_pengajuan === 200 && $http_status_code_barang_medis === 200 && $http_status_code_supplier === 200 && $http_status_code_satuan === 200 && $http_status_code_pesanan === 200) {
                $pegawai_data = json_decode($response_pegawai, true);
                $pengajuan_data = json_decode($response_pengajuan, true);
                if ($pengajuan_data['data']['status_pesanan'] !== '2') {
                    return redirect('pengajuanmedis')->with('warning', 'Pengajuan harus disetujui terlebih dahulu agar bisa membuat pemesanan');
                }
                $medis_data = json_decode($response_barang_medis, true);
                $supplier_data = json_decode($response_supplier, true);
                $satuan_data = json_decode($response_satuan, true); // Decode data satuan
                $pesanan_data = json_decode($response_pesanan, true); // Decode data pesanan
            } else {
                // Handle jika ada response tidak berhasil
                return "Response pegawai data:" . $response_pegawai .
                    "<br><br>Response pengajuan data:" . $response_pengajuan .
                    "<br><br>Response barang medis data:" . $response_barang_medis .
                    "<br><br>Response Supplier:" . $response_supplier .
                    "<br><br>Response Satuan:" . $response_satuan .
                    "<br><br>Response Pesanan:" . $response_pesanan; // Tambahkan response satuan dan pesanan di sini
            }
            $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Pemesanan', 'pemesananmedis');
            $this->addBreadcrumb('Tambah', 'tambahpemesananmedis');

            $breadcrumbs = $this->getBreadcrumbs();

            // Tampilkan view dengan data yang sudah diambil
            echo view('/admin/pengadaan/medis/pemesanan/pemesanan', [
                'pegawai_data' => $pegawai_data['data'],
                'pengajuan_data' => $pengajuan_data['data'],
                'medis_data' => $medis_data['data'],
                'supplier_data' => $supplier_data['data'],
                'satuan_data' => $satuan_data['data'], // Tambahkan satuan_data ke dalam array
                'pesanan_data' => $pesanan_data['data'], // Tambahkan pesanan_data ke dalam array
                'api_url' => $api_url,
                'token' => $token,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return "User not logged in. Please log in first.";
        }
    }


    public function submitTambahPemesananMedis()
    {
        if ($this->request->getPost()) {
            $tglpemesanan = $this->request->getPost('tglpemesanan');
            $nopemesanan = $this->request->getPost('nopemesanan');
            $idpengajuan = $this->request->getPost('idpengajuan');
            $pegawaipemesanan = $this->request->getPost('pegawaipemesanan');
            $supplier = intval($this->request->getPost('supplier'));
            $pajakpersenpemesanan = intval($this->request->getPost('pajakpersenpemesanan'));
            $pajakjumlahpemesanan = intval($this->request->getPost('pajakjumlahpemesanan'));
            $materaipemesanan = intval($this->request->getPost('materaipemesanan'));
            $totalpemesanan = intval($this->request->getPost('totalpemesanan'));

            $statuspesanan = $this->request->getPost('statuspesanan');

            $idpesanan = $this->request->getPost('idpesanan');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuanbrgmedis = $this->request->getPost('satuanbrgmedis');
            $jumlah_pesanan = $this->request->getPost('jumlah_pesanan');
            $jumlah_diterima = $this->request->getPost('jumlah_diterima');
            $harga_satuan_pengajuan = $this->request->getPost('harga_satuan_pengajuan');
            $harga_satuan_pemesanan = $this->request->getPost('harga_satuan_pemesanan');
            $totalperitem = $this->request->getPost('totalperitem');
            $subtotalperitem = $this->request->getPost('subtotalperitem');
            $diskonpersenperitem = $this->request->getPost('diskonpersenperitem');
            $diskonjumlahperitem = $this->request->getPost('diskonjumlahperitem');
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            $no_batch = $this->request->getPost('no_batch');

            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan';

            $postDataPemesanan = [
                'tanggal_pesan' => $tglpemesanan,
                'no_pemesanan' => $nopemesanan,
                'id_pengajuan' => $idpengajuan,
                'id_supplier' => $supplier,
                'id_pegawai' => $pegawaipemesanan,
                'pajak_persen' => $pajakpersenpemesanan,
                'pajak_jumlah' => $pajakjumlahpemesanan,
                'total_pemesanan' => $totalpemesanan,
                'materai' => $materaipemesanan,
            ];
            $tambah_pemesanan_JSON = json_encode($postDataPemesanan);


            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_pemesanan = curl_init($pemesanan_url);

                curl_setopt($ch_pemesanan, CURLOPT_POST, 1);
                curl_setopt($ch_pemesanan, CURLOPT_POSTFIELDS, ($tambah_pemesanan_JSON));
                curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($tambah_pemesanan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_pemesanan = curl_exec($ch_pemesanan);

                if ($response_pemesanan) {
                    $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_pemesanan === 201) {
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
                                for ($i = 0; $i < count($idbrgmedis); $i++) {
                                    $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                                    $postDataPesanan = [
                                        'id_pengajuan' => $idpengajuan,
                                        'id_barang_medis' => $idbrgmedis[$i],
                                        'satuan' => intval($satuanbrgmedis[$i]),
                                        'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                                        'harga_satuan_pemesanan' => intval($harga_satuan_pemesanan[$i]),
                                        'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                                        'jumlah_diterima' => intval($jumlah_diterima[$i]),
                                        'total_per_item' => intval($totalperitem[$i]),
                                        'subtotal_per_item' => intval($subtotalperitem[$i]),
                                        'diskon_persen' => intval($diskonpersenperitem[$i]),
                                        'diskon_jumlah' => intval($diskonjumlahperitem[$i]),
                                        'kadaluwarsa' => $kadaluwarsa[$i],
                                        'no_batch' => $no_batch[$i],
                                    ];
                                    $edit_pesanan_JSON = json_encode($postDataPesanan);
                                    $ch_pesanan = curl_init($pesanan_url);
                                    curl_setopt($ch_pesanan, CURLOPT_CUSTOMREQUEST, 'PUT');
                                    curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, ($edit_pesanan_JSON));
                                    curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                                        'Content-Type: application/json',
                                        'Content-Length: ' . strlen($edit_pesanan_JSON),
                                        'Authorization: Bearer ' . $token,
                                    ]);
                                    $response_pesanan = curl_exec($ch_pesanan);
                                }
                                if ($response_pesanan) {
                                    $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                                    if ($http_status_code_pesanan === 200) {
                                        return redirect()->to(base_url('pemesananmedis'));
                                    } else {
                                        return "Error Update Pesanan: " . $response_pesanan;
                                    }
                                } else {
                                    return "Error sending request to the obat API.";
                                }
                                curl_close($ch_pesanan);
                            } else {
                                return "Error Update Pengajuan: " . $response_pengajuan;
                            }
                            curl_close($ch_pemesanan);
                            curl_close($ch_pengajuan);
                        } else {
                            return "Error sending request to the obat API.";
                        }
                    } else {
                        return "Error Insert Pemesanan: " . $response_pemesanan;
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
    public function editPemesananMedis($pemesananId)
    {
        if (session()->has('jwt_token')) {
            // Ambil data medis berdasarkan ID barang medis
            $token = session()->get('jwt_token');
            $barang_medis_url = $this->api_url . '/inventaris/medis';
            $pegawai_url = $this->api_url . '/pegawai';
            $supplier_url = $this->api_url . '/inventaris/supplier';
            $satuan_url = $this->api_url . '/inventaris/satuan';
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan/' . $pemesananId;

            // Initialize cURL for each endpoint
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

            $ch_pemesanan = curl_init($pemesanan_url);
            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);
            $pemesanan_data = json_decode($response_pemesanan, true);
            $idpengajuan = $pemesanan_data['data']['id_pengajuan'];

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

            // Initialize cURL for supplier data
            $ch_supplier = curl_init($supplier_url);
            curl_setopt($ch_supplier, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_supplier, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_supplier = curl_exec($ch_supplier);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);

            if ($response_pemesanan && $response_pesanan && $response_pengajuan && $response_barang_medis && $response_pegawai && $response_supplier && $response_satuan) {
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                $http_status_code_pengajuan = curl_getinfo($ch_pengajuan, CURLINFO_HTTP_CODE);
                $http_status_code_barang_medis = curl_getinfo($ch_barang_medis, CURLINFO_HTTP_CODE);
                $http_status_code_pegawai = curl_getinfo($ch_pegawai, CURLINFO_HTTP_CODE);
                $http_status_code_supplier = curl_getinfo($ch_supplier, CURLINFO_HTTP_CODE);
                $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);

                $medis_data = json_decode($response_barang_medis, true);
                $pengajuan_data = json_decode($response_pengajuan, true);
                $pesanan_data = json_decode($response_pesanan, true);
                $pegawai_data = json_decode($response_pegawai, true);
                $supplier_data = json_decode($response_supplier, true);
                $satuan_data = json_decode($response_satuan, true);

                if ($http_status_code_pemesanan === 200 && $http_status_code_pesanan === 200 && $http_status_code_pengajuan === 200 && $http_status_code_supplier === 200 && $http_status_code_satuan === 200) {
                    $this->addBreadcrumb('Pengadaan', 'pengadaanmedis');
                    $this->addBreadcrumb('Barang Medis', 'medis');
                    $this->addBreadcrumb('Pemesanan', 'pemesananmedis');
                    $this->addBreadcrumb('Ubah', 'editpemesananmedis');

                    $breadcrumbs = $this->getBreadcrumbs();
                    return view('/admin/pengadaan/medis/pemesanan/edit_pemesanan', [
                        'pemesanan_data' => $pemesanan_data['data'],
                        'pesanan_data' => $pesanan_data['data'],
                        'pengajuan_data' => $pengajuan_data['data'],
                        'medis_data' => $medis_data['data'],
                        'pegawai_data' => $pegawai_data['data'],
                        'supplier_data' => $supplier_data['data'],
                        'satuan_data' => $satuan_data['data'], // Menyertakan data satuan
                        'pemesananId' => $pemesananId,
                        'title' => 'Edit pemesanan Medis',
                        'breadcrumbs' => $breadcrumbs
                    ]);
                } else {
                    // Error: Gagal mengambil data medis
                    return "Error fetching data. HTTP Status Code pemesanan: $http_status_code_pemesanan, HTTP Status Code Pesanan: $http_status_code_pesanan, HTTP Status Code Pengajuan: $http_status_code_pengajuan, HTTP Status Code Supplier: $http_status_code_supplier, HTTP Status Code Satuan: $http_status_code_satuan";
                }
            } else {
                // Error: Gagal mengambil respons dari API untuk data medis
                return "Error fetching data.";
            }

            // Close all cURL sessions
            curl_close($ch_pemesanan);
            curl_close($ch_pesanan);
            curl_close($ch_pengajuan);
            curl_close($ch_barang_medis);
            curl_close($ch_pegawai);
            curl_close($ch_supplier);
            curl_close($ch_satuan);
        } else {
            // User belum login
            return "User not logged in. Please log in first.";
        }
    }
    public function submitEditPemesananMedis($pemesananId)
    {
        if ($this->request->getPost()) {
            $tglpemesanan = $this->request->getPost('tglpemesanan');
            $nopemesanan = $this->request->getPost('nopemesanan');
            $idpengajuan = $this->request->getPost('idpengajuan');
            $pegawaipemesanan = $this->request->getPost('pegawaipemesanan');
            $supplier = intval($this->request->getPost('supplier'));
            $pajakpersenpemesanan = intval($this->request->getPost('pajakpersenpemesanan'));
            $pajakjumlahpemesanan = intval($this->request->getPost('pajakjumlahpemesanan'));
            $materaipemesanan = intval($this->request->getPost('materaipemesanan'));
            $totalpemesanan = intval($this->request->getPost('totalpemesanan'));

            $idpesanan = $this->request->getPost('idpesanan');
            $idbrgmedis = $this->request->getPost('idbrgmedis');
            $satuanbrgmedis = $this->request->getPost('satuanbrgmedis');
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

            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan/' . $pemesananId;

            $postDatapemesanan = [
                'tanggal_pesan' => $tglpemesanan,
                'no_pemesanan' => $nopemesanan,
                'id_pengajuan' => $idpengajuan,
                'id_supplier' => $supplier,
                'id_pegawai' => $pegawaipemesanan,
                'pajak_persen' => $pajakpersenpemesanan,
                'pajak_jumlah' => $pajakjumlahpemesanan,
                'materai' => $materaipemesanan,
                'total_pemesanan' => $totalpemesanan,
            ];
            $edit_pemesanan_JSON = json_encode($postDatapemesanan);

            if (session()->has('jwt_token')) {
                $token = session()->get('jwt_token');
                $ch_pemesanan = curl_init($pemesanan_url);

                curl_setopt($ch_pemesanan, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch_pemesanan, CURLOPT_POSTFIELDS, $edit_pemesanan_JSON);
                curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($edit_pemesanan_JSON),
                    'Authorization: Bearer ' . $token,
                ]);

                $response_pemesanan = curl_exec($ch_pemesanan);

                if ($response_pemesanan) {
                    $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                    if ($http_status_code_pemesanan === 200) {
                        for ($i = 0; $i < count($idbrgmedis); $i++) {
                            $pesanan_url = $this->api_url . '/pengadaan/pesanan/' . $idpesanan[$i];
                            $postDataPesanan = [
                                'id_pengajuan' => $idpengajuan,
                                'id_barang_medis' => $idbrgmedis[$i],
                                'satuan' => intval($satuanbrgmedis[$i]),
                                'harga_satuan_pengajuan' => intval($harga_satuan_pengajuan[$i]),
                                'harga_satuan_pemesanan' => intval($harga_satuan_pemesanan[$i]),
                                'diskon_persen' => intval($diskonpersenperitem[$i]),
                                'diskon_jumlah' => intval($diskonjumlahperitem[$i]),
                                'jumlah_pesanan' => intval($jumlah_pesanan[$i]),
                                'jumlah_diterima' => intval($jumlah_diterima[$i]),
                                'subtotal_per_item' => intval($subtotalperitem[$i]),
                                'total_per_item' => intval($totalperitem[$i]),
                                'kadaluwarsa' => $kadaluwarsa[$i],
                                'no_batch' => $no_batch[$i],
                            ];
                            $edit_pesanan_JSON = json_encode($postDataPesanan);
                            $ch_pesanan = curl_init($pesanan_url);
                            curl_setopt($ch_pesanan, CURLOPT_CUSTOMREQUEST, 'PUT');
                            curl_setopt($ch_pesanan, CURLOPT_POSTFIELDS, ($edit_pesanan_JSON));
                            curl_setopt($ch_pesanan, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_pesanan, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($edit_pesanan_JSON),
                                'Authorization: Bearer ' . $token,
                            ]);
                            $response_pesanan = curl_exec($ch_pesanan);
                        }
                        if ($response_pesanan) {
                            $http_status_code_pesanan = curl_getinfo($ch_pesanan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_pesanan === 200) {
                                return redirect()->to(base_url('pemesananmedis'));
                            } else {
                                return "Error Update Pesanan: " . $response_pesanan;
                            }
                        } else {
                            return "Error sending request to the obat API.";
                        }
                        curl_close($ch_pesanan);


                        curl_close($ch_pemesanan);
                    } else {
                        // Error response from the API
                        curl_close($ch_pemesanan); // Tutup session cURL untuk medis_url di sini
                        return "Error Update pemesanan: " . $response_pemesanan;
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
            return "Data is required.";
        }
    }
    public function hapusPemesananMedis($pemesananId)
    {
        // Check if the user is logged in
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pemesanan_url = $this->api_url . '/pengadaan/pemesanan/' . $pemesananId;

            $ch_pemesanan = curl_init($pemesanan_url);

            curl_setopt($ch_pemesanan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemesanan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemesanan = curl_exec($ch_pemesanan);
            $pemesanan_data = json_decode($response_pemesanan, true);
            $pengajuanId = $pemesanan_data['data']['id_pengajuan'];
            $pengajuan_url = $this->api_url . '/pengadaan/pengajuan/' . $pengajuanId;
            if ($response_pemesanan) {
                $http_status_code_pemesanan = curl_getinfo($ch_pemesanan, CURLINFO_HTTP_CODE);
                if ($http_status_code_pemesanan === 200) {
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
                        'status_pesanan' => '2',
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
                                    'harga_satuan_pemesanan' => 0,
                                    'jumlah_pesanan' => $pesanan['jumlah_pesanan'],
                                    'total_per_item' => $pesanan['subtotal_per_item'],
                                    'subtotal_per_item' => $pesanan['subtotal_per_item'],
                                    'diskon_persen' => 0,
                                    'diskon_jumlah' => 0,
                                    'kadaluwarsa' => $pesanan['kadaluwarsa'],
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
                            $ch_delete_pemesanan = curl_init($pemesanan_url);
                            curl_setopt($ch_delete_pemesanan, CURLOPT_CUSTOMREQUEST, "DELETE");
                            curl_setopt($ch_delete_pemesanan, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch_delete_pemesanan, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $token,
                            ]);
                            // Execute the cURL request to obat_url
                            $response_pemesanan = curl_exec($ch_delete_pemesanan);
                            $http_status_code_pemesanan = curl_getinfo($ch_delete_pemesanan, CURLINFO_HTTP_CODE);
                            if ($http_status_code_pemesanan === 204) {
                                return redirect()->to(base_url('pemesananmedis'));
                            } else {
                                // Error response from the API
                                return "Error deleting pemesanan: " . $response_pemesanan;
                            }
                        } else {
                            // Error response dari obat_url
                            return "Error Update Pengajuan: " . $response_pengajuan;
                        }
                        curl_close($ch_pengajuan);
                        curl_close($ch_pemesanan);
                    } else {
                        return "Error sending request to the obat API.";
                    }
                } else {
                    return "Error mendapatkan data pengajuan: " . $response_pemesanan;
                }
            } else {
                return "Error mendapatkan data pengajuan.";
            }
            //delete pemesanan

        } else {
            // User not logged in
            return "User not logged in. Please log in first.";
        }
    }
}
