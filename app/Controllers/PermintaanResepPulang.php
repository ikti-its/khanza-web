<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PermintaanResepPulang extends BaseController
{
    public function dataPermintaanResepPulang()
    {
        $title = 'Data Permintaan Resep Pulang';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Step 1: Get permintaan resep pulang list
        $url = $this->api_url . '/permintaan-resep-pulang';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $permintaan_data = json_decode($response, true);
        if (!isset($permintaan_data['data'])) {
            return $this->renderErrorView(500);
        }

        $permintaan_list = $permintaan_data['data'];

        // Step 2: Loop through each permintaan and fetch rawat inap details
        foreach ($permintaan_list as &$item) {
            $no_rawat = $item['no_rawat'] ?? null;

            $item['nama_pasien'] = '-';
            $item['kamar'] = '-';

            if ($no_rawat) {
                $url = $this->api_url . '/rawatinap/' . $no_rawat;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response = curl_exec($ch);
                curl_close($ch);

                $rawat_data = json_decode($response, true);
                if (isset($rawat_data['data'])) {
                    $item['nama_pasien'] = $rawat_data['data']['nama_pasien'] ?? '-';
                    $item['kamar'] = $rawat_data['data']['kamar'] ?? '-';
                }
            }
        }

        // Step 3: Breadcrumbs and render view
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Permintaan Resep Pulang', 'PermintaanResepPulang');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/permintaanreseppulang/permintaanreseppulang_data', [
            'permintaanreseppulang_data' => $permintaan_list,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $permintaan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);
    }


    public function tambahPermintaanResepPulang()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Tambah Permintaan Resep Pulang';
        $permintaan = [];
        $dokter_list = [];
        $obat_list = [];
    
        $nomor_rawat = $this->request->getGet('nomor_rawat');
    
        if ($nomor_rawat) {
            $url = $this->api_url . '/rawatinap/' . $nomor_rawat;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $parsed = json_decode($response, true);
    
            if (isset($parsed['data'])) {
                $rawatinap = $parsed['data'];
                $permintaan = [
                    'nomor_rm'        => $rawatinap['nomor_rm'] ?? '',
                    'nomor_rawat'     => $rawatinap['nomor_rawat'] ?? '',
                    'nama_pasien'     => $rawatinap['nama_pasien'] ?? '',
                    'nama_dokter'     => $rawatinap['nama_dokter'] ?? '',
                    'kode_dokter'     => $rawatinap['kode_dokter'] ?? '',
                    'kd_bangsal'      => $rawatinap['kamar'] ?? '',
                    'no_permintaan'   => 'PRP' . date('Ymd') . rand(1000, 9999),
                    'tgl_permintaan'  => date('Y-m-d'),
                    'jam'             => date('H:i:s'),
                    'tgl_validasi'    => date('Y-m-d'),
                    'jam_validasi'    => date('H:i:s'),
                    'status'          => 'Belum'
                ];
            }
        }
    
        // ✅ Fetch dokter list
        $dokterResponse = $this->callApi('/dokterjaga', $token);
        $dokter_list = $dokterResponse['data'] ?? [];
    
        // ✅ Fetch obat list
        $obatResponse = $this->callApi('/databarang', $token);
        $obat_list = $obatResponse['data'] ?? [];
    
        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Permintaan Resep Pulang', 'permintaanreseppulang');
        $this->addBreadcrumb('Tambah', 'tambah');
    
        return view('/admin/permintaanreseppulang/tambah_permintaanreseppulang', [
            'permintaanreseppulang' => $permintaan,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'dokter_list' => $dokter_list,
            'obat_list' => $obat_list
        ]);
    }
    
    // Utility to avoid repetition
    private function callApi($endpoint, $token)
    {
        $url = $this->api_url . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    


    public function submitTambahPermintaanResepPulang()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $postData = [
            'no_permintaan' => $this->request->getPost('no_permintaan') ?? ('PRP' . date('Ymd') . rand(1000, 9999)),
            'tgl_permintaan' => $this->request->getPost('tgl_permintaan') ?? date('Y-m-d'),
            'jam'            => $this->request->getPost('jam') ?? date('H:i:s'),
            'no_rawat'       => $this->request->getPost('nomor_rawat'),
            'kd_dokter'      => $this->request->getPost('kode_dokter'),
            'status'         => $this->request->getPost('status') ?? 'Belum',
            'tgl_validasi'   => $this->request->getPost('tgl_validasi') ?? date('Y-m-d'),
            'jam_validasi'   => $this->request->getPost('jam_validasi') ?? date('H:i:s'),
            'kode_brng'      => $this->request->getPost('kode_brng'), // new
            'jumlah'         => (int) $this->request->getPost('jumlah'), // new
            'aturan_pakai'   => $this->request->getPost('aturan_pakai'), // new
        ];

        $url = $this->api_url . '/permintaan-resep-pulang';
        $payload = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200 && $status !== 201) {
            log_message('error', 'Failed to insert permintaan_resep_pulang: ' . $response);
            return $this->renderErrorView($status);
        }

        return redirect()->to(base_url('permintaanreseppulang'))
            ->with('success', 'Permintaan resep pulang berhasil disimpan.');
    }

    public function editPermintaanResepPulang($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Permintaan Resep Pulang';

        // ✅ Ambil data permintaan resep pulang
        $url = $this->api_url . '/permintaan-resep-pulang/' . $noPermintaan;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    // dd($noPermintaan);
        $data = json_decode($response, true);
        $permintaan = $data['data'] ?? [];

        // ✅ Ambil list dokter
        $dokterList = $this->getDokterListFromAPI($token);

        // ✅ Ambil data obat
        $obatUrl = $this->api_url . '/pemberian-obat/databarang';
        $chObat = curl_init($obatUrl);
        curl_setopt($chObat, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chObat, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $obatResponse = curl_exec($chObat);
        curl_close($chObat);

        $obatData = json_decode($obatResponse, true);
        $obatList = $obatData['data'] ?? [];

        // ✅ Breadcrumb
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Permintaan Resep Pulang', 'permintaanreseppulang');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/permintaanreseppulang/edit_permintaanreseppulang', [
            'permintaanreseppulang' => $permintaan,
            'title' => $title,
            'dokter_list' => $dokterList,
            'obat_list' => $obatList, // ✅ Kirim ke view
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditPermintaanResepPulang($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/permintaan-resep-pulang/' . $noPermintaan;

        $postData = [
            'tgl_permintaan' => $this->request->getPost('tgl_permintaan'),
            'jam'            => $this->request->getPost('jam'),
            'no_rawat'       => $this->request->getPost('no_rawat'),
            'kd_dokter'      => $this->request->getPost('kd_dokter'),
            'status'         => $this->request->getPost('status'),
            'tgl_validasi'   => $this->request->getPost('tgl_validasi'),
            'jam_validasi'   => $this->request->getPost('jam_validasi'),
        ];

        $payload = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200) {
            return redirect()->to(base_url('permintaanreseppulang'))->with('success', 'Permintaan resep pulang updated');
        } else {
            return $this->renderErrorView($http_status);
        }
    }

    public function hapusPermintaanResepPulang($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $delete_url = $this->api_url . "/permintaan-resep-pulang/$noPermintaan";

        $ch = curl_init($delete_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        return redirect()->to('/permintaanreseppulang')->with('success', 'Permintaan resep pulang deleted');
    }

    public function submitFromRawatinap($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

        $token = session()->get('jwt_token');

        // Step 1: Get rawat inap data
        $url_rawatinap = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch = curl_init($url_rawatinap);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['data']) || $data['data'] === null) {
            return redirect()->back()->with('error', 'Rawat inap data not found.');
        }

        $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];
        $dokterList = $this->getDokterListFromAPI($token);
        $obatList = $this->getObatListFromAPI($token);

        if (!is_array($rawatinap)) {
            return redirect()->back()->with('error', 'Invalid rawat inap data format.');
        }

        // Step 2: Prepare prefill for the form
        $preFill = [
            'no_permintaan' => 'PRP' . date('Ymd') . rand(1000, 9999), // Generate new nomor permintaan
            'tgl_permintaan' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'no_rawat' => $rawatinap['nomor_rawat'] ?? '',
            'kd_dokter' => $rawatinap['kode_dokter'] ?? '',
            'status' => 'Belum',
            'tgl_validasi' => date('Y-m-d'),
            'jam_validasi' => date('H:i:s')
        ];

        // Step 3: Open the tambah permintaan reseppulang form
        return view('admin/permintaanreseppulang/tambah_permintaanreseppulang', [
            'prefill' => $preFill,
            'dokter_list' => $dokterList,
            'obat_list' => $obatList,
            'title' => 'Tambah Permintaan Resep Pulang'
        ]);
    }

    private function getDokterListFromAPI($token)
    {
        $url = $this->api_url . '/dokter'; // your API endpoint for dokter list
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['data'] ?? []; // assume your API returns { "data": [...] }
    }

    private function getObatListFromAPI($token)
    {
        $url = $this->api_url . '/pemberian-obat/databarang'; // adjust path if your API uses /v1/obat or something else

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        }

        return []; // fallback if API fails
    }
}