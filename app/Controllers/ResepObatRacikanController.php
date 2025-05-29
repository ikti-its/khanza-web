<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResepDokterModel;
use App\Models\RawatInapModel;

class ResepObatRacikanController extends BaseController
{
    public function dataResepObatRacikan()
    {
        $title = 'Data Resep Dokter';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // ✅ Fetch resep dokter data
            $url = $this->api_url . '/resep-obat';
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
            if (!isset($resep_data['data'])) {
                return $this->renderErrorView(500);
            }

            // ✅ Breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Resep Obat', 'resepobatracikan');
            $breadcrumbs = $this->getBreadcrumbs();

            // dd($resep_data);

            return view('/admin/resepobatracikan/resepobatracikan_data', [
                'resepobatracikan_data' => $resep_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
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