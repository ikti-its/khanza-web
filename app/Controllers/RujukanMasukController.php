<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RujukanMasukController extends BaseController
{
    public function dataRujukanMasuk()
    {
        $title = 'Data Rujukan Masuk';

        // Check if the user has a valid session with JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $rujukan_url = $this->api_url . '/rujukanmasuk';

            // Initialize cURL to fetch rujukan data from Go API
            $ch_rujukan = curl_init($rujukan_url);
            curl_setopt($ch_rujukan, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_rujukan, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_rujukan = curl_exec($ch_rujukan);
            $http_status_code_rujukan = curl_getinfo($ch_rujukan, CURLINFO_HTTP_CODE);
            curl_close($ch_rujukan);

            // Check API response status
            if ($http_status_code_rujukan !== 200) {
                return $this->renderErrorView($http_status_code_rujukan);
            }

            // Decode JSON response
            $rujukan_data = json_decode($response_rujukan, true);

            // Ensure we have valid data
            if (!isset($rujukan_data['data'])) {
                return $this->renderErrorView(500);
            }

            // Set up breadcrumbs
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Rujukan Masuk', 'rujukanmasuk');
            $breadcrumbs = $this->getBreadcrumbs();

            // Meta fallback
            if (!isset($rujukan_data['meta_data'])) {
                $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
            } else {
                $meta_data = $rujukan_data['meta_data'];
                $meta_data['total'] = $meta_data['total'] ?? 1;
            }

            // Return the view with rujukan data
            return view('/admin/rujukan/rujukan_masuk_data', [
                'rujukanmasuk_data' => $rujukan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'medis_data' => $medis_data ?? [],
                'medis_tanpa_params_data' => $medis_tanpa_params_data ?? [],
                'meta_data' => $meta_data
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

        

    public function tambahRujukanMasuk()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah Rujukan Masuk';

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Rujukan Masuk', 'rujukanmasuk');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/rujukan/tambah_rujukan_masuk', [
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }


        public function submitTambahRujukanMasuk()
{
    if (!session()->has('jwt_token')) {
        return $this->renderErrorView(401);
    }

    $token = session()->get('jwt_token');

    // Get data from the form
    $nomor_rujuk = $this->request->getPost('nomor_rujuk');
    $perujuk = $this->request->getPost('perujuk');
    $alamat_perujuk = $this->request->getPost('alamat_perujuk');
    $nomor_rawat = $this->request->getPost('nomor_rawat');
    $nomor_rm = $this->request->getPost('nomor_rm');
    $nama_pasien = $this->request->getPost('nama_pasien');
    $alamat = $this->request->getPost('alamat');
    $umur = $this->request->getPost('umur');
    $tanggal_masuk = $this->request->getPost('tanggal_masuk');

    // FIX: handle null explicitly
    $tanggal_keluar_input = $this->request->getPost('tanggal_keluar');
    $tanggal_keluar = (trim($tanggal_keluar_input) !== '') ? $tanggal_keluar_input : null;

    $diagnosa_awal = $this->request->getPost('diagnosa_awal');

    if (empty($nomor_rawat)) {
        return $this->response->setJSON([
            'code' => 400,
            'status' => 'Bad Request',
            'data' => 'Nomor Rawat is required.'
        ]);
    }
        
                $postData = [
        'nomor_rujuk'      => $nomor_rujuk,
        'perujuk'          => $perujuk,
        'alamat_perujuk'   => $alamat_perujuk,
        'nomor_rawat'      => $nomor_rawat,
        'nomor_rm'         => $nomor_rm,
        'nama_pasien'      => $nama_pasien,
        'alamat'           => $alamat,
        'umur'             => $umur,
        'tanggal_masuk'    => $tanggal_masuk,
        'tanggal_keluar'   => $tanggal_keluar, // will be null if empty
        'diagnosa_awal'    => $diagnosa_awal
    ];

    $jsonData = json_encode($postData);

    $ch = curl_init($this->api_url . '/rujukanmasuk');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
        'Authorization: Bearer ' . $token
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 201) {
        return redirect()->to(base_url('rujukanmasuk'));
    } else {
        return $this->response->setJSON([
            'code' => $http_status,
            'status' => 'Error',
            'message' => 'Failed to submit rujukan',
            'response' => $response
        ]);
    }
}
        
        

    public function editRujukanMasuk($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Rujukan Masuk';
        $rujukan_url = $this->api_url . '/rujukanmasuk/' . $nomorRawat;

        $ch = curl_init($rujukan_url);
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

        $rujukan_data = json_decode($response, true);

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rujukan Masuk', 'rujukanmasuk');
        $this->addBreadcrumb('Edit', 'edit');

        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/rujukan/edit_rujukan_masuk', [
            'rujukan' => $rujukan_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

        

    public function submitEditRujukanMasuk($nomorRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $rujukan_url = $this->api_url . '/rujukanmasuk/' . $nomorRawat;

            // Get form data
            $nomor_rujuk = $this->request->getPost('nomor_rujuk');
            $perujuk = $this->request->getPost('perujuk');
            $alamat_perujuk = $this->request->getPost('alamat_perujuk');
            $nomor_rm = $this->request->getPost('nomor_rm');
            $nama_pasien = $this->request->getPost('nama_pasien');
            $alamat = $this->request->getPost('alamat');
            $umur = $this->request->getPost('umur');
            $tanggal_masuk = $this->request->getPost('tanggal_masuk');
            $tanggal_keluar = $this->request->getPost('tanggal_keluar');
            $diagnosa_awal = $this->request->getPost('diagnosa_awal');

            // Build payload
            $postData = [
                'nomor_rujuk' => $nomor_rujuk,
                'perujuk' => $perujuk,
                'alamat_perujuk' => $alamat_perujuk,
                'nomor_rm' => $nomor_rm,
                'nama_pasien' => $nama_pasien,
                'alamat' => $alamat,
                'umur' => $umur,
                'tanggal_masuk' => $tanggal_masuk,
                'tanggal_keluar' => $tanggal_keluar,
                'diagnosa_awal' => $diagnosa_awal
            ];

            // Encode as JSON and prepare cURL
            $jsonData = json_encode($postData);

            $ch = curl_init($rujukan_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData),
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status === 200) {
                return redirect()->to(base_url('rujukanmasuk'));
            } else {
                return $this->response->setJSON([
                    'code' => $http_status,
                    'status' => 'Failed to update data',
                    'response' => $response
                ]);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function hapusRujukanMasuk($nomorRawat)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/rujukanmasuk/' . $nomorRawat;

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
                return redirect()->to(base_url('rujukanmasuk'))->with('success', 'Data rujukan masuk berhasil dihapus.');
            } else {
                return $this->renderErrorView($http_status);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }



}

