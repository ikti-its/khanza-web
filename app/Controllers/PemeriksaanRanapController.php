<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PemeriksaanRanapController extends BaseController
{
public function dataPemeriksaanRanap()
{
    $title = 'Data Pemeriksaan Ranap';

    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');
    $pemeriksaan_url = $this->api_url . '/pemeriksaanranap';

    // 🔹 Fetch pemeriksaan ranap data
    $ch = curl_init($pemeriksaan_url);
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

    $pemeriksaan_list = $data['data'];

    // dd($pemeriksaan_list);

    // 🔁 Enrich each item with registrasi data and nama pegawai
    foreach ($pemeriksaan_list as &$item) {
        // ✅ Fetch registrasi data
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

            $item['nomor_rm'] = $reg_data['data']['nomor_rm'] ?? '';
            $item['nama_pasien'] = $reg_data['data']['nama_pasien'] ?? '';
            $item['umur'] = $reg_data['data']['umur'] ?? '';
            $item['jenis_kelamin'] = $reg_data['data']['jenis_kelamin'] ?? '';
        }

        // ✅ Fetch nama petugas based on NIP
if (isset($item['nip']) && !empty($item['nip'])) {
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
    // error_log("📥 Pegawai response for NIP {$nip}: " . $response_nama);

    // ✅ Defensive check for nested structure
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

    // Breadcrumbs & pagination
    $this->addBreadcrumb('User', 'user');
    $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
    $breadcrumbs = $this->getBreadcrumbs();

    $meta_data = $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => count($pemeriksaan_list)];
    // dd($pemeriksaan_list);
    return view('/admin/pemeriksaanranap/pemeriksaanranap_data', [
        'pemeriksaanranap_data' => $pemeriksaan_list,
        'title' => $title,
        'breadcrumbs' => $breadcrumbs,
        'meta_data' => $meta_data
    ]);
}


    public function tambahPemeriksaanRanap()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah Pemeriksaan Ranap';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/pemeriksaanranap/tambah_pemeriksaanranap', [
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahPemeriksaanRanap()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            
            // Get data from the form
            $nomor_rawat = $this->request->getPost('nomor_rawat');
            $tanggal = $this->request->getPost('tanggal');
            $jam = $this->request->getPost('jam');
            $dokter = $this->request->getPost('dokter');
            $nama_pasien = $this->request->getPost('nama_pasien');
            $diagnosa_awal = $this->request->getPost('diagnosa_awal');
            $status_pemeriksaan = $this->request->getPost('status_pemeriksaan');

            // Prepare data to be inserted
            $postDataPemeriksaanRanap = [
                'nomor_rawat' => $nomor_rawat,
                'tanggal' => $tanggal,
                'jam' => $jam,
                'dokter' => $dokter,
                'nama_pasien' => $nama_pasien,
                'diagnosa_awal' => $diagnosa_awal,
                'status_pemeriksaan' => $status_pemeriksaan,
            ];
    
            $pemeriksaan_url = $this->api_url . '/pemeriksaanranap';
            $tambah_pemeriksaanranap_JSON = json_encode($postDataPemeriksaanRanap);
    
            $ch_pemeriksaan = curl_init($pemeriksaan_url);
            curl_setopt($ch_pemeriksaan, CURLOPT_POST, 1);
            curl_setopt($ch_pemeriksaan, CURLOPT_POSTFIELDS, $tambah_pemeriksaanranap_JSON);
            curl_setopt($ch_pemeriksaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemeriksaan, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemeriksaan = curl_exec($ch_pemeriksaan);
            $http_status_code_pemeriksaan = curl_getinfo($ch_pemeriksaan, CURLINFO_HTTP_CODE);
    
            if ($http_status_code_pemeriksaan === 201) {
                return redirect()->to(base_url('pemeriksaanranap'));
            } else {
                return $response_pemeriksaan;
            }
            
            curl_close($ch_pemeriksaan);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function editPemeriksaanRanap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Edit Pemeriksaan Ranap';
        $pemeriksaan_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;
    
        $ch_pemeriksaan = curl_init($pemeriksaan_url);
        curl_setopt($ch_pemeriksaan, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pemeriksaan, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch_pemeriksaan);
        $http_status = curl_getinfo($ch_pemeriksaan, CURLINFO_HTTP_CODE);
        curl_close($ch_pemeriksaan);
    
        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }
    
        $pemeriksaan_data = json_decode($response, true);
    
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Pemeriksaan Ranap', 'pemeriksaanranap');
        $this->addBreadcrumb('Edit', 'edit');
    
        $breadcrumbs = $this->getBreadcrumbs();
    
        return view('/admin/pemeriksaanranap/edit_pemeriksaanranap', [
            'pemeriksaan' => $pemeriksaan_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function submitEditPemeriksaanRanap($nomorRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $pemeriksaan_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;
    
            // Get data from the form
            $nomor_rawat = $this->request->getPost('nomor_rawat');
            $tanggal = $this->request->getPost('tanggal');
            $jam = $this->request->getPost('jam');
            $dokter = $this->request->getPost('dokter');
            $nama_pasien = $this->request->getPost('nama_pasien');
            $diagnosa_awal = $this->request->getPost('diagnosa_awal');
            $status_pemeriksaan = $this->request->getPost('status_pemeriksaan');
    
            // Prepare data to be updated
            $postDataPemeriksaanRanap = [
                'nomor_rawat' => $nomor_rawat,
                'tanggal' => $tanggal,
                'jam' => $jam,
                'dokter' => $dokter,
                'nama_pasien' => $nama_pasien,
                'diagnosa_awal' => $diagnosa_awal,
                'status_pemeriksaan' => $status_pemeriksaan,
            ];
    
            $tambah_pemeriksaanranap_JSON = json_encode($postDataPemeriksaanRanap);
    
            $ch_pemeriksaan = curl_init($pemeriksaan_url);
            curl_setopt($ch_pemeriksaan, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch_pemeriksaan, CURLOPT_POSTFIELDS, ($tambah_pemeriksaanranap_JSON));
            curl_setopt($ch_pemeriksaan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pemeriksaan, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($tambah_pemeriksaanranap_JSON),
                'Authorization: Bearer ' . $token,
            ]);
            $response_pemeriksaan = curl_exec($ch_pemeriksaan);
            $http_status_code_pemeriksaan = curl_getinfo($ch_pemeriksaan, CURLINFO_HTTP_CODE);
    
            if ($http_status_code_pemeriksaan === 200) {
                return redirect()->to(base_url('pemeriksaanranap'));
            } else {
                return $response_pemeriksaan;
            }
    
            curl_close($ch_pemeriksaan);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function hapusPemeriksaanRanap($nomorRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/pemeriksaanranap/' . $nomorRawat;
    
            $ch_delete = curl_init($delete_url);
            curl_setopt($ch_delete, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch_delete, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_delete, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ]);
    
            $response = curl_exec($ch_delete);
            $http_status = curl_getinfo($ch_delete, CURLINFO_HTTP_CODE);
            curl_close($ch_delete);
    
            if ($http_status === 200 || $http_status === 204) {
                return redirect()->to(base_url('pemeriksaanranap'))->with('success', 'Data pemeriksaan ranap berhasil dihapus.');
            } else {
                return $this->renderErrorView($http_status);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }
}
