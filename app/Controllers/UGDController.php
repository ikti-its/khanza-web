<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UGDController extends BaseController
{
    public function dataUGD()
    {
        $title = 'Data UGD';
    
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $ugd_url = $this->api_url . '/ugd';
    
        $ch = curl_init($ugd_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        // dd($response);

        if ($status !== 200 || !$response) {
            log_message('error', "UGD API error: status {$status}, response: " . $response);
            return $this->renderErrorView($status);
        }
    
        $ugd_data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($ugd_data['data'])) {
            log_message('error', 'JSON decode error: ' . json_last_error_msg());
            return $this->renderErrorView(500);
        }
    
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('UGD', 'ugd');
        $breadcrumbs = $this->getBreadcrumbs();
        $meta_data = $ugd_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1];
    
        return view('/admin/ugd/ugd_data', [
            'ugd_data' => $ugd_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $meta_data,
        ]);
    }
    

    public function tambahUGD()
    {
        if (session()->has('jwt_token')) {
            $title = 'Tambah UGD';
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('UGD', 'ugd');
            $this->addBreadcrumb('Tambah', 'tambah');

            return view('/admin/ugd/tambah_ugd', [
                'title' => $title,
                'breadcrumbs' => $this->getBreadcrumbs(),
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahUGD()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
$jamRaw = $this->request->getPost('jam');
$jamForUgd = date('H:i', strtotime($jamRaw));      // for /ugd
$jamForRegistrasi = date('H:i:s', strtotime($jamRaw)); // for /registrasi

    // Common input
    $postData = [
        'nomor_reg'        => $this->request->getPost('nomor_reg'),
        'nomor_rawat'      => $this->request->getPost('nomor_rawat'),
        'tanggal'          => $this->request->getPost('tanggal'),
        'jam'              => $jamForUgd,
        'kode_dokter'      => $this->request->getPost('kode_dokter'),
        'dokter_dituju'    => $this->request->getPost('dokter_dituju'),
        'nomor_rm'         => $this->request->getPost('nomor_rekam_medis'),
        'nama_pasien'      => $this->request->getPost('nama'),
        'jenis_kelamin'    => $this->request->getPost('jenis_kelamin'),
        'umur'             => $this->request->getPost('umur'),
        'poliklinik'       => $this->request->getPost('poliklinik'),
        'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
        'alamat_pj'        => $this->request->getPost('alamat_penanggung_jawab'),
        'hubungan_pj'      => $this->request->getPost('hubungan_penanggung_jawab'),
        'biaya_registrasi' => floatval($this->request->getPost('biaya_registrasi')),
        'jenis_bayar'      => $this->request->getPost('jenis_bayar'),
        'status_rawat'     => $this->request->getPost('status_rawat'),
        'status_bayar'     => $this->request->getPost('status_bayar'),
    ];
// dd($postData);
    // Submit to /ugd
    $ugdJson = json_encode($postData);
    $ch1 = curl_init($this->api_url . '/ugd');
    curl_setopt($ch1, CURLOPT_POST, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $ugdJson);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($ugdJson),
        'Authorization: Bearer ' . $token
    ]);
    $responseUgd = curl_exec($ch1);
    $statusUgd = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
    curl_close($ch1);

    log_message('error', 'UGD API response: ' . $responseUgd);

    // Prepare payload for /registrasi
    $postDataRegistrasi = [
        'nomor_reg' => $postData['nomor_reg'],
        'nomor_rawat' => $postData['nomor_rawat'],
        'tanggal' => $postData['tanggal'],
        'nomor_rm' => $postData['nomor_rm'],
        'jenis_kelamin' => $postData['jenis_kelamin'],
        'poliklinik' => $postData['poliklinik'],
        'nama_dokter' => $postData['dokter_dituju'],
        'nama_pasien' => $postData['nama_pasien'],
        'kode_dokter' => $postData['kode_dokter'],
        'umur' => strval($postData['umur']),
        'penanggung_jawab' => $postData['penanggung_jawab'],
        'alamat_pj' => $postData['alamat_pj'],
        'biaya_registrasi' => $postData['biaya_registrasi'],
        'status_rawat' => $postData['status_rawat'],
        'status_bayar' => $postData['status_bayar'],
        'jam' => $jamForRegistrasi,
        'hubungan_pj' => $postData['hubungan_pj'],
        'no_telepon' => $this->request->getPost('nomor_telepon'), // if exists
        'status_registrasi' => $this->request->getPost('status_registrasi'), // if exists
        'status_poli' => $this->request->getPost('status_poliklinik'), // if exists
        'jenis_bayar' => $postData['jenis_bayar'],
    ];

    $registrasiJson = json_encode($postDataRegistrasi);
    $ch2 = curl_init($this->api_url . '/registrasi');
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $registrasiJson);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($registrasiJson),
        'Authorization: Bearer ' . $token
    ]);
    $responseRegistrasi = curl_exec($ch2);
    $statusRegistrasi = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    log_message('error', 'Registrasi API response: ' . $responseRegistrasi);

    if ($statusUgd === 201 && $statusRegistrasi === 201) {
        return redirect()->to(base_url('ugd'))->with('success', 'Data berhasil ditambahkan ke UGD dan Registrasi.');
    }

    return $this->response->setStatusCode(500)->setJSON([
        'message' => 'Gagal menambahkan data',
        'response_ugd' => json_decode($responseUgd, true),
        'response_registrasi' => json_decode($responseRegistrasi, true)
    ]);
}



    public function editUGD($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit UGD';
        $url = $this->api_url . '/ugd/' . $nomorReg;

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

        $ugd_data = json_decode($response, true);

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('UGD', 'ugd');
        $this->addBreadcrumb('Edit', 'edit');

        return view('/admin/ugd/edit_ugd', [
            'ugd' => $ugd_data['data'],
            'title' => $title,
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function submitEditUGD($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/ugd/' . $nomorReg;

        $postData = [
            'nomor_reg' => $this->request->getPost('nomor_reg'),
            'nomor_rawat' => $this->request->getPost('nomor_rawat'),
            'tanggal' => $this->request->getPost('tanggal'),
            'jam' => $this->request->getPost('jam'),
            'kode_dokter' => $this->request->getPost('kode_dokter'),
            'dokter_dituju' => $this->request->getPost('dokter_dituju'),
            'nomor_rm' => $this->request->getPost('nomor_rekam_medis'),
            'nama_pasien' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'umur' => $this->request->getPost('umur'),
            'poliklinik' => $this->request->getPost('poliklinik'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'alamat_pj' => $this->request->getPost('alamat_penanggung_jawab'),
            'hubungan_pj' => $this->request->getPost('hubungan_penanggung_jawab'),
            'biaya_registrasi' => $this->request->getPost('biaya_registrasi'),
            'jenis_bayar' => $this->request->getPost('jenis_bayar'),
            'status_rawat' => $this->request->getPost('status_rawat'),
            'status_bayar' => $this->request->getPost('status_bayar'),
        ];

        $jsonData = json_encode($postData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
// dd($jsonData);
        if ($status === 200) {
            return redirect()->to(base_url('ugd'));
        }

        return $response;
    }

    public function hapusUGD($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/ugd/' . $nomorReg;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 204) {
            return redirect()->to(base_url('ugd'))->with('success', 'Data UGD berhasil dihapus.');
        } else {
            return $this->renderErrorView($status);
        }
    }
}
