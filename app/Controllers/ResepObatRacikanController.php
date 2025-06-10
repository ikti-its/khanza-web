<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResepDokterModel;
use App\Models\RawatInapModel;

class ResepObatRacikanController extends BaseController
{
public function dataResepObatRacikan()
{
    $title = 'Data Resep Obat Racikan';

    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    // ✅ Fetch racikan
    $racikanUrl = $this->api_url . '/resep-dokter-racikan';
    $racikanResponse = $this->curlGet($racikanUrl, $token);
    $racikanData = json_decode($racikanResponse, true);
    $racikanList = $racikanData['data'] ?? [];

    // ✅ Fetch racikan detail
    $detailUrl = $this->api_url . '/resep-dokter-racikan-detail';
    $detailResponse = $this->curlGet($detailUrl, $token);
    $detailData = json_decode($detailResponse, true);
    $detailList = $detailData['data'] ?? [];

    // ✅ Fetch obat (databarang)
    $obatUrl = $this->api_url . '/pemberian-obat/databarang';
    $obatResponse = $this->curlGet($obatUrl, $token);
    $obatData = json_decode($obatResponse, true);
    $obatList = $obatData['data'] ?? [];
// dd($obatList);
    // 🔗 Build map for fast lookup
    $racikanMap = [];
    foreach ($racikanList as $racikan) {
        $racikanMap[$racikan['no_resep']] = $racikan;
    }

    $obatMap = [];
    foreach ($obatList as $obat) {
        $kode = $obat['kode_obat'] ?? null;
        if ($kode !== null) {
            $obatMap[$kode] = [
                'nama_barang' => $obat['nama_brng'] ?? '-',
                'kode_sat'    => $obat['kode_sat'] ?? '-',
                'kelas1'    => $obat['kelas1'] ?? '-',
                'kdjns'    => $obat['kdjns'] ?? '-',
                'kapasitas'    => $obat['kapasitas'] ?? '-',
                'stokminimal'    => $obat['stokminimal'] ?? '-',
            ];
        }
    }


    // 🔗 Merge all into combined list
    $combinedList = [];
    foreach ($detailList as $detail) {
        $no_resep = $detail['no_resep'];
        $kode_brng = $detail['kode_brng'];

        if (!isset($racikanMap[$no_resep])) {
            continue; // skip if no matching racikan
        }

        $racikan = $racikanMap[$no_resep];
        $combinedList[] = [
            'no_resep'     => $detail['no_resep'],
            'no_racik'     => $racikanMap[$no_resep]['no_racik'] ?? '',
            'nama_racik'   => $racikanMap[$no_resep]['nama_racik'] ?? '',
            'kd_racik'     => $racikanMap[$no_resep]['kd_racik'] ?? '',
            'jml_dr'       => $racikanMap[$no_resep]['jml_dr'] ?? '',
            'aturan_pakai' => $racikanMap[$no_resep]['aturan_pakai'] ?? '',
            'keterangan'   => $racikanMap[$no_resep]['keterangan'] ?? '',
            'kode_brng'    => $kode_brng,
            'nama_barang'  => $obatMap[$kode_brng]['nama_barang'] ?? '-',
            'p1'           => $detail['p1'] ?? '',
            'p2'           => $detail['p2'] ?? '',
            'kandungan'    => $detail['kandungan'] ?? '',
            'jml'          => $detail['jml'] ?? '',
            'kode_sat'     => $obatMap[$kode_brng]['kode_sat'] ?? '-', // ✅ fix here
            'kelas1'     => $obatMap[$kode_brng]['kelas1'] ?? '-', // ✅ fix here
            'kdjns'     => $obatMap[$kode_brng]['kdjns'] ?? '-', // ✅ fix here
            'kapasitas'     => $obatMap[$kode_brng]['kapasitas'] ?? '-', // ✅ fix here
            'stokminimal'     => $obatMap[$kode_brng]['stokminimal'] ?? '-', // ✅ fix here
        ];

    }

    // ✅ Breadcrumbs
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Resep Obat', 'resepobatracikan');
    $breadcrumbs = $this->getBreadcrumbs();
// dd($combinedList);
    return view('/admin/resepobatracikan/resepobatracikan_data', [
        'resepobatracikan_data' => $combinedList,
        'title' => $title,
        'breadcrumbs' => $breadcrumbs,
        'meta_data' => [
            'page' => 1,
            'size' => 10,
            'total' => count($combinedList)
        ],
    ]);
}

// Optional helper to avoid repeating curl code
private function curlGet(string $url, string $token): string
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

    public function tambahResepObatRacikan()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Tambah Resep Obat';
        $resep = [];

        // dd($resep);

        $nomor_rawat = $this->request->getGet('nomor_rawat'); // 👈 read from query string

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
                // dd($rawatinap);
                $resep = [
                    'nomor_rm'     => $rawatinap['nomor_rm'] ?? '',
                    'nomor_rawat'  => $rawatinap['nomor_rawat'] ?? '',
                    'nama_pasien'  => $rawatinap['nama_pasien'] ?? '',
                    'nama_dokter'  => $rawatinap['nama_dokter'] ?? '',
                    'kode_dokter'  => $rawatinap['kode_dokter'] ?? '',
                    'no_resep'     => 'RSP' . date('Ymd') . rand(1000, 9999),
                    'tgl_peresepan'=> date('Y-m-d'),
                    'jam_peresepan'=> date('H:i:s')
                ];
            }
        }

        // get obat data
        $obat = [];
        $ch = curl_init($this->api_url . '/pemberian-obat/databarang');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (isset($data['data'])) {
            $obat = $data['data'];
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Obat', 'resepobatracikan');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/resepobatracikan/tambah_resepobatracikan', [
            'resepobatracikan' => $resep,
            'title' => $title,
            'obat_list' => $obat,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitTambahResepObatRacikan()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    // === Submit to resep_dokter_racikan (master) ===
    $postDataRacikan = [
        'no_resep'     => $this->request->getPost('no_resep'),
        'no_racik'     => $this->request->getPost('no_racik'),
        'nama_racik'   => $this->request->getPost('nama_racik'),
        'kd_racik'     => $this->request->getPost('kd_racik'),
        'jml_dr'        => intval($this->request->getPost('jml_dr') ?? 0),
        'aturan_pakai' => $this->request->getPost('aturan_pakai'),
        'keterangan'   => $this->request->getPost('keterangan'),
    ];

    $urlMaster = $this->api_url . '/resep-dokter-racikan';
    $payloadMaster = json_encode($postDataRacikan);

    $chMaster = curl_init($urlMaster);
    curl_setopt($chMaster, CURLOPT_POST, true);
    curl_setopt($chMaster, CURLOPT_POSTFIELDS, $payloadMaster);
    curl_setopt($chMaster, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chMaster, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);
    $responseMaster = curl_exec($chMaster);
    $statusMaster = curl_getinfo($chMaster, CURLINFO_HTTP_CODE);
    curl_close($chMaster);

    if ($statusMaster !== 200 && $statusMaster !== 201) {
        log_message('error', '❌ Failed to insert into resep_dokter_racikan: ' . $responseMaster);
        return $this->renderErrorView($statusMaster);
    }

    // === Submit to resep_dokter_racikan_detail (detail) ===
    $no_resep = $this->request->getPost('no_resep');
    $no_racik = $this->request->getPost('no_racik');
    $kode_barang = $this->request->getPost('kode_barang'); // array

    $p1         = $this->request->getPost('p1');         // associative [kode_barang] => value
    $p2         = $this->request->getPost('p2');
    $kandungan  = $this->request->getPost('kandungan');
    $jml        = $this->request->getPost('jml');

    if (!is_array($kode_barang)) {
        return $this->renderErrorView(400); // Must be an array
    }

    foreach ($kode_barang as $kode) {
        $postDetail = [
            'no_resep'  => $no_resep,
            'no_racik'  => $no_racik,
            'kode_brng' => $kode,
            'p1'        => floatval($p1[$kode] ?? 0),
            'p2'        => floatval($p2[$kode] ?? 0),
            'kandungan' => $kandungan[$kode] ?? '',
            'jml'       => floatval($jml[$kode] ?? 0),
        ];

        $urlDetail = $this->api_url . '/resep-dokter-racikan-detail';
        $payloadDetail = json_encode($postDetail);

        $chDetail = curl_init($urlDetail);
        curl_setopt($chDetail, CURLOPT_POST, true);
        curl_setopt($chDetail, CURLOPT_POSTFIELDS, $payloadDetail);
        curl_setopt($chDetail, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chDetail, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        $responseDetail = curl_exec($chDetail);
        $statusDetail = curl_getinfo($chDetail, CURLINFO_HTTP_CODE);
        curl_close($chDetail);

        if ($statusDetail !== 200 && $statusDetail !== 201) {
            log_message('error', '❌ Failed to insert into resep_dokter_racikan_detail: ' . $responseDetail);
            return $this->renderErrorView($statusDetail);
        }

                // === ALSO submit to resep_dokter for each racikan component ===
        $postResepDokter = [
            'no_resep'     => $no_resep,
            'kode_barang'  => $kode,
            'jumlah'       => floatval($jml[$kode] ?? 0),
            'aturan_pakai' => $this->request->getPost('aturan_pakai'), // same as racikan
            'embalase'     => 0,
            'tuslah'       => 0,
        ];

        $urlResepDokter = $this->api_url . '/resep-dokter';
        $payloadResepDokter = json_encode($postResepDokter);

        $chResep = curl_init($urlResepDokter);
        curl_setopt($chResep, CURLOPT_POST, true);
        curl_setopt($chResep, CURLOPT_POSTFIELDS, $payloadResepDokter);
        curl_setopt($chResep, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chResep, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        $responseResep = curl_exec($chResep);
        $statusResep = curl_getinfo($chResep, CURLINFO_HTTP_CODE);
        curl_close($chResep);

        if ($statusResep !== 200 && $statusResep !== 201) {
            log_message('error', '❌ Failed to insert into resep_dokter: ' . $responseResep);
            return $this->renderErrorView($statusResep);
        }

    }

    $tanggal = date('Y-m-d');
$jamNow = date('H:i:s');

$postDataResepObat = [
    'no_resep'        => $no_resep,
    'tgl_perawatan'   => $tanggal,
    'jam'             => $jamNow,
    'no_rawat'        => $this->request->getPost('nomor_rawat') ?? '',
    'kd_dokter'       => $this->request->getPost('kode_dokter') ?? '',
    'tgl_peresepan'   => $tanggal,
    'jam_peresepan'   => $jamNow,
    'status'          => 'ranap', // or 'ralan', adjust if needed
    'tgl_penyerahan'  => $tanggal,
    'jam_penyerahan'  => $jamNow,
    'validasi'        => false
];

$urlResepObat = $this->api_url . '/resep-obat';
$payloadResepObat = json_encode($postDataResepObat);

$chResepObat = curl_init($urlResepObat);
curl_setopt($chResepObat, CURLOPT_POST, true);
curl_setopt($chResepObat, CURLOPT_POSTFIELDS, $payloadResepObat);
curl_setopt($chResepObat, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chResepObat, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
]);
$responseResepObat = curl_exec($chResepObat);
$statusResepObat = curl_getinfo($chResepObat, CURLINFO_HTTP_CODE);
curl_close($chResepObat);

if ($statusResepObat !== 200 && $statusResepObat !== 201) {
    log_message('error', '❌ Failed to insert into resep_obat: ' . $responseResepObat);
    return $this->renderErrorView($statusResepObat);
}


    return redirect()->to(base_url('resepdokter/' . $no_resep))
        ->with('success', '✅ Resep racikan berhasil disimpan.');
}


    

    public function editResepObatRacikan($noResep)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Edit Resep Obat';

    $url = $this->api_url . '/resep-obat/' . $noResep;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $resep = $data['data'] ?? [];

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Resep Obat', 'resepobatracikan');
    $this->addBreadcrumb('Edit', 'edit');

    return view('/admin/resepobatracikan/edit_resepobatracikan', [
        'resepobatracikan' => $resep,
        'title' => $title,
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}

public function submitEditResepObatRacikan($noResep)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/resep-obat/' . $noResep;

    $postData = [
        'no_resep'        => $noResep,
        'tgl_perawatan'   => $this->request->getPost('tgl_perawatan'),
        'jam'             => $this->request->getPost('jam'),
        'no_rawat'        => $this->request->getPost('no_rawat'),
        'kd_dokter'       => $this->request->getPost('kd_dokter'),
        'tgl_peresepan'   => $this->request->getPost('tgl_peresepan'),
        'jam_peresepan'   => $this->request->getPost('jam_peresepan'),
        'status'          => $this->request->getPost('status'),
        'tgl_penyerahan'  => $this->request->getPost('tgl_penyerahan'),
        'jam_penyerahan'  => $this->request->getPost('jam_penyerahan'),
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
        return redirect()->to(base_url('resepobatracikan'))->with('success', 'Resep obat updated');
    } else {
        return $this->renderErrorView($http_status);
    }
}

public function hapusResepObatRacikan($noResep)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $delete_url = $this->api_url . "/resep-obat/$noResep";

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

    return redirect()->to('/resepobatracikan')->with('success', 'Resep obat deleted');
}

public function ResepObatRacikanData($nomorRawat)
{
    $title = 'Detail Resep Obat';

    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/resep-obat/by-nomor-rawat/' . $nomorRawat;

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

        log_message('error', 'ResepObatRacikanData Response: ' . $response);
        log_message('error', 'ResepObatRacikanData HTTP Status: ' . $http_status);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $resep_data = json_decode($response, true);

        if (!isset($resep_data['data'])) {
            log_message('error', 'ResepObatRacikanData: data key not found');
            return $this->renderErrorView(500);
        }

        $data = $resep_data['data']; // already an array

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Obat', 'resepobatracikan');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/resepobatracikan/resepobatracikan_data', [
            'resepobatracikan_data' => $data,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);

    } catch (\Throwable $e) {
        log_message('critical', 'ResepObatRacikanData Exception: ' . $e->getMessage());
        return $this->renderErrorView(500);
    }
}

private function getObatListFromAPI($token)
{
    $url = $this->api_url . '/pemberian-obat/databarang';
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

        // Step 2: Prepare prefill data for the form (no API submission here)
        $formData = [
            'no_resep'       => 'RSP' . date('Ymd') . rand(100, 999),
            'tgl_perawatan'  => date('Y-m-d'),
            'jam'            => date('H:i:s'),
            'no_rawat'       => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
            'kd_dokter'      => $rawatinap['dokter_pj'] ?? $rawatinap['kode_dokter'] ?? '',
            'tgl_peresepan'  => date('Y-m-d'),
            'jam_peresepan'  => date('H:i:s'),
            'status'         => 'ranap',
            'tgl_penyerahan' => date('Y-m-d'),
            'jam_penyerahan' => date('H:i:s'),
            'nomor_rm'       => $rawatinap['nomor_rm'] ?? '',
        ];

        return view('admin/resepobatracikan/tambah_resepobatracikan', [
            'title'      => 'Tambah Resep Dokter',
            'resepobatracikan'  => $formData,
            'obat_list'  => $this->getObatListFromAPI($token),
        ]);
    }

    return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
}


public function submitTambahResepObatRacikanDetail()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $noResep = $this->request->getPost('no_resep');
    $kodeBarangList = $this->request->getPost('kode_barang');
    $jumlahList = $this->request->getPost('jumlah');
    $aturanPakaiList = $this->request->getPost('aturan_pakai');
    $embalaseList = $this->request->getPost('embalase');
    $tuslahList = $this->request->getPost('tuslah');

    $details = [];

    foreach ($kodeBarangList as $index => $kode) {
        $details[] = [
            'no_resep'     => $noResep,
            'kode_barang'  => $kode,
            'jumlah'       => floatval($jumlahList[$kode] ?? 0),
            'aturan_pakai' => $aturanPakaiList[$kode] ?? '',
            'embalase'     => floatval($embalaseList[$kode] ?? 0),
            'tuslah'       => floatval($tuslahList[$kode] ?? 0),
        ];
    }

    $url = $this->api_url . '/resep-obat-detail'; // adjust if different
    $payload = json_encode($details);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
        'Authorization: Bearer ' . $token,
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 201 || $status === 200) {
        return redirect()->to(base_url('resepobatracikan/' . $noResep))->with('success', 'Detail resep berhasil ditambahkan.');
    } else {
        return $this->renderErrorView($status);
    }
}

public function cetak($no_resep)
{
    $db = \Config\Database::connect();

    $builder = $db->table('sik.resep_dokter rd');
    $builder->select('
        rd.*,
        db.nama_brng AS nama_obat,
        ro.tgl_perawatan, ro.jam, ro.no_rawat,
        ri.nomor_rm, ri.nama_pasien, ri.alamat_pasien, ri.penanggung_jawab
    ');
    $builder->join('sik.databarang db', 'rd.kode_barang = db.kode_brng', 'left');
    $builder->join('sik.resep_obat ro', 'rd.no_resep = ro.no_resep', 'left');
    $builder->join('sik.rawat_inap ri', 'ro.no_rawat = ri.nomor_rawat', 'left');
    $builder->where('rd.no_resep', $no_resep);

    $resepDokter = $builder->get()->getResultArray();

    if (empty($resepDokter)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data resep tidak ditemukan.");
    }

    return view('admin/resepobatracikan/cetak_aturan_pakai', [
        'resep_dokter' => $resepDokter,
    ]);
}







}