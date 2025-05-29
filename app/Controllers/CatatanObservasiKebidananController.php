<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CatatanObservasiKebidananController extends BaseController
{
    public function dataCatatanObservasi()
    {
        $title = 'Catatan Observasi Ranap Kebidanan';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/catatan-observasi-ranap-kebidanan';

        $ch = curl_init($url);
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

        $data = json_decode($response, true);
        if (!isset($data['data']) || !is_array($data['data'])) {
            return $this->renderErrorView(500);
        }

        $list = $data['data'];

        // Tambahkan nama pasien & petugas (opsional)
        foreach ($list as &$item) {
            if (isset($item['no_rawat'])) {
                $no_rawat = $item['no_rawat'];
                $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $no_rawat;

                $ch_reg = curl_init($registrasi_url);
                curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_reg = curl_exec($ch_reg);
                curl_close($ch_reg);

                $reg_data = json_decode($response_reg, true);
                $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
                $item['umur'] = $reg_data['data']['umur'] ?? '';
                $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
            }

            if (!empty($item['nip'])) {
                $nip = urlencode($item['nip']);
                $pegawai_url = $this->api_url . '/pegawai/nip/' . $nip;

                $ch_nama = curl_init($pegawai_url);
                curl_setopt($ch_nama, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_nama, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ]);
                $response_nama = curl_exec($ch_nama);
                curl_close($ch_nama);

                $pegawai_data = json_decode($response_nama, true);
                if (
                    isset($pegawai_data['status']) &&
                    $pegawai_data['status'] === 'success' &&
                    isset($pegawai_data['data']['Nama'])
                ) {
                    $item['nama_petugas'] = $pegawai_data['data']['Nama'];
                } else {
                    $item['nama_petugas'] = '—';
                }
            } else {
                $item['nama_petugas'] = '—';
            }
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Observasi Kebidanan', 'catatanobservasikebidanan');
        $breadcrumbs = $this->getBreadcrumbs();

        $meta_data = $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => count($list)];

        return view('/admin/observasikebidanan/catatanobservasikebidanan_data', [
            'catatan_data' => $list,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $meta_data
        ]);
    }

    public function tambahCatatanObservasi()
    {
        if (session()->has('jwt_token')) {
            $title = 'Tambah Catatan Observasi Kebidanan';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Observasi Kebidanan', 'catatanobservasikebidanan');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/observasikebidanan/tambah_catatanobservasikebidanan', [
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahCatatanObservasi()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $postData = [
                'no_rawat'     => $this->request->getPost('no_rawat'),
                'tanggal'      => $this->request->getPost('tanggal'),
                'jam'          => $this->request->getPost('jam'),
                'gcs'          => $this->request->getPost('gcs'),
                'td'           => $this->request->getPost('td'),
                'hr'           => $this->request->getPost('hr'),
                'rr'           => $this->request->getPost('rr'),
                'suhu'         => $this->request->getPost('suhu'),
                'spo2'         => $this->request->getPost('spo2'),
                'kontraksi'    => $this->request->getPost('kontraksi'),
                'bjj'          => $this->request->getPost('bjj'),
                'ppv'          => $this->request->getPost('ppv'),
                'vt'           => $this->request->getPost('vt'),
                'nip'          => $this->request->getPost('nip'),
            ];

            $url = $this->api_url . '/catatan-observasi-ranap-kebidanan';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status === 201) {
                return redirect()->to(base_url('catatanobservasikebidanan'));
            } else {
                return $response;
            }
        } else {
            return $this->renderErrorView(401);
        }
    }



public function editCatatanObservasiKebidanan($noRawat)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $title = 'Edit Observasi Ranap Kebidanan';

    $url = $this->api_url . '/catatan-observasi-ranap-kebidanan/' . $noRawat;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200) {
        return $this->renderErrorView($status);
    }

    $data = json_decode($response, true);
    $catatan = $data['data'][0] ?? [];

    // 🔹 Fetch pasien data
    $nama_pasien = '';
    $tgl_lahir = '';

    if (!empty($catatan['no_rawat'])) {
        $registrasi_url = $this->api_url . '/registrasi/by-no-rawat/' . $catatan['no_rawat'];

        $ch_reg = curl_init($registrasi_url);
        curl_setopt($ch_reg, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_reg, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response_reg = curl_exec($ch_reg);
        curl_close($ch_reg);

        $reg_data = json_decode($response_reg, true);
        $nama_pasien = $reg_data['data']['nama_pasien'] ?? '';
        $no_rkm_medis = $reg_data['data']['nomor_rm'] ?? '';

        if (!empty($no_rkm_medis)) {
            $pasien_url = $this->api_url . '/pasien/' . urlencode($no_rkm_medis);

            $ch_pasien = curl_init($pasien_url);
            curl_setopt($ch_pasien, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pasien, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_pasien = curl_exec($ch_pasien);
            curl_close($ch_pasien);

            $pasien_data = json_decode($response_pasien, true);
            $tgl_lahir = $pasien_data['data']['tgl_lahir'] ?? '';
        }
    }

    // 🔹 Fetch nama petugas
    $nama_petugas = '—';
    if (!empty($catatan['nip'])) {
        $pegawai_url = $this->api_url . '/pegawai/nip/' . urlencode($catatan['nip']);

        $ch_petugas = curl_init($pegawai_url);
        curl_setopt($ch_petugas, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_petugas, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response_petugas = curl_exec($ch_petugas);
        curl_close($ch_petugas);

        $pegawai_data = json_decode($response_petugas, true);
        if (
            isset($pegawai_data['status']) &&
            $pegawai_data['status'] === 'success' &&
            isset($pegawai_data['data']['Nama'])
        ) {
            $nama_petugas = $pegawai_data['data']['Nama'];
        }
    }

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Observasi Kebidanan', 'catatanobservasikebidanan');
    $this->addBreadcrumb('Edit', 'edit');
    $breadcrumbs = $this->getBreadcrumbs();
// dd($catatan);
    return view('/admin/observasikebidanan/edit_catatanobservasikebidanan', [
        'catatan' => $catatan,
        'title' => $title,
        'breadcrumbs' => $breadcrumbs,
        'nama_pasien' => $nama_pasien,
        'tgl_lahir' => $tgl_lahir,
        'nama_petugas' => $nama_petugas,
    ]);
}

public function submitEditCatatanObservasiKebidanan($noRawat)
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $url = $this->api_url . '/catatan-observasi-ranap-kebidanan/' . $noRawat;

    $tanggalInput = trim($this->request->getPost('tanggal'));
    $tanggal = $tanggalInput !== '' ? $tanggalInput : date('Y-m-d');

    $data = [
        'no_rawat'   => $noRawat,
        'tgl_perawatan' => $tanggal,
        'jam_rawat'  => $this->request->getPost('jam'),
        'nip'        => $this->request->getPost('nip'),
        'gcs'        => $this->request->getPost('gcs'),
        'td'         => $this->request->getPost('td'),
        'hr'         => $this->request->getPost('hr'),
        'rr'         => $this->request->getPost('rr'),
        'suhu'       => $this->request->getPost('suhu'),
        'spo2'       => $this->request->getPost('spo2'),
        'kontraksi'  => $this->request->getPost('kontraksi'),
        'bjj'        => $this->request->getPost('bjj'),
        'ppv'        => $this->request->getPost('ppv'),
        'vt'         => $this->request->getPost('vt'),
    ];

    $json = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json),
        'Authorization: Bearer ' . $token,
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 200) {
        return redirect()->to(base_url('catatanobservasikebidanan'));
    } else {
        $error = json_decode($response, true);
        return $error['data'] ?? $response;
    }
}

   public function hapusCatatanObservasiKebidanan($noRawat)
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');
        $delete_url = $this->api_url . '/catatan-observasi-ranap-kebidanan/' . $noRawat;

        $ch = curl_init($delete_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200 || $http_status === 204) {
            return redirect()->to(base_url('catatanobservasikebidanan'))->with('success', 'Data observasi kebidanan berhasil dihapus.');
        } else {
            return $this->renderErrorView($http_status);
        }
    } else {
        return $this->renderErrorView(401);
    }
}

public function submitFromRawatinapToCatatanObservasi($nomor_rawat)
{
    if (!session()->has('jwt_token')) {
        return redirect()->back()->with('error', 'Session token missing.');
    }

    $token = session()->get('jwt_token');

    // Step 1: Fetch rawat inap data
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
        return redirect()->back()->with('error', 'Rawat inap data not found.');
    }

    $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];
    if (!is_array($rawatinap)) {
        return redirect()->back()->with('error', 'Invalid rawat inap data format.');
    }

    // Step 2: Pre-fill catatan observasi form
    $prefill = [
        'no_rawat'     => $rawatinap['nomor_rawat'] ?? $nomor_rawat,
        'nip'          => '', // Petugas input sendiri
        'nama_pasien'  => $rawatinap['nama_pasien'] ?? '',
        'tanggal'      => $rawatinap['tanggal_masuk'] ?? date('Y-m-d'),
        'jam'          => $rawatinap['jam_masuk'] ?? date('H:i:s'),
        'kontraksi'    => '',
        'bjj'          => '',
        'ppv'          => '',
        'vt'           => '',
    ];

    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Observasi Kebidanan', 'catatanobservasikebidanan');
    $this->addBreadcrumb('Tambah', 'tambah');

    return view('/admin/observasikebidanan/tambah_catatanobservasi', [
        'title' => 'Tambah Observasi Kebidanan',
        'breadcrumbs' => $this->getBreadcrumbs(),
        'prefill' => $prefill
    ]);
}

}