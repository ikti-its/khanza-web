<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PemberianObatController extends BaseController
{


    public function dataPemberianObat()
    {
        $title = 'Data Pemberian Obat';
    
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
    
            // ✅ Fetch pemberian_obat data
            $obat_url = $this->api_url . '/pemberian-obat';
            $ch = curl_init($obat_url);
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
    
            $obat_data = json_decode($response, true);
            if (!isset($obat_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // ✅ Breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
            $breadcrumbs = $this->getBreadcrumbs();

            // dd($obat_data);
    
            return view('/admin/pemberianobat/pemberianobat_data', [
                'pemberianobat_data' => $obat_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $obat_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function tambahPemberianObat($nomorRawat)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Tambah Pemberian Obat';

    // ✅ Fetch pemberian obat data (e.g. from rawatinap or existing entry)
    $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $parsed = json_decode($response, true);
    $pemberianobat = $parsed['data'][0] ?? []; // safely pick first if list

    // ✅ Fetch available obat from databarang
    $url = $this->api_url . '/pemberian-obat/databarang';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    $obat_data = $data['data'] ?? [];

    // dd($obat_data);

    // ✅ Breadcrumbs
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
    $this->addBreadcrumb('Tambah', 'tambah');

    return view('/admin/pemberianObat/tambah_pemberianobat', [
        'pemberianobat' => $pemberianobat,
        'obat_data' => $obat_data,
        'title' => $title,
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}


public function submitTambahPemberianObat()
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');

        $postData = [
            'tanggal_beri'   => $this->request->getPost('tanggal_beri') ?? date('Y-m-d'),
            'jam_beri'       => $this->request->getPost('jam_beri') ?? date('H:i:s'),
            'nomor_rawat'    => $this->request->getPost('nomor_rawat'),
            'nama_pasien'    => $this->request->getPost('nama_pasien'),
            'kode_obat'      => $this->request->getPost('kode_obat'),
            'nama_obat'      => $this->request->getPost('nama_obat'),
            'embalase'       => $this->request->getPost('embalase'),
            'tuslah'         => $this->request->getPost('tuslah'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'biaya_obat'     => floatval($this->request->getPost('biaya_obat')),
            'total'          => floatval($this->request->getPost('total')),
            'gudang'         => $this->request->getPost('gudang'),
            'no_batch'       => $this->request->getPost('no_batch'),
            'no_faktur'      => $this->request->getPost('no_faktur'),
        ];

        $url = $this->api_url . '/pemberian-obat';
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
            return redirect()->to(base_url('pemberianobat/' . $postData['nomor_rawat']));
        } else {
            return $this->renderErrorView($status);
        }
    } else {
        return $this->renderErrorView(401);
    }
}

public function editPemberianObat($nomorRawat, $jamBeri)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Edit Pemberian Obat';

    $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $selectedObat = [];

    // ✅ Find the pemberian obat with matching jam_beri
    if (isset($data['data']) && is_array($data['data'])) {
        foreach ($data['data'] as $item) {
            if ($item['jam_beri'] === $jamBeri) {
                $selectedObat = $item;
                break;
            }
        }
    }

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
    $this->addBreadcrumb('Edit', 'edit');

    return view('/admin/pemberianObat/edit_pemberianobat', [
        'pemberianobat' => $selectedObat,
        'title' => $title,
        'breadcrumbs' => $this->getBreadcrumbs()
    ]);
}

public function submitEditPemberianObat($nomorRawat)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;

    $postData = [
        'tanggal_beri'   => $this->request->getPost('tanggal_beri'),
        'jam_beri'       => $this->request->getPost('jam_beri'),
        'nomor_rawat'    => $this->request->getPost('nomor_rawat'),
        'nama_pasien'    => $this->request->getPost('nama_pasien'),
        'kode_obat'      => $this->request->getPost('kode_obat'),
        'nama_obat'      => $this->request->getPost('nama_obat'),
        'embalase'       => $this->request->getPost('embalase'),
        'tuslah'         => $this->request->getPost('tuslah'),
        'jumlah'         => $this->request->getPost('jumlah'),
        'biaya_obat'     => floatval($this->request->getPost('biaya_obat')),
        'total'          => floatval($this->request->getPost('total')),
        'gudang'         => $this->request->getPost('gudang'),
        'no_batch'       => $this->request->getPost('no_batch'),
        'no_faktur'      => $this->request->getPost('no_faktur'),
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
        return redirect()->to(base_url('pemberianobat/' . $nomorRawat));
    } else {
        return $response;
    }
}

public function hapusPemberianObat($nomor_rawat, $jam_beri)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    $delete_url = $this->api_url . "/pemberian-obat/$nomor_rawat/$jam_beri";

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

    return redirect()->to('/pemberianobat')->with('success', 'Pemberian Obat deleted');
}

public function pemberianObatData($nomorRawat)
{
    $title = 'Detail Pemberian Obat';

    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');
        $url = $this->api_url . '/pemberian-obat/' . $nomorRawat;

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

        $obat_data = json_decode($response, true);

        if (!isset($obat_data['data'])) {
            return $this->renderErrorView(500);
        }

        $data = $obat_data['data'];
        if (isset($data['nomor_rawat'])) {
            $data = [$data];
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemberian Obat', 'pemberianobat');
        $breadcrumbs = $this->getBreadcrumbs();

        // dd($response);

        return view('/admin/pemberianObat/pemberianobat_data', [
            'pemberianobat_data' => $data,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $obat_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1],
        ]);
    } else {
        return $this->renderErrorView(401);
    }
}

public function submitFromRawatinap($nomor_rawat)
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');

        // Step 1: Get rawat inap data by nomor_rawat
        $url_rawatinap = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch = curl_init($url_rawatinap);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        // dd($response);

        $data = json_decode($response, true);

        if ($data && isset($data['data'])) {
            $rawatinap = $data['data'];

            // Step 2: Map fields to pemberian_obat
            $postData = [
                'tanggal_beri'   => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
                'jam_beri'       => $rawatinap['jam_masuk'] ?? date('H:i:s'),
                'nomor_rawat'    => $rawatinap['nomor_rawat'],
                'nama_pasien'    => $rawatinap['nama_pasien'],
                'kelas'          => $rawatinap['kelas'] ?? 'dasar',
                'kode_obat'      => '', // <-- change this as needed
                'nama_obat'      => '', // <-- or fetch dynamically
                'embalase'       => '',
                'tuslah'         => '',
                'jumlah'         => '',
                'biaya_obat'     => 0,
                'total'          => 0,
                'gudang'         => '',
                'no_batch'       => '',
                'no_faktur'      => ''
            ];

            // Step 3: Submit to Go API /pemberian-obat
            $url_obat = $this->api_url . '/pemberian-obat';
            $ch2 = curl_init($url_obat);
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
                return redirect()->to('/pemberianobat/' . $nomor_rawat)->with('success', 'Data pemberian obat berhasil disimpan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menyimpan data pemberian obat.');
            }
        } else {
            return redirect()->back()->with('error', 'Data rawat inap tidak ditemukan.');
        }
    }

    return redirect()->back()->with('error', 'Tidak ada token sesi.');
}


}    