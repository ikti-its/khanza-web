<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResepDokterModel;
use App\Models\RawatInapModel;

class ResepObatController extends BaseController
{
    public function dataResepObat()
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
            $this->addBreadcrumb('Resep Obat', 'ResepObat');
            $breadcrumbs = $this->getBreadcrumbs();

            // dd($resep_data);

            return view('/admin/ResepObat/resepobat_data', [
                'resepobat_data' => $resep_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahResepObat()
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
        $this->addBreadcrumb('Resep Obat', 'resepobat');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/resepobat/tambah_resepobat', [
            'resepobat' => $resep,
            'title' => $title,
            'obat_list' => $obat,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitTambahResepObat()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    // ✅ Submit to resep_obat (master)
    $postDataObat = [
        'no_resep'        => $this->request->getPost('no_resep'),
        'tgl_perawatan'   => $this->request->getPost('tgl_perawatan') ?? date('Y-m-d'),
        'jam'             => $this->request->getPost('jam') ?? date('H:i:s'),
        'no_rawat'        => $this->request->getPost('nomor_rawat'),
        'kd_dokter'       => $this->request->getPost('kode_dokter'),
        'tgl_peresepan'   => $this->request->getPost('tgl_peresepan') ?? date('Y-m-d'),
        'jam_peresepan'   => $this->request->getPost('jam_peresepan') ?? date('H:i:s'),
        'status'          => $this->request->getPost('status') ?? 'ranap',
        'tgl_penyerahan'  => $this->request->getPost('tgl_penyerahan') ?? date('Y-m-d'),
        'jam_penyerahan'  => $this->request->getPost('jam_penyerahan') ?? date('H:i:s'),
        'validasi'        => $this->request->getPost('validasi') === '1',
    ];

    $urlObat = $this->api_url . '/resep-obat';
    $payloadObat = json_encode($postDataObat);

    $ch1 = curl_init($urlObat);
    curl_setopt($ch1, CURLOPT_POST, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $payloadObat);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);
    $response1 = curl_exec($ch1);
    $status1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
    curl_close($ch1);

    if ($status1 !== 200 && $status1 !== 201) {
        log_message('error', 'Failed to insert resep_obat: ' . $response1);
        return $this->renderErrorView($status1);
    }

    // ✅ Submit to resep_dokter (details)
    $kodeBarangList = $this->request->getPost('kode_barang');

    if (!is_array($kodeBarangList)) {
        return $this->renderErrorView(400); // Ensure it's an array
    }

    foreach ($kodeBarangList as $kodeBarang) {
        $postDataDetail = [
            'no_resep'      => $this->request->getPost('no_resep'),
            'kode_barang'   => $kodeBarang,
            'jumlah'        => floatval($this->request->getPost('jumlah')[$kodeBarang]),
            'aturan_pakai'  => $this->request->getPost('aturan_pakai')[$kodeBarang],
            'embalase'      => floatval($this->request->getPost('embalase')[$kodeBarang] ?? 0),
            'tuslah'        => floatval($this->request->getPost('tuslah')[$kodeBarang] ?? 0),
        ];

        $urlDetail = $this->api_url . '/resep-dokter';
        $payloadDetail = json_encode($postDataDetail);

        $ch2 = curl_init($urlDetail);
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $payloadDetail);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);
        $response2 = curl_exec($ch2);
        $status2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);

        if ($status2 !== 200 && $status2 !== 201) {
            log_message('error', 'Failed to insert resep_dokter: ' . $response2);
            return $this->renderErrorView($status2);
        }
    }

    return redirect()->to(base_url('resepdokter/' . $this->request->getPost('no_resep')))
        ->with('success', 'Resep dokter berhasil disimpan.');
}

    

    public function editResepObat($noResep)
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
    $this->addBreadcrumb('Resep Obat', 'resepobat');
    $this->addBreadcrumb('Edit', 'edit');

    return view('/admin/resepobat/edit_resepobat', [
        'resepobat' => $resep,
        'title' => $title,
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}

public function submitEditResepObat($noResep)
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
        return redirect()->to(base_url('resepobat'))->with('success', 'Resep obat updated');
    } else {
        return $this->renderErrorView($http_status);
    }
}

public function hapusResepObat($noResep)
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

    return redirect()->to('/resepobat')->with('success', 'Resep obat deleted');
}

public function ResepObatData($nomorRawat)
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

        log_message('error', 'ResepObatData Response: ' . $response);
        log_message('error', 'ResepObatData HTTP Status: ' . $http_status);

        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }

        $resep_data = json_decode($response, true);

        if (!isset($resep_data['data'])) {
            log_message('error', 'ResepObatData: data key not found');
            return $this->renderErrorView(500);
        }

        $data = $resep_data['data']; // already an array

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Obat', 'resepobat');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/resepobat/resepobat_data', [
            'resepobat_data' => $data,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);

    } catch (\Throwable $e) {
        log_message('critical', 'ResepObatData Exception: ' . $e->getMessage());
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
    if (session()->has('jwt_token')) {
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
            $rawatinap = $data['data'];
            // dd($rawatinap);
            // Step 2: Map to resep_obat
            $postData = [
                'no_resep'        => 'RSP' . date('Ymd') . rand(100, 999),
                'tgl_perawatan'   => date('Y-m-d'),
                'jam'             => date('H:i:s'),
                'no_rawat'        => $rawatinap['nomor_rawat'],
                'kd_dokter'       => $rawatinap['dokter_pj'] ?? '',
                'tgl_peresepan'   => date('Y-m-d'),
                'jam_peresepan'   => date('H:i:s'),
                'status'          => 'ranap',
                'tgl_penyerahan'  => date('Y-m-d'),
                'jam_penyerahan'  => date('H:i:s'),
            ];

            // Step 3: Submit to Go API /resep-obat
            $url_resep = $this->api_url . '/resep-obat';
            $ch2 = curl_init($url_resep);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
            $result = curl_exec($ch2);
            $status = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
            curl_close($ch2);

            if ($status === 201 || $status === 200) {
                return view('admin/resepobat/tambah_resepobat', [
                    'title' => 'Tambah Resep Dokter',
                    'resepobat' => [
                        'nomor_rm' => $rawatinap['nomor_rm'] ?? '',
                        'nomor_rawat' => $rawatinap['nomor_rawat'] ?? '',
                        'kd_dokter' => $rawatinap['kode_dokter'] ?? '',
                    ],
                    'obat_list' => $this->getObatListFromAPI($token),
                ]);
            } else {
                return redirect()->to('/resepobat')->with('error', 'Gagal menyimpan resep obat.');
            }
        } else {
            return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
        }
    }

    return redirect()->back()->with('error', 'Tidak ada token sesi.');
}

public function submitTambahResepObatDetail()
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
        return redirect()->to(base_url('resepobat/' . $noResep))->with('success', 'Detail resep berhasil ditambahkan.');
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

    return view('admin/resepobat/cetak_aturan_pakai', [
        'resep_dokter' => $resepDokter,
    ]);
}







}