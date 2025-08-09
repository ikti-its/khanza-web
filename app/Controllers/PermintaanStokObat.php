<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PermintaanStokObat extends BaseController
{
    public function dataPermintaanStokObat()
    {
        $title = 'Data Permintaan Stok Obat';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $url = $this->api_url . '/permintaan-stok-obat';
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
            // dd($permintaan_data);
            if (!isset($permintaan_data['data'])) {
                return $this->renderErrorView(500);
            }

            $permintaan_list = $permintaan_data['data']; // now clearly a list
            $permintaan = $permintaan_list[0] ?? []; // pick first item safely

            $stok_obat = [];
            if (isset($permintaan['no_permintaan'])) {
                $stok_obat_result = $this->fetchStokObatByNoPermintaan($permintaan['no_permintaan']);

                // Make sure it is array
                if (is_array($stok_obat_result)) {
                    $stok_obat = $stok_obat_result;
                } else {
                    // Try decoding if it's a JSON string (optional fallback)
                    $decoded = json_decode($stok_obat_result, true);
                    if (is_array($decoded)) {
                        $stok_obat = $decoded;
                    }
                }
            }

            $no_rawat = $permintaan['no_rawat'] ?? null;

            $rawatinap = [];
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

                $parsed = json_decode($response, true);
                if (isset($parsed['data'])) {
                    $rawatinap = $parsed['data'];
                }
            }
            
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Permintaan Stok Obat', 'permintaanstokobat');
            $breadcrumbs = $this->getBreadcrumbs();
// dd($stok_obat);
            return view('/admin/stokobatpasien/permintaanstokobat_data', [
                'permintaanstokobat_data' => $permintaan_data['data'],
                'title' => $title,
                'rawatinap' => $rawatinap,
                'breadcrumbs' => $breadcrumbs,
                'permintaanstokobat' => $permintaan,
                'permintaanstokobat_data' => $permintaan_list,
                'stok_obat' => $stok_obat,
                'meta_data' => $permintaan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahPermintaanStokObat()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Tambah Permintaan Stok Obat';
        $permintaan = [];
        $obat_list = [];
    
        $nomor_rawat = $this->request->getGet('nomor_rawat');
    
        // Ambil data rawatinap berdasarkan nomor_rawat
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
    
                $permintaan = [
                    'nomor_rm'      => $rawatinap['nomor_rm'] ?? '',
                    'nomor_rawat'   => $rawatinap['nomor_rawat'] ?? '',
                    'nama_pasien'   => $rawatinap['nama_pasien'] ?? '',
                    'nama_dokter'   => $rawatinap['nama_dokter'] ?? '',
                    'kode_dokter'   => $rawatinap['kode_dokter'] ?? '',
                    'no_permintaan' => 'PRP' . date('Ymd') . rand(1000, 9999),
                    'tgl_permintaan'=> date('Y-m-d'),
                    'jam'           => date('H:i:s')
                ];
            }
        }
    
        // Ambil daftar obat dari API
        $obat_url = $this->api_url . '/pemberian-obat/databarang';
        $ch = curl_init($obat_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $obat_response = curl_exec($ch);
        curl_close($ch);
    
        $obat_parsed = json_decode($obat_response, true);
        if (isset($obat_parsed['data'])) {
            $obat_list = $obat_parsed['data'];
        }

        $no_rawat = $this->request->getUri()->getSegment(3);
    
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Permintaan Stok Obat', 'permintaanstokobat');
        $this->addBreadcrumb('Tambah', 'tambah');
    
        return view('/admin/stokobatpasien/tambah_permintaanstokobat', [
            'permintaanstokobat' => $permintaan,
            'no_rawat' => $no_rawat,
            'obat_list' => $obat_list,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }
    

    public function submitTambahPermintaanStokObat()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Decode stok_obat input
        $stokObatRaw = $this->request->getPost('stok_obat');
        $stokObat = is_string($stokObatRaw) ? json_decode($stokObatRaw, true) : ($stokObatRaw ?? []);

        // Normalize each field in stok_obat
        foreach ($stokObat as &$item) {
            foreach ($item as $key => $val) {
                if ($val === '') {
                    $item[$key] = null;
                }
            }
        }
        unset($item);

        // Normalize 'jam' into a flat string
        $jamRaw = $this->request->getPost('jam');

        if (is_array($jamRaw)) {
            // If your form is accidentally posting jam as an array keyed by item code,
            // you can either pick one value or just use the current time:
            // Option A: pick the first element of the first key
            $firstKey = array_key_first($jamRaw);
            $firstList = $jamRaw[$firstKey] ?? [];
            $jam = is_array($firstList) && count($firstList)
                ? $firstList[0]            // e.g. "12"
                : date('H:i:s');
            // Option B: override with now
            // $jam = date('H:i:s');
        } else {
            $jam = $jamRaw ?: date('H:i:s');
        }

        // Normalize main request data
        $postData = [
            'no_permintaan'  => $this->request->getPost('no_permintaan'),
            'tgl_permintaan' => $this->request->getPost('tgl_permintaan') ?: date('Y-m-d'),
            'jam'            => $jam,
            'no_rawat'       => $this->request->getPost('no_rawat'),
            'kd_dokter'      => $this->request->getPost('kode_dokter'),
            'status'         => 'Belum',
            'tgl_validasi'   => date('Y-m-d'),
            'jam_validasi'   => date('H:i:s'),
            'stok_obat'      => $stokObat,
        ];

        // Replace any remaining empty strings with null
        foreach ($postData as $key => $val) {
            if ($val === '') {
                $postData[$key] = null;
            }
        }

        // merge header and details into one array
        $payloadData = $postData;
        $payloadData['stok_obat'] = $stokObat;

        $payload = json_encode($payloadData, JSON_UNESCAPED_UNICODE);
        log_message('debug', 'Full JSON payload: ' . $payload);

        log_message('debug', 'Payload dikirim ke API: ' . $payload);
        error_log(">>> PERMINTAAN-STOK-OBAT DETAIL PAYLOAD: " . json_encode($postData));


        $ch = curl_init($this->api_url . '/permintaan-stok-obat/detail');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (! in_array($status, [200,201])) {
        log_message('error', "Failed to insert permintaan_stok_obat: $response");
        return $this->renderErrorView($status);
        }

        if ($status !== 200 && $status !== 201) {
            log_message('error', 'Failed to insert permintaan_stok_obat: ' . $response);
            return $this->renderErrorView($status);
        }

        return redirect()->to(base_url('permintaanstokobat'))
            ->with('success', 'Permintaan stok obat berhasil disimpan.');
    }


    public function editPermintaanStokObat($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Edit Permintaan Stok Obat';
    
        // Ambil data permintaan
        $permintaanUrl = $this->api_url . '/permintaan-stok-obat/' . $noPermintaan;
        $ch = curl_init($permintaanUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($status !== 200 || !$response) {
            return $this->renderErrorView($status);
        }
    
        $responseData = json_decode($response, true);
        $permintaan = $responseData['data'] ?? [];
        $noRawat = $permintaan['no_rawat'] ?? '';
    
        // ✅ Ambil daftar obat
        $obatUrl = $this->api_url . '/pemberian-obat/databarang';
        $ch = curl_init($obatUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $obatResponse = curl_exec($ch);
        curl_close($ch);
    
        $obatData = json_decode($obatResponse, true);
        $obatList = $obatData['data'] ?? [];
    
        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Permintaan Stok Obat', 'permintaanstokobat');
        $this->addBreadcrumb('Edit', 'edit');
    
        return view('/admin/stokobatpasien/edit_permintaanstokobat', [
            'permintaanstokobat' => $permintaan,
            'no_rawat' => $noRawat,
            'obat_list' => $obatList,
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }
    


    public function submitEditPermintaanStokObat($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/permintaan-stok-obat/' . $noPermintaan;

        $postData = [
            'no_permintaan'  => $noPermintaan,
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
            return redirect()->to(base_url('permintaanstokobat'))->with('success', 'Permintaan stok obat updated');
        } else {
            return $this->renderErrorView($http_status);
        }
    }

    public function hapusPermintaanStokObat($noPermintaan)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $delete_url = $this->api_url . "/permintaan-stok-obat/$noPermintaan";

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

        return redirect()->to('/permintaanstokobat')->with('success', 'Permintaan stok obat deleted');
    }

    public function permintaanStokObatData($nomorRawat)
    {
        $title = 'Detail Permintaan Stok Obat';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/permintaan-stok-obat/nomor-rawat/' . $nomorRawat;

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

            log_message('error', 'PermintaanStokObatData Response: ' . $response);
            log_message('error', 'PermintaanStokObatData HTTP Status: ' . $http_status);

            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }

            $permintaan_data = json_decode($response, true);

            if (!isset($permintaan_data['data'])) {
                log_message('error', 'PermintaanStokObatData: data key not found');
                return $this->renderErrorView(500);
            }

            $data = $permintaan_data['data'];

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Permintaan Stok Obat', 'permintaanstokobat');
            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/stokobatpasien/permintaanstokobat_data', [
                'permintaanstokobat_data' => $data,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $permintaan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);

        } catch (\Throwable $e) {
            log_message('critical', 'PermintaanStokObatData Exception: ' . $e->getMessage());
            return $this->renderErrorView(500);
        }
    }
    private function getPermintaanStokObatListFromAPI($token)
    {
        $url = $this->api_url . '/permintaan-stok-obat';
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

        // Step 1: Get Rawat Inap Data
        $url = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($response, true);

        if ($decoded && isset($decoded['data'])) {
            $rawatinap = is_string($decoded['data']) 
                ? json_decode($decoded['data'], true) 
                : $decoded['data'];

            if (!$rawatinap) {
                return redirect()->back()->with('error', 'Gagal memproses data rawat inap.');
            }

            // Prepare Form Data
            $formData = [
                'no_permintaan'  => 'PRP' . date('Ymd') . rand(100, 999),
                'tgl_permintaan' => date('Y-m-d'),
                'jam'            => date('H:i:s'),
                'no_rawat'       => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
                'kd_dokter'      => $rawatinap['kode_dokter'] ?? '',
                'status'         => 'Belum',
                'tgl_validasi'   => null,
                'jam_validasi'   => null,
            ];

            // Step 2: Get Dokter List for Dynamic Dropdown
            $dokterList = $this->getDokterListFromAPI($token);
            $obatList = $this->getObatListFromAPI($token);
    // dd($dokterList);
            return view('admin/stokobatpasien/tambah_permintaanstokobat', [
                'title'              => 'Tambah Permintaan Stok Obat',
                'permintaanstokobat' => $formData,
                'dokterList'         => $dokterList,
                'obat_list'          => $obatList, 
            ]);
        }

        return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
    }


    public function fetchStokObatByNoPermintaan($noPermintaan)
    {
        $token = session()->get('jwt_token');
        $url = $this->api_url . '/stok-obat-pasien/' . $noPermintaan;

        // Request stok_obat_pasien data
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $parsed = json_decode($response, true);
        $stokObat = is_array($parsed) && isset($parsed['data']) && is_array($parsed['data'])
            ? $parsed['data']
            : [];

        // Request databarang reference data
        $barangUrl = $this->api_url . '/pemberian-obat/databarang';
        $ch = curl_init($barangUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $barangResponse = curl_exec($ch);
        curl_close($ch);

        $barangParsed = json_decode($barangResponse, true);

        $barangList = $barangParsed['data'] ?? $barangParsed; // support both formats
        $barangMap = [];

        if (is_array($barangList)) {
            foreach ($barangList as $b) {
                if (isset($b['kode_obat'], $b['nama_obat'])) {
                    $kode = trim((string)$b['kode_obat']);
                    $barangMap[$kode] = $b['nama_obat'];
                }
            }
        }


        // Enrich stokObat entries with resolved nama_barang
        foreach ($stokObat as &$obat) {
            if (isset($obat['kode_brng'])) {
                $key = trim((string)$obat['kode_brng']);
                $obat['nama_barang'] = $barangMap[$key] ?? '-';
            } else {
                $obat['nama_barang'] = '-';
            }
        }

        return $stokObat;
    }

    private function getDokterListFromAPI($token)
    {
        $url = $this->api_url . '/dokter';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
    // dd($data);
        // If response or 'data' is not array, return empty array
        if (!is_array($data) || !isset($data['data']) || !is_array($data['data'])) {
            return [];
        }

        return $data['data'];
    }

    private function getObatListFromAPI($token)
    {
        $url = $this->api_url . '/pemberian-obat/databarang'; // adjust path if needed
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return is_array($data['data'] ?? null) ? $data['data'] : [];
    }
}
