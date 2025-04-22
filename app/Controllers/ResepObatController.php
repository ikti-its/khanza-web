<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

    public function tambahResepObat($noResep = null)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Tambah Resep Obat';
    $resep = [];

    // ✅ Optional: Load existing resep
    if ($noResep) {
        $url = $this->api_url . '/resep-obat/' . $noResep;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $parsed = json_decode($response, true);
        $resep = $parsed['data'] ?? [];
    }

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


    // ✅ Breadcrumbs
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
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $postData = [
                'no_resep'      => $this->request->getPost('no_resep'),
                'kode_barang'   => $this->request->getPost('kode_barang'),
                'jumlah'        => floatval($this->request->getPost('jumlah')),
                'aturan_pakai'  => $this->request->getPost('aturan_pakai'),
            ];

            $url = $this->api_url . '/resep-obat';
            $payload = json_encode($postData);

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
                return redirect()->to(base_url('ResepObat/' . $postData['no_resep']));
            } else {
                return $this->renderErrorView($status);
            }
        } else {
            return $this->renderErrorView(401);
        }
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

public function ResepObatData($noResep)
{
    $title = 'Detail Resep Obat';

    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');
        $url = $this->api_url . '/resep-obat/' . $noResep;

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

        $data = [$resep_data['data']]; // Wrap in array for consistency with view

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Resep Obat', 'resepobat');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/resepobat/resepobat_data', [
            'resepobat_data' => $data,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $resep_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);
    } else {
        return $this->renderErrorView(401);
    }
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

            // Step 2: Map to resep_obat
            $postData = [
                'no_resep'        => 'RSP' . date('Ymd') . rand(100, 999),
                'tgl_perawatan'   => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
                'jam'             => date('H:i:s'),
                'no_rawat'        => $rawatinap['nomor_rawat'],
                'kd_dokter'       => $rawatinap['kode_dokter'] ?? '',
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
                return redirect()->to('/resepobat/' . $postData['no_resep'])->with('success', 'Data resep obat berhasil disimpan.');
            } else {
                return redirect()->to('/resepobat')->with('error', 'Gagal menyimpan resep obat.');
            }
        } else {
            return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
        }
    }

    return redirect()->back()->with('error', 'Tidak ada token sesi.');
}

}