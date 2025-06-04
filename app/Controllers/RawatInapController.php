<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RawatInapController extends BaseController
{
    public function dataRawatInap()
{
    $title = 'Data Rawat Inap';

    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/rawatinap';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    log_message('error', 'Rawat Inap API Response: ' . $response);

    if ($http_status !== 200) {
        return $this->renderErrorView($http_status);
    }

    $data = json_decode($response, true);
    if (!isset($data['data'])) {
        return $this->renderErrorView(500);
    }

    $rawatinapList = $data['data'];

    // ✅ Calculate lama_ranap and total_biaya
    foreach ($rawatinapList as &$ri) {
        $tanggalMasuk = $ri['tanggal_masuk'] ?? null;
        $tarifKamar = floatval($ri['tarif_kamar'] ?? 0);

        if ($tanggalMasuk) {
            $tanggalKeluar = ($ri['tanggal_keluar'] === '0001-01-01' || empty($ri['tanggal_keluar']))
                ? date('Y-m-d') : $ri['tanggal_keluar'];

            try {
                $start = new \DateTime($tanggalMasuk);
                $end = new \DateTime($tanggalKeluar);
                $interval = $start->diff($end);
                $lamaRanap = max($interval->days, 1);
            } catch (\Exception $e) {
                $lamaRanap = 1;
            }

            $ri['lama_ranap'] = $lamaRanap;
            $ri['total_biaya'] = $lamaRanap * $tarifKamar;
        } else {
            $ri['lama_ranap'] = 0;
            $ri['total_biaya'] = 0;
        }
    }

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Rawat Inap', 'rawatinap');
    $breadcrumbs = $this->getBreadcrumbs();

    return view('/admin/rawatinap/rawatinap_data', [
        'rawatinap_data' => $rawatinapList,
        'title' => $title,
        'breadcrumbs' => $breadcrumbs,
        'meta_data' => $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1]
    ]);
}


    public function submitTambahRawatInap()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    $postData = [
        'nomor_rawat' => $this->request->getPost('nomor_rawat'),
        'nomor_rm' => $this->request->getPost('nomor_rm'),
        'nama_pasien' => $this->request->getPost('nama_pasien'),
        'alamat_pasien' => $this->request->getPost('alamat_pasien'),
        'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
        'hubungan_pj' => $this->request->getPost('hubungan_pj'),
        'dokter_pj' => $this->request->getPost('dokter_pj'),
        'jenis_bayar' => $this->request->getPost('jenis_bayar'),
        'diagnosa_awal' => $this->request->getPost('diagnosa_awal'),
        'kamar' => $this->request->getPost('kamar'),
        'tarif_kamar' => floatval(str_replace(',', '', $this->request->getPost('tarif_kamar'))),
        'status_kamar' => $this->request->getPost('status_kamar'),
        'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
        'jam_masuk' => $this->request->getPost('jam_masuk') ?: date('H:i:s'),
    ];
// dd($postData);
    $ch = curl_init($this->api_url . '/rawatinap');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $response = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // dd($response);

    if ($httpStatus === 200 || $httpStatus === 201) {
        return redirect()->to('/rawatinap')->with('success', 'Rawat Inap berhasil ditambahkan.');
    }

    return $this->renderErrorView($httpStatus);
}


    public function tambahRawatInap()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $title = 'Tambah Rawat Inap';

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rawat Inap', 'rawatinap');
        $this->addBreadcrumb('Tambah', 'tambah');

        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/rawatinap/tambah_rawatinap', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function tambahRawatInapBaru($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $registrasiUrl = $this->api_url . '/registrasi/' . $nomorReg;
    
        $ch = curl_init($registrasiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // dd($response);
    
        if ($httpStatus !== 200) {
            return $this->renderErrorView($httpStatus);
        }
    
        $registrasi = json_decode($response, true)['data'];
    
        $title = 'Tambah Rawat Inap';
        $breadcrumbs = [
            ['title' => 'Rawat Inap', 'url' => '/rawatinap'],
            ['title' => 'Tambah', 'url' => null]
        ];
    
        return view('admin/rawatinap/tambah_rawatinapbaru', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'registrasi' => $registrasi,
            'tanggal_masuk' => date('Y-m-d'),
            'jam_masuk' => date('H:i:s'),
        ]);
    }
    


    public function editRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            return $this->renderErrorView($status);
        }

        $data = json_decode($response, true);

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rawat Inap', 'rawatinap');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/rawatinap/edit_rawatinap', [
            'rawatinap' => $data['data'],
            'title' => 'Edit Rawat Inap',
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $postData = $this->getRawatInapPostData();
        $jsonData = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            return redirect()->to(base_url('rawatinap'));
        }

        return $response;
    }

    public function hapusRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 204) {
            return redirect()->to(base_url('rawatinap'))->with('success', 'Data rawat inap berhasil dihapus.');
        }

        return $this->renderErrorView($status);
    }

    private function getRawatInapPostData(): array
    {
        // Get raw input
    $jamMasukInput = $this->request->getPost('jam_masuk');
    $jamKeluarInput = $this->request->getPost('jam_keluar');

    // Fallback to current time if input is empty
    $jamMasuk = $jamMasukInput ? date('H:i:s', strtotime($jamMasukInput)) : date('H:i:s');
    $jamKeluar = $jamKeluarInput ? date('H:i:s', strtotime($jamKeluarInput)) : date('H:i:s');
    
        return [
            'nomor_rawat'       => $this->request->getPost('nomor_rawat'),
            'nomor_rm'          => $this->request->getPost('nomor_rm'),
            'nama_pasien'       => $this->request->getPost('nama_pasien'),
            'alamat_pasien'     => $this->request->getPost('alamat_pasien'),
            'penanggung_jawab'  => $this->request->getPost('penanggung_jawab'),
            'hubungan_pj'       => $this->request->getPost('hubungan_pj'),
            'jenis_bayar'       => $this->request->getPost('jenis_bayar'),
            'kamar'             => $this->request->getPost('kamar'),
            'tarif_kamar'       => floatval($this->request->getPost('tarif_kamar')),
            'diagnosa_awal'     => $this->request->getPost('diagnosa_awal'),
            'diagnosa_akhir'    => $this->request->getPost('diagnosa_akhir'),
            'tanggal_masuk'     => $this->request->getPost('tanggal_masuk'),
            'jam_masuk'         => $jamMasuk,
            'tanggal_keluar'    => $this->request->getPost('tanggal_keluar'),
            'jam_keluar'        => $jamKeluar,
            'total_biaya'       => floatval($this->request->getPost('total_biaya')),
            'status_pulang'     => $this->request->getPost('status_pulang'),
            'lama_ranap'        => floatval($this->request->getPost('lama_ranap')),
            'dokter_pj'         => $this->request->getPost('dokter_pj'),
            'status_bayar'      => $this->request->getPost('status_bayar'),
        ];
    }
    
}