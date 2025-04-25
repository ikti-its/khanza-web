<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DokterJagaController extends BaseController
{
    public function dataDokterJaga()
    {
        $title = 'Data Dokter Jaga';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $dokterjaga_url = $this->api_url . '/dokterjaga';

            $ch = curl_init($dokterjaga_url);
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

            $dokterjaga_data = json_decode($response, true);

            foreach ($dokterjaga_data['data'] as &$item) {
                if (!empty($item['jam_mulai'])) {
                    $item['jam_mulai'] = date("H:i:s", strtotime($item['jam_mulai']));
                }
                if (!empty($item['jam_selesai'])) {
                    $item['jam_selesai'] = date("H:i:s", strtotime($item['jam_selesai']));
                }
            }

            if (!isset($dokterjaga_data['data'])) {
                return $this->renderErrorView(500);
            }

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Dokter Jaga', 'dokterjaga');
            $breadcrumbs = $this->getBreadcrumbs();

            $meta_data = $dokterjaga_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1];

            return view('/admin/dokterjaga/dokterjaga_data', [
                'dokterjaga_data' => $dokterjaga_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $meta_data
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahDokterJaga()
    {
        if (session()->has('jwt_token')) {
            $title = 'Tambah Dokter Jaga';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Dokter Jaga', 'dokterjaga');
            $this->addBreadcrumb('Tambah', 'tambah');

            $token = session()->get('jwt_token');
            $url = $this->api_url . '/dokter';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $dokterData = json_decode($response, true);

            $data['dokters'] = $dokterData['data'] ?? [];
            $data['dokterjaga'] = []; // or pass existing data if it's an edit

            return view('/admin/dokterjaga/tambah_dokterjaga', [
                'title' => $title,
                'breadcrumbs' => $this->getBreadcrumbs(),
                'dokter' => $data['dokters'],
                'dokterjaga' => $data['dokterjaga']
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahDokterJaga()
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');

        $postData = [
            'kode_dokter' => $this->request->getPost('kode_dokter'),
            'nama_dokter' => $this->request->getPost('nama_dokter'),
            'hari_kerja' => $this->request->getPost('hari_kerja'), // Format: YYYY-MM-DD
            'jam_mulai' => date("H:i:s", strtotime($this->request->getPost('jam_mulai'))),
            'jam_selesai' => date("H:i:s", strtotime($this->request->getPost('jam_selesai'))),
            'poliklinik' => $this->request->getPost('poliklinik'),
            'status' => $this->request->getPost('status')
        ];

        $url = $this->api_url . '/dokterjaga';
        $json = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json),
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($status === 201)
            ? redirect()->to(base_url('dokterjaga'))
            : $this->renderErrorView($status);
    }

    return $this->renderErrorView(401);
}

public function editDokterJaga($kodeDokter)
{
    if (!session()->has('jwt_token')) return $this->renderErrorView(401);

    $token = session()->get('jwt_token');

    // 👉 Fetch specific dokter jaga detail
    $url = $this->api_url . '/dokterjaga/' . $kodeDokter;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200) return $this->renderErrorView($status);

    $dokterJagaData = json_decode($response, true);

    // 👉 Fetch all dokter (for dropdown options)
    $dokterUrl = $this->api_url . '/dokter';
    $ch = curl_init($dokterUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $dokterResponse = curl_exec($ch);
    curl_close($ch);
    $dokterList = json_decode($dokterResponse, true);

    // 🧭 Breadcrumbs
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Dokter Jaga', 'dokterjaga');
    $this->addBreadcrumb('Edit', 'edit');

    return view('/admin/dokterjaga/edit_dokterjaga', [
        'dokterjaga' => $dokterJagaData['data'][0] ?? [],
        'dokter' => $dokterList['data'] ?? [],
        'title' => 'Edit Dokter Jaga',
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}


public function submitEditDokterJaga($kodeDokter)
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');

        $postData = [
            'kode_dokter' => $this->request->getPost('kode_dokter'),
            'nama_dokter' => $this->request->getPost('nama_dokter'),
            'hari_kerja' => $this->request->getPost('hari_kerja'),
            'jam_mulai' => date("H:i:s", strtotime($this->request->getPost('jam_mulai'))),
            'jam_selesai' => date("H:i:s", strtotime($this->request->getPost('jam_selesai'))),
            'poliklinik' => $this->request->getPost('poliklinik'),
            'status' => $this->request->getPost('status')
        ];

        $json = json_encode($postData);
        $url = $this->api_url . '/dokterjaga';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($status === 200)
            ? redirect()->to(base_url('dokterjaga'))->with('success', 'Data dokter jaga berhasil diperbarui.')
            : $this->renderErrorView($status);
    }

    return $this->renderErrorView(401);
}

public function hapusDokterJaga($kodeDokter)
{
    if (!session()->has('jwt_token')) return $this->renderErrorView(401);

    $hariKerja = $this->request->getGet('hari_kerja'); // passed as query param
    if (!$hariKerja) return $this->renderErrorView(400); // Bad request if missing

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/dokterjaga/' . $kodeDokter . '?hari_kerja=' . urlencode($hariKerja);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($status === 200 || $status === 204)
        ? redirect()->to(base_url('dokterjaga'))->with('success', 'Data dokter jaga berhasil dihapus.')
        : $this->renderErrorView($status);
}

public function panggilDokter($kodeDokter)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Detail Dokter Jaga';
    $url = $this->api_url . '/dokterjaga/' . $kodeDokter;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status !== 200) {
        return $this->renderErrorView($http_status);
    }

    $dokter_data = json_decode($response, true);

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Dokter Jaga', 'dokterjaga');
    $this->addBreadcrumb('Detail', 'detail');
    $breadcrumbs = $this->getBreadcrumbs();

    return view('/admin/dokterjaga/detail_dokterjaga', [
        'dokterjaga' => $dokter_data['data'],
        'title' => $title,
        'breadcrumbs' => $breadcrumbs,
    ]);
}

public function terimaDokter($kodeDokter)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $hariKerja = $this->request->getGet('hari_kerja'); // use query param to identify shift
    if (!$hariKerja) return $this->renderErrorView(400);

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/dokterjaga/update-status';

    $payload = json_encode([
        'kode_dokter' => $kodeDokter,
        'hari_kerja' => $hariKerja,
        'status' => 'diterima' // or "aktif", depending on your system
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
        'Content-Type: application/json',
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 200) {
        return redirect()->to(base_url('dokterjaga'))->with('success', 'Status dokter jaga berhasil diperbarui.');
    } else {
        return $this->renderErrorView($http_status);
    }
}

}

