<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StokObatPasien extends BaseController
{
    public function dataStokObatPasien()
    {
        $title = 'Data Stok Obat Pasien';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/stok-obat-pasien';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Handle connection failure or non-200 status
        if ($httpStatus !== 200 || !$response) {
            return $this->renderErrorView($httpStatus);
        }

        $decoded = json_decode($response, true);
        if (!isset($decoded['data']) || !is_array($decoded['data'])) {
            return $this->renderErrorView(500);
        }

        $stokObatData = $decoded['data'];
        $metaData = $decoded['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => count($stokObatData)];

        // Optional: inject default value for missing nama_brng to avoid view errors
        foreach ($stokObatData as &$item) {
            if (!isset($item['nama_brng'])) {
                $item['nama_brng'] = '';
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Stok Obat Pasien', 'stokobatpasien');
    // dd($stokObatData);
        return view('/admin/stokobatpasien/stokobatpasien_data', [
            'stokobatpasien_data' => $stokObatData,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'meta_data' => $metaData,
        ]);
    }

    public function tambahStokObatPasien()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Stok Obat Pasien';
        $stok = [];

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
            log_message('debug', print_r($parsed, true));

            if (isset($parsed['data'])) {
                $rawatinap = $parsed['data'];

                $stok = [
                    'nomor_rm'    => $rawatinap['nomor_rm'] ?? '',
                    'nomor_rawat' => $rawatinap['nomor_rawat'] ?? '',
                    'nama_pasien' => $rawatinap['nama_pasien'] ?? '',
                    'nama_dokter' => $rawatinap['nama_dokter'] ?? '',
                    'kode_dokter' => $rawatinap['kode_dokter'] ?? '',
                    'no_permintaan' => 'SOP' . date('Ymd') . rand(1000, 9999),
                    'tanggal' => date('Y-m-d'),
                    'jam' => date('H:i:s')
                ];
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Stok Obat Pasien', 'stokobatpasien');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/stokobatpasien/tambah_stokobatpasien', [
            'stokobatpasien' => $stok,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitTambahStokObatPasien()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $postData = [
            'no_permintaan' => $this->request->getPost('no_permintaan'),
            'tanggal'       => $this->request->getPost('tanggal') ?? date('Y-m-d'),
            'jam'           => $this->request->getPost('jam') ?? date('H:i:s'),
            'no_rawat'      => $this->request->getPost('nomor_rawat'),
            'kode_brng'     => $this->request->getPost('kode_brng'),
            'jumlah'        => floatval($this->request->getPost('jumlah')),
            'kd_bangsal'    => $this->request->getPost('kd_bangsal'),
            'no_batch'      => $this->request->getPost('no_batch'),
            'no_faktur'     => $this->request->getPost('no_faktur'),
            'aturan_pakai'  => $this->request->getPost('aturan_pakai'),

            // Jam-jam inputan (0–23)
        ];
        for ($i = 0; $i < 24; $i++) {
            $key = sprintf('jam%02d', $i);
            $postData[$key] = $this->request->getPost($key) === '1';
        }

        $url = $this->api_url . '/stok-obat-pasien';
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
            log_message('error', 'Failed to insert stok_obat_pasien: ' . $response);
            return $this->renderErrorView($status);
        }

        return redirect()->to(base_url('stokobatpasien'))
            ->with('success', 'Stok obat pasien berhasil disimpan.');
    }

    public function editStokObatPasien($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Stok Obat Pasien';

        $url = $this->api_url . '/stok-obat-pasien/' . $noPermintaan;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $stok = $data['data'] ?? [];

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Stok Obat Pasien', 'stokobatpasien');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/stokobatpasien/edit_stokobatpasien', [
            'stokobatpasien' => $stok,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditStokObatPasien($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/stok-obat-pasien/' . $noPermintaan;

        $postData = [
            'no_permintaan' => $noPermintaan,
            'tanggal'       => $this->request->getPost('tanggal'),
            'jam'           => $this->request->getPost('jam'),
            'no_rawat'      => $this->request->getPost('no_rawat'),
            'kode_brng'     => $this->request->getPost('kode_brng'),
            'jumlah'        => floatval($this->request->getPost('jumlah')),
            'kd_bangsal'    => $this->request->getPost('kd_bangsal'),
            'no_batch'      => $this->request->getPost('no_batch'),
            'no_faktur'     => $this->request->getPost('no_faktur'),
            'aturan_pakai'  => $this->request->getPost('aturan_pakai'),
        ];

        // 🕐 Tambah jam00 s/d jam23
        for ($i = 0; $i < 24; $i++) {
            $key = sprintf('jam%02d', $i);
            $postData[$key] = $this->request->getPost($key) === '1';
        }

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
            return redirect()->to(base_url('stokobatpasien'))->with('success', 'Stok obat pasien berhasil diperbarui.');
        } else {
            return $this->renderErrorView($http_status);
        }
    }

    public function hapusStokObatPasien($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $delete_url = $this->api_url . "/stok-obat-pasien/$noPermintaan";

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

        return redirect()->to('/stokobatpasien')->with('success', 'Stok obat pasien berhasil dihapus.');
    }

    public function stokObatPasienData($nomorRawat)
    {
        $title = 'Detail Stok Obat Pasien';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/stok-obat-pasien/nomor-rawat/' . $nomorRawat;

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            log_message('error', 'StokObatPasienData Response: ' . $response);
            log_message('error', 'StokObatPasienData HTTP Status: ' . $http_status);

            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }

            $stok_data = json_decode($response, true);

            if (!isset($stok_data['data'])) {
                log_message('error', 'StokObatPasienData: data key not found');
                return $this->renderErrorView(500);
            }

            $data = $stok_data['data'];

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Stok Obat Pasien', 'stokobatpasien');
            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/stokobatpasien/stokobatpasien_data', [
                'stokobatpasien_data' => $data,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $stok_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);

        } catch (\Throwable $e) {
            log_message('critical', 'StokObatPasienData Exception: ' . $e->getMessage());
            return $this->renderErrorView(500);
        }
    }

    private function getStokObatPasienListFromAPI($token)
    {
        $url = $this->api_url . '/stok-obat-pasien';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        $parsed = json_decode($res, true);
        return $parsed['data'] ?? [];
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

        if ($data && isset($data['data'])) {
            $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];

            $formData = [
                'no_permintaan'  => 'SOP' . date('Ymd') . rand(100, 999),
                'tanggal'        => date('Y-m-d'),
                'jam'            => date('H:i:s'),
                'no_rawat'       => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
                'kode_brng'      => '',
                'jumlah'         => '',
                'kd_bangsal'     => '',
                'no_batch'       => '',
                'no_faktur'      => '',
                'aturan_pakai'   => '',
            ];

            return view('admin/stokobatpasien/tambah_stokobatpasien', [
                'title' => 'Tambah Stok Obat Pasien',
                'stokobatpasien' => $formData,
            ]);
        }

        return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
    }
}