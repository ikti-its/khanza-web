<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MedisController extends BaseController
{
    public function dataMedis()
    {
        $title = 'Data Medis';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $medis_url = $this->api_url . '/inventory/barang';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $industri_url = $this->api_url . '/ref/inventory/industri';
            $jenis_url = $this->api_url . '/ref/inventory/jenis';
            $kategori_url = $this->api_url . '/ref/inventory/kategori';
            $golongan_url = $this->api_url . '/ref/inventory/golongan';

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            curl_close($ch_medis);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            curl_close($ch_satuan);

            $ch_industri = curl_init($industri_url);
            curl_setopt($ch_industri, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_industri, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_industri = curl_exec($ch_industri);
            $http_status_code_industri = curl_getinfo($ch_industri, CURLINFO_HTTP_CODE);
            curl_close($ch_industri);

            $ch_jenis = curl_init($jenis_url);
            curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_jenis = curl_exec($ch_jenis);
            $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);
            curl_close($ch_jenis);

            $ch_kategori = curl_init($kategori_url);
            curl_setopt($ch_kategori, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kategori, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_kategori = curl_exec($ch_kategori);
            $http_status_code_kategori = curl_getinfo($ch_kategori, CURLINFO_HTTP_CODE);
            curl_close($ch_kategori);

            $ch_golongan = curl_init($golongan_url);
            curl_setopt($ch_golongan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_golongan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_golongan = curl_exec($ch_golongan);
            $http_status_code_golongan = curl_getinfo($ch_golongan, CURLINFO_HTTP_CODE);
            curl_close($ch_golongan);

            if ($http_status_code_medis !== 200) {
                return $this->renderErrorView($http_status_code_medis);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_industri !== 201) {
                return $this->renderErrorView($http_status_code_industri);
            }
            if ($http_status_code_jenis !== 201) {
                return $this->renderErrorView($http_status_code_jenis);
            }
            if ($http_status_code_kategori !== 201) {
                return $this->renderErrorView($http_status_code_kategori);
            }
            if ($http_status_code_golongan !== 201) {
                return $this->renderErrorView($http_status_code_golongan);
            }

            $medis_data = json_decode($response_medis, true);
            $satuan_data = json_decode($response_satuan, true);
            $industri_data = json_decode($response_industri, true);
            $jenis_data = json_decode($response_jenis, true);
            $kategori_data = json_decode($response_kategori, true);
            $golongan_data = json_decode($response_golongan, true);

            $this->addBreadcrumb('Inventaris', 'inventarismedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Data', 'data');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/inventaris/medis/data_medis', [
                'medis_data' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'industri_data' => $industri_data['data'],
                'jenis_data' => $jenis_data['data'],
                'kategori_data' => $kategori_data['data'],
                'golongan_data' => $golongan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahMedis()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah medis';
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $industri_url = $this->api_url . '/ref/inventory/industri';
            $jenis_url = $this->api_url . '/ref/inventory/jenis';
            $kategori_url = $this->api_url . '/ref/inventory/kategori';
            $golongan_url = $this->api_url . '/ref/inventory/golongan';

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            curl_close($ch_satuan);

            $ch_industri = curl_init($industri_url);
            curl_setopt($ch_industri, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_industri, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_industri = curl_exec($ch_industri);
            $http_status_code_industri = curl_getinfo($ch_industri, CURLINFO_HTTP_CODE);
            curl_close($ch_industri);

            $ch_jenis = curl_init($jenis_url);
            curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_jenis = curl_exec($ch_jenis);
            $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);
            curl_close($ch_jenis);

            $ch_kategori = curl_init($kategori_url);
            curl_setopt($ch_kategori, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kategori, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_kategori = curl_exec($ch_kategori);
            $http_status_code_kategori = curl_getinfo($ch_kategori, CURLINFO_HTTP_CODE);
            curl_close($ch_kategori);

            $ch_golongan = curl_init($golongan_url);
            curl_setopt($ch_golongan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_golongan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_golongan = curl_exec($ch_golongan);
            $http_status_code_golongan = curl_getinfo($ch_golongan, CURLINFO_HTTP_CODE);
            curl_close($ch_golongan);

            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_industri !== 201) {
                return $this->renderErrorView($http_status_code_industri);
            }
            if ($http_status_code_jenis !== 201) {
                return $this->renderErrorView($http_status_code_jenis);
            }
            if ($http_status_code_kategori !== 201) {
                return $this->renderErrorView($http_status_code_kategori);
            }
            if ($http_status_code_golongan !== 201) {
                return $this->renderErrorView($http_status_code_golongan);
            }

            $satuan_data = json_decode($response_satuan, true);
            $industri_data = json_decode($response_industri, true);
            $jenis_data = json_decode($response_jenis, true);
            $kategori_data = json_decode($response_kategori, true);
            $golongan_data = json_decode($response_golongan, true);

            $this->addBreadcrumb('Inventaris', 'inventarismedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/inventaris/medis/tambah_medis', [
                'satuan_data' => $satuan_data['data'],
                'industri_data' => $industri_data['data'],
                'jenis_data' => $jenis_data['data'],
                'kategori_data' => $kategori_data['data'],
                'golongan_data' => $golongan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahMedis()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $kode = $this->request->getPost('kode');
            $nama = $this->request->getPost('nama');
            $isi = intval($this->request->getPost('isi'));
            $kapasitas = intval($this->request->getPost('kapasitas'));
            $kandungan = $this->request->getPost('kandungan');
            $indusfarmasi = intval($this->request->getPost('indusfarmasi'));
            $satuan = intval($this->request->getPost('satuan'));
            $satkecil = intval($this->request->getPost('satkecil'));
            $jenis = intval($this->request->getPost('jenis'));
            $kategori = intval($this->request->getPost('kategori'));
            $golongan = intval($this->request->getPost('golongan'));
            $hargadasar = intval($this->request->getPost('hargadasar'));
            $hargabeli = intval($this->request->getPost('hargabeli'));
            $hargaralan = intval($this->request->getPost('hargaralan'));
            $hargakelas1 = intval($this->request->getPost('hargakelas1'));
            $hargakelas2 = intval($this->request->getPost('hargakelas2'));
            $hargakelas3 = intval($this->request->getPost('hargakelas3'));
            $hargautama = intval($this->request->getPost('hargautama'));
            $hargavip = intval($this->request->getPost('hargavip'));
            $hargavvip = intval($this->request->getPost('hargavvip'));
            $hargaapotekluar = intval($this->request->getPost('hargaapotekluar'));
            $hargaobatbebas = intval($this->request->getPost('hargaobatbebas'));
            $hargaobatkaryawan = intval($this->request->getPost('hargakaryawan'));
            $stokminimum = intval($this->request->getPost('stokminimum'));
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            if ($kadaluwarsa === "") {
                $kadaluwarsaformat = '0001-01-01';
            } else {
                $kadaluwarsaformat = $kadaluwarsa;
            }
            $medis_url = $this->api_url . '/inventory/barang';
            $ruangan_url = $this->api_url . '/ref/inventory/ruangan';
            $gudang_url = $this->api_url . '/inventory/gudang';

            $postDataMedis = [
                'kode_barang' => $kode,
                'nama' => $nama,
                'isi' => $isi,
                'kapasitas' => $kapasitas,
                'kandungan' => $kandungan,
                'id_industri' => $indusfarmasi,
                'id_satbesar' => $satuan,
                'id_satuan' => $satkecil,
                'id_jenis' => $jenis,
                'id_kategori' => $kategori,
                'id_golongan' => $golongan,
                'h_dasar' => $hargadasar,
                'h_beli' => $hargabeli,
                'h_ralan' => $hargaralan,
                'h_kelas1' => $hargakelas1,
                'h_kelas2' => $hargakelas2,
                'h_kelas3' => $hargakelas3,
                'h_utama' => $hargautama,
                'h_vip' => $hargavip,
                'h_vvip' => $hargavvip,
                'h_beliluar' => $hargaapotekluar,
                'h_jualbebas' => $hargaobatbebas,
                'h_karyawan' => $hargaobatkaryawan,
                'stok_minimum' => $stokminimum,
                'kadaluwarsa' => $kadaluwarsaformat,
            ];
            $tambah_medis_JSON = json_encode($postDataMedis);

            $ch_ruangan = curl_init($ruangan_url);
            curl_setopt($ch_ruangan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ruangan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_ruangan = curl_exec($ch_ruangan);
            $ruangan_data = json_decode($response_ruangan, true);
            $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
            if ($http_status_code_ruangan !== 201) {
                $this->renderErrorView($http_status_code_ruangan);
            }
            curl_close($ch_ruangan);

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
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            if ($http_status_code_medis === 201) {
                $medis_data = json_decode($response_medis, true);
                $idbrgmedis = $medis_data['data']['id'];

                foreach ($ruangan_data['data'] as $ruangan) {
                    $postGudangMedis = [
                        'id_barang_medis' => $idbrgmedis,
                        'id_ruangan' => intval($ruangan['id']),
                        'stok' => 0,
                        'no_batch' => '',
                        'no_faktur' => '',
                    ];
                    $tambah_gudang_JSON = json_encode($postGudangMedis);
                    $ch_gudang = curl_init($gudang_url);
                    curl_setopt($ch_gudang, CURLOPT_POST, 1);
                    curl_setopt($ch_gudang, CURLOPT_POSTFIELDS, ($tambah_gudang_JSON));
                    curl_setopt($ch_gudang, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_gudang, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($tambah_gudang_JSON),
                        'Authorization: Bearer ' . $token,
                    ]);
                    $response_gudang = curl_exec($ch_gudang);
                    $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
                    curl_close($ch_gudang);
                }
                if ($http_status_code_gudang === 201) {
                    return redirect()->to(base_url('datamedis'));
                } else {
                    return $response_medis;
                }
            } else {
                return $response_medis;
            }

            curl_close($ch_medis);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function editMedis($medisId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah medis';
            $medis_url = $this->api_url . '/inventory/barang/' . $medisId;
            $satuan_url = $this->api_url . '/ref/inventory/satuan';
            $industri_url = $this->api_url . '/ref/inventory/industri';
            $jenis_url = $this->api_url . '/ref/inventory/jenis';
            $kategori_url = $this->api_url . '/ref/inventory/kategori';
            $golongan_url = $this->api_url . '/ref/inventory/golongan';

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            curl_close($ch_medis);

            $ch_satuan = curl_init($satuan_url);
            curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_satuan = curl_exec($ch_satuan);
            $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            curl_close($ch_satuan);

            $ch_industri = curl_init($industri_url);
            curl_setopt($ch_industri, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_industri, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_industri = curl_exec($ch_industri);
            $http_status_code_industri = curl_getinfo($ch_industri, CURLINFO_HTTP_CODE);
            curl_close($ch_industri);

            $ch_jenis = curl_init($jenis_url);
            curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_jenis = curl_exec($ch_jenis);
            $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);
            curl_close($ch_jenis);

            $ch_kategori = curl_init($kategori_url);
            curl_setopt($ch_kategori, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_kategori, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_kategori = curl_exec($ch_kategori);
            $http_status_code_kategori = curl_getinfo($ch_kategori, CURLINFO_HTTP_CODE);
            curl_close($ch_kategori);

            $ch_golongan = curl_init($golongan_url);
            curl_setopt($ch_golongan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_golongan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
            $response_golongan = curl_exec($ch_golongan);
            $http_status_code_golongan = curl_getinfo($ch_golongan, CURLINFO_HTTP_CODE);
            curl_close($ch_golongan);

            if ($http_status_code_medis !== 200) {
                return $this->renderErrorView($http_status_code_medis);
            }
            if ($http_status_code_satuan !== 201) {
                return $this->renderErrorView($http_status_code_satuan);
            }
            if ($http_status_code_industri !== 201) {
                return $this->renderErrorView($http_status_code_industri);
            }
            if ($http_status_code_jenis !== 201) {
                return $this->renderErrorView($http_status_code_jenis);
            }
            if ($http_status_code_kategori !== 201) {
                return $this->renderErrorView($http_status_code_kategori);
            }
            if ($http_status_code_golongan !== 201) {
                return $this->renderErrorView($http_status_code_golongan);
            }

            $medis_data = json_decode($response_medis, true);
            $satuan_data = json_decode($response_satuan, true);
            $industri_data = json_decode($response_industri, true);
            $jenis_data = json_decode($response_jenis, true);
            $kategori_data = json_decode($response_kategori, true);
            $golongan_data = json_decode($response_golongan, true);

            $this->addBreadcrumb('Inventaris', 'inventarismedis');
            $this->addBreadcrumb('Barang Medis', 'medis');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/inventaris/medis/edit_medis', [
                'medis_data' => $medis_data['data'],
                'satuan_data' => $satuan_data['data'],
                'industri_data' => $industri_data['data'],
                'jenis_data' => $jenis_data['data'],
                'kategori_data' => $kategori_data['data'],
                'golongan_data' => $golongan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitEditMedis($medisId)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $kode = $this->request->getPost('kode');
            $nama = $this->request->getPost('nama');
            $isi = intval($this->request->getPost('isi'));
            $kapasitas = intval($this->request->getPost('kapasitas'));
            $kandungan = $this->request->getPost('kandungan');
            $indusfarmasi = intval($this->request->getPost('indusfarmasi'));
            $satuan = intval($this->request->getPost('satuan'));
            $satkecil = intval($this->request->getPost('satkecil'));
            $jenis = intval($this->request->getPost('jenis'));
            $kategori = intval($this->request->getPost('kategori'));
            $golongan = intval($this->request->getPost('golongan'));
            $hargadasar = intval($this->request->getPost('hargadasar'));
            $hargabeli = intval($this->request->getPost('hargabeli'));
            $hargaralan = intval($this->request->getPost('hargaralan'));
            $hargakelas1 = intval($this->request->getPost('hargakelas1'));
            $hargakelas2 = intval($this->request->getPost('hargakelas2'));
            $hargakelas3 = intval($this->request->getPost('hargakelas3'));
            $hargautama = intval($this->request->getPost('hargautama'));
            $hargavip = intval($this->request->getPost('hargavip'));
            $hargavvip = intval($this->request->getPost('hargavvip'));
            $hargaapotekluar = intval($this->request->getPost('hargaapotekluar'));
            $hargaobatbebas = intval($this->request->getPost('hargaobatbebas'));
            $hargaobatkaryawan = intval($this->request->getPost('hargakaryawan'));
            $stokminimum = intval($this->request->getPost('stokminimum'));
            $kadaluwarsa = $this->request->getPost('kadaluwarsa');
            if ($kadaluwarsa === "") {
                $kadaluwarsaformat = '0001-01-01';
            } else {
                $kadaluwarsaformat = $kadaluwarsa;
            }
            $medis_url = $this->api_url . '/inventory/barang/' . $medisId;

            $postDataMedis = [
                'kode_barang' => $kode,
                'nama' => $nama,
                'isi' => $isi,
                'kapasitas' => $kapasitas,
                'kandungan' => $kandungan,
                'id_industri' => $indusfarmasi,
                'id_satbesar' => $satuan,
                'id_satuan' => $satkecil,
                'id_jenis' => $jenis,
                'id_kategori' => $kategori,
                'id_golongan' => $golongan,
                'h_dasar' => $hargadasar,
                'h_beli' => $hargabeli,
                'h_ralan' => $hargaralan,
                'h_kelas1' => $hargakelas1,
                'h_kelas2' => $hargakelas2,
                'h_kelas3' => $hargakelas3,
                'h_utama' => $hargautama,
                'h_vip' => $hargavip,
                'h_vvip' => $hargavvip,
                'h_beliluar' => $hargaapotekluar,
                'h_jualbebas' => $hargaobatbebas,
                'h_karyawan' => $hargaobatkaryawan,
                'stok_minimum' => $stokminimum,
                'kadaluwarsa' => $kadaluwarsaformat,
            ];
            $tambah_medis_JSON = json_encode($postDataMedis);

            $ch_medis = curl_init($medis_url);
            curl_setopt($ch_medis, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch_medis, CURLOPT_POSTFIELDS, $tambah_medis_JSON);
            curl_setopt($ch_medis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_medis, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($tambah_medis_JSON),
                'Authorization: Bearer ' . $token,
            ]);
            $response_medis = curl_exec($ch_medis);
            $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
            curl_close($ch_medis);
            if ($http_status_code_medis === 200) {
                return redirect()->to(base_url('datamedis'));
            } else {
                return $this->renderErrorView(500);
            }
        } else {
            return $this->renderErrorView(401);
        }
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
    // public function hapusMedis($medisId)
    // {
    //     if (!session()->has('jwt_token')) {
    //         return $this->renderErrorView(401);
    //     }

    //     $token = session()->get('jwt_token');
    //     $medis_url = $this->api_url . '/inventory/barang/' . $medisId;
    //     $ruangan_url = $this->api_url . '/ref/inventory/ruangan';

    //     // Function to initialize cURL
    //     function initCurl($url, $token, $customRequest = null)
    //     {
    //         $ch = curl_init($url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
    //         if ($customRequest) {
    //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customRequest);
    //         }
    //         return $ch;
    //     }

    //     // Retrieve medis data
    //     $ch_medis = initCurl($medis_url, $token);
    //     $response_medis = curl_exec($ch_medis);
    //     $http_status_code_medis = curl_getinfo($ch_medis, CURLINFO_HTTP_CODE);
    //     curl_close($ch_medis);

    //     if ($http_status_code_medis !== 200) {
    //         return "Error fetching medis data: " . $response_medis;
    //     }

    //     $medis_data = json_decode($response_medis, true);
    //     $medis = $medis_data['data'] ?? null;

    //     // Retrieve gudang data
    //     $gudang_url = $this->api_url . '/inventory/gudang/barang/' . $medisId;
    //     $ch_gudang = initCurl($gudang_url, $token);
    //     $response_gudang = curl_exec($ch_gudang);
    //     $http_status_code_gudang = curl_getinfo($ch_gudang, CURLINFO_HTTP_CODE);
    //     curl_close($ch_gudang);

    //     if ($http_status_code_gudang !== 200) {
    //         return "Error fetching gudang data: " . $response_gudang;
    //     }

    //     $gudang_data = json_decode($response_gudang, true);
    //     $gudang = $gudang_data['data'] ?? null;

    //     // Retrieve ruangan data
    //     $ch_ruangan = initCurl($ruangan_url, $token);
    //     $response_ruangan = curl_exec($ch_ruangan);
    //     $http_status_code_ruangan = curl_getinfo($ch_ruangan, CURLINFO_HTTP_CODE);
    //     curl_close($ch_ruangan);

    //     if ($http_status_code_ruangan !== 201) {
    //         return "Error fetching ruangan data: " . $response_ruangan;
    //     }

    //     $ruangan_data = json_decode($response_ruangan, true);
    //     $ruangan = $ruangan_data['data'] ?? null;

    //     // Delete gudang data
    //     $http_status_code_gudang_final = 204;
    //     foreach ($ruangan as $rgn) {
    //         foreach ($gudang as $gdg) {
    //             if ($gdg['id_ruangan'] === $rgn['id']) {
    //                 $gudang_delete_url = $this->api_url . '/inventory/gudang/' . $gdg['id'];
    //                 $ch_gudang_delete = initCurl($gudang_delete_url, $token, 'DELETE');
    //                 $response_gudang_delete = curl_exec($ch_gudang_delete);
    //                 $http_status_code_gudang_final = curl_getinfo($ch_gudang_delete, CURLINFO_HTTP_CODE);
    //                 curl_close($ch_gudang_delete);

    //                 if ($http_status_code_gudang_final !== 204) {
    //                     return "Error deleting gudang data: " . $response_gudang_delete;
    //                 }
    //             }
    //         }
    //     }

    //     // Delete medis data
    //     $ch_medis_delete = initCurl($medis_url, $token, 'DELETE');
    //     $response_medis_delete = curl_exec($ch_medis_delete);
    //     $http_status_code_medis_delete = curl_getinfo($ch_medis_delete, CURLINFO_HTTP_CODE);
    //     curl_close($ch_medis_delete);

    //     if ($http_status_code_medis_delete === 204) {
    //         return redirect()->to(base_url('datamedis'));
    //     } else {
    //         return "Error deleting medis data: " . $response_medis_delete;
    //     }
    // }
