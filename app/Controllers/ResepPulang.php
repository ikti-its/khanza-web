<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ResepPulang extends BaseController
{

protected array $breadcrumbs = [];
    protected string $judul = 'Audit Resep Pulang';
    protected string $modul_path = '/reseppulang';
    protected string $api_path = '/reseppulang';
    protected string $kolom_id = 'no_rawat';
    protected array $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rawat'  , 'no_rawat'   , 'indeks'],
                            [1, 'Tanggal Resep', 'tanggal'    , 'tanggal'],
                            [1, 'Jam'          , 'jam'        , 'jam'],
                            [1, 'Pasien'       , 'nama_pasien', 'nama'],
                            [1, 'Obat'         , 'kode_brng'  , 'indeks'],
                            [1, 'Jumlah'       , 'jml_barang' , 'jumlah'],
                            [0, 'Harga'        , 'harga'      , 'uang'],
                            [0, 'Total'        , 'total'      , 'uang'],
                            [0, 'Dosis'        , 'dosis'      , 'jumlah'], 
                            [0, 'No. Batch'    , 'no_batch'   , 'indeks'],
                            [0, 'No. Faktur'   , 'no_faktur'  , 'indeks'],
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataResepPulang()
    {
        $title = 'Data Resep Pulang';
    
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
    
        // Fetch resep pulang data
        $url = $this->api_url . '/resep-pulang';
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
    
        $resep_data = json_decode($response, true);
        if (!isset($resep_data['data']) || !is_array($resep_data['data'])) {
            return $this->renderErrorView(500);
        }
    
        $reseppulang_data = $resep_data['data'];
    
        // Fetch nama_pasien for each no_rawat
        foreach ($reseppulang_data as &$item) {
            $noRawat = $item['no_rawat'] ?? null;
            if ($noRawat) {
                $rawatUrl = $this->api_url . '/rawatinap/' . $noRawat;
                $ch = curl_init($rawatUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $rawatResponse = curl_exec($ch);
                curl_close($ch);
    
                $rawatParsed = json_decode($rawatResponse, true);
                $item['nama_pasien'] = $rawatParsed['data']['nama_pasien'] ?? 'N/A';
            } else {
                $item['nama_pasien'] = 'N/A';
            }
        }
    
        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Pulang', 'reseppulang');
        $breadcrumbs = $this->getBreadcrumbs();
    
        return view('/admin/reseppulang/reseppulang_data', [
            'reseppulang_data' => $reseppulang_data,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);
    }
    

    public function tambahResepPulang()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Resep Pulang';
        $resep = [];

        $noRawat = $this->request->getGet('no_rawat');
        $kamar = $this->request->getGet('kamar');
        $noPermintaan = $this->request->getGet('no_permintaan');

        // Step 1: Get rawat inap data by nomor_rawat
        if ($noRawat) {
            $url = $this->api_url . '/rawatinap/' . $noRawat;
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
                $resep = [
                    'nomor_rm'     => $rawatinap['nomor_rm'] ?? '',
                    'nomor_rawat'  => $rawatinap['nomor_rawat'] ?? '',
                    'nama_pasien'  => $rawatinap['nama_pasien'] ?? '',
                    'nama_dokter'  => $rawatinap['nama_dokter'] ?? '',
                    'kode_dokter'  => $rawatinap['kode_dokter'] ?? '',
                    'tanggal'      => date('Y-m-d'),
                    'jam'          => date('H:i:s')
                ];
            }
        }

        // Step 2: Get obat list from permintaan_resep_pulang API
        $obat_list = [];
        if ($noPermintaan) {
            $obatUrl = $this->api_url . '/permintaan-resep-pulang/obat/' . $noPermintaan;
            $ch = curl_init($obatUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $obatResponse = curl_exec($ch);
            curl_close($ch);

            $parsedObat = json_decode($obatResponse, true);
        if (isset($parsedObat['data'])) {
            // Double-check if it's a JSON string (not array)
            if (is_string($parsedObat['data'])) {
                $obat_list = json_decode($parsedObat['data'], true);
            } else {
                $obat_list = $parsedObat['data'];
                $resep['dosis'] = $obat_list[0]['aturan_pakai'] ?? '';

            }
        }

        }
    // dd($obat_list);
        // Breadcrumbs
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Pulang', 'reseppulang');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/reseppulang/tambah_reseppulang', [
            'reseppulang' => $resep,
            'no_rawat' => $noRawat,
            'kamar' => $kamar,
            'no_permintaan' => $noPermintaan,
            'title' => $title,
            'obat_list' => $obat_list,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function tambahResepPulangFromPermintaan($noPermintaan)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Tambah Resep Pulang';
    $resep = [];

    $kamar = $this->request->getGet('kamar') ?? 'TIDAK ADA';
    $noRawat = null;

    // 🔹 Step 1: Ambil nomor_rawat dari master permintaan
    $masterUrl = $this->api_url . '/permintaan-resep-pulang/' . $noPermintaan;
    $ch = curl_init($masterUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $masterResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
// dd($masterResponse);
    $parsedMaster = json_decode($masterResponse, true);
    if ($httpCode === 200 && isset($parsedMaster['data']['no_rawat'])) {
    $noRawat = $parsedMaster['data']['no_rawat'];
} else {
    log_message('error', '❌ Gagal mengambil data master permintaan resep pulang: ' . $masterResponse);
}


    // 🔹 Step 2: Ambil daftar obat dari permintaan resep pulang
$obat_list = [];

if ($noPermintaan) {
    $obatUrl = $this->api_url . '/permintaan-resep-pulang/obat/' . $noPermintaan;
    $ch = curl_init($obatUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $obatResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $parsedObat = json_decode($obatResponse, true);

    if ($httpCode === 200 && isset($parsedObat['data'])) {
        $obat_list = is_string($parsedObat['data'])
            ? json_decode($parsedObat['data'], true)
            : $parsedObat['data'];

        if (!empty($obat_list)) {
            $resep['dosis'] = $obat_list[0]['aturan_pakai'] ?? '';
            $resep['jumlah'] = $obat_list[0]['jumlah'] ?? 0;
        }
    } else {
        log_message('error', '❌ Gagal mengambil data obat dari permintaan resep pulang. Status: ' . $httpCode . ' Response: ' . $obatResponse);
    }
}


// dd($obat_list);
    // 🔹 Step 3: Ambil data Rawat Inap berdasarkan nomor_rawat
    if ($noRawat) {
        $url = $this->api_url . '/rawatinap/' . $noRawat;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $parsed = json_decode($response, true);

        if ($httpCode === 200 && isset($parsed['data']) && is_array($parsed['data'])) {
            $rawatinap = $parsed['data'];

            $resep = array_merge($resep, [
                'nomor_rm'     => $rawatinap['nomor_rm'] ?? '',
                'nomor_rawat'  => $rawatinap['nomor_rawat'] ?? '',
                'nama_pasien'  => $rawatinap['nama_pasien'] ?? '',
                'nama_dokter'  => $rawatinap['dokter_pj'] ?? '',
                'kode_dokter'  => $rawatinap['kode_dokter'] ?? '',
                'tanggal'      => date('Y-m-d'),
                'jam'          => date('H:i:s')
            ]);

            $kamar = $this->request->getGet('kamar') ?? $rawatinap['kamar'] ?? null;
        } else {
            log_message('error', '❌ Gagal mengambil data rawatinap (' . $httpCode . '): ' . $response);
        }
    }

    // 🔹 Breadcrumbs
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Resep Pulang', 'reseppulang');
    $this->addBreadcrumb('Tambah', 'tambah');
// dd($noRawat);
    return view('/admin/reseppulang/tambah_reseppulang', [
        'reseppulang' => $resep,
        'no_rawat' => $noRawat,
        'kamar' => $kamar,
        'no_permintaan' => $noPermintaan,
        'title' => $title,
        'obat_list' => $obat_list,
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}




    public function submitTambahResepPulang()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $postData = [
            'no_rawat'   => $this->request->getPost('nomor_rawat'),
            'kode_brng'  => $this->request->getPost('kode_brng'),
            'jml_barang' => (float) $this->request->getPost('jml_barang'),
            'harga'      => (float) $this->request->getPost('harga'),
            'total'      => (float) $this->request->getPost('total'),
            'dosis'      => $this->request->getPost('dosis'),
            'tanggal'    => $this->request->getPost('tanggal') ?? date('Y-m-d'),
            'jam'        => $this->request->getPost('jam') ?? date('H:i:s'),
            'kd_bangsal' => $this->request->getPost('kd_bangsal'),
            'no_batch'   => $this->request->getPost('no_batch'),
            'no_faktur'  => $this->request->getPost('no_faktur')
        ];

        $url = $this->api_url . '/resep-pulang';
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
            log_message('error', 'Failed to insert resep_pulang: ' . $response);
            return $this->renderErrorView($status);
        }

        return redirect()->to(base_url('reseppulang'))
            ->with('success', 'Resep pulang berhasil disimpan.');
    }

// public function tambahResepPulangFromPermintaan($no_rawat, $kode_brng, $tanggal, $jam)
// {
//     if (!session()->has('jwt_token')) {
//         return $this->renderErrorView(401);
//     }

//     $token = session()->get('jwt_token');
//     $title = 'Edit Resep Pulang';

//     // 🔥 Step 1: Fetch resep pulang data untuk prefill
//     $url = $this->api_url . "/resep-pulang/$no_rawat/$kode_brng/$tanggal/$jam";
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, [
//         'Authorization: Bearer ' . $token,
//     ]);
//     $response = curl_exec($ch);
//     curl_close($ch);

//     $data = json_decode($response, true);
//     $prefill = $data['data'] ?? [];

//     // 🔥 Step 2: Fetch obat list
//     $obat_url = $this->api_url . '/pemberian-obat/databarang';
//     $ch = curl_init($obat_url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, [
//         'Authorization: Bearer ' . $token,
//         'Accept: application/json'
//     ]);
//     $obat_response = curl_exec($ch);
//     curl_close($ch);

//     $obat_data = json_decode($obat_response, true);
//     $obat_list = $obat_data['data'] ?? [];

//     // Breadcrumbs
//     $this->addBreadcrumb('User', 'user');
//     $this->addBreadcrumb('Resep Pulang', 'reseppulang');
//     $this->addBreadcrumb('Edit', 'edit');

//     return view('admin/reseppulang/edit_reseppulang', [
//         'no_rawat'   => $no_rawat,
//         'kode_brng'  => $kode_brng,
//         'tanggal'    => $tanggal,
//         'jam'        => $jam,
//         'prefill'    => $prefill,
//         'obat_list'  => $obat_list,
//         'title'      => $title,
//         'breadcrumbs'=> $this->getBreadcrumbs()
//     ]);
// }

public function editResepPulang($no_rawat, $kode_brng, $tanggal, $jam)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Edit Resep Pulang';

    // 🔥 Step 1: Fetch resep pulang data untuk prefill
    $url = $this->api_url . "/resep-pulang/$no_rawat/$kode_brng/$tanggal/$jam";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $prefill = $data['data'] ?? [];

    // 🔥 Step 2: Fetch obat list
    $obat_url = $this->api_url . '/pemberian-obat/databarang';
    $ch = curl_init($obat_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $obat_response = curl_exec($ch);
    curl_close($ch);

    $obat_data = json_decode($obat_response, true);
    $obat_list = $obat_data['data'] ?? [];

    // Breadcrumbs
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Resep Pulang', 'reseppulang');
    $this->addBreadcrumb('Edit', 'edit');

    return view('admin/reseppulang/edit_reseppulang', [
        'no_rawat'   => $no_rawat,
        'kode_brng'  => $kode_brng,
        'tanggal'    => $tanggal,
        'jam'        => $jam,
        'prefill'    => $prefill,
        'obat_list'  => $obat_list,
        'title'      => $title,
        'breadcrumbs'=> $this->getBreadcrumbs()
    ]);
}

    

    public function submitEditResepPulang($no_rawat, $kode_brng, $tanggal, $jam)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . "/resep-pulang/{$no_rawat}/{$kode_brng}/{$tanggal}/{$jam}";

        $postData = [
            'jml_barang' => (float) $this->request->getPost('jml_barang'),
            'harga'      => (float) $this->request->getPost('harga'),
            'total'      => (float) $this->request->getPost('total'),
            'dosis'      => $this->request->getPost('dosis'),
            'kd_bangsal' => $this->request->getPost('kd_bangsal'),
            'no_batch'   => $this->request->getPost('no_batch'),
            'no_faktur'  => $this->request->getPost('no_faktur')
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
            return redirect()->to(base_url('reseppulang'))->with('success', 'Resep pulang berhasil diupdate.');
        } else {
            return $this->renderErrorView($http_status);
        }
    }

    public function hapusResepPulang($no_rawat, $kode_brng, $tanggal, $jam)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $url = $this->api_url . "/resep-pulang/$no_rawat/$kode_brng/$tanggal/$jam";

        $ch = curl_init($url);
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

        return redirect()->to('/reseppulang')->with('success', 'Resep pulang berhasil dihapus.');
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
            return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
        }
    
        $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];
        $obat_list = $this->getObatListFromAPI($token);

    
        if (!is_array($rawatinap)) {
            return redirect()->back()->with('error', 'Format data rawat inap tidak valid.');
        }
    
        // Step 2: Prepare complete prefill for the tambah reseppulang form
        $preFill = [
            'nomor_rm'     => $rawatinap['nomor_rm'] ?? '',
            'no_rawat'     => $rawatinap['nomor_rawat'] ?? '',
            'kd_bangsal'   => $rawatinap['kamar'] ?? '', // kalau ada kd_bangsal
            'kode_obat'    => '',  // belum pilih obat
            'jumlah_obat'  => '',  // belum input jumlah
            'harga'        => '',  // belum input harga
            'total_harga'  => '',  // belum input total
            'dosis'        => '',  // belum input dosis
            'tanggal'      => date('Y-m-d'),
            'jam'          => date('H:i:s'),
            'no_batch'     => '',
            'no_faktur'    => ''
        ];
    
        // Step 3: Buka halaman tambah resep pulang dengan prefill data
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Pulang', 'reseppulang');
        $this->addBreadcrumb('Tambah', 'tambah');
    
        return view('admin/reseppulang/tambah_reseppulang', [
            'prefill' => $preFill,
            'title' => 'Tambah Resep Pulang',
            'obat_list' => $obat_list,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }
    
    private function getObatListFromAPI($token)
    {
        $url = $this->api_url . '/pemberian-obat/databarang'; // ✅ Perbaiki di sini
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($http_status != 200) {
            log_message('error', 'Gagal ambil data obat. Status: ' . $http_status);
            return [];
        }

        if (isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        } else {
            log_message('error', 'Format response databarang tidak sesuai');
            return [];
        }
    }
}