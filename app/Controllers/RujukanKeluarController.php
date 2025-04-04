<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RujukanModel;


class RujukanKeluarController extends BaseController
{
    public function dataRujukanKeluar()
    {
        $title = 'Data Rujukan Keluar';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $rujukan_url = $this->api_url . '/rujukankeluar';

            $ch = curl_init($rujukan_url);
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

            $rujukan_data = json_decode($response, true);

            if (!isset($rujukan_data['data'])) {
                return $this->renderErrorView(500);
            }

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Rujukan Keluar', 'rujukankeluar');
            $breadcrumbs = $this->getBreadcrumbs();

            $meta_data = $rujukan_data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1];

            return view('/admin/rujukan/rujukan_keluar_data', [
                'rujukankeluar_data' => $rujukan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'meta_data' => $meta_data,
            ]);
        }

        return $this->renderErrorView(401);
    }

    public function tambahRujukanKeluar()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $title = 'Tambah Rujukan Keluar';
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rujukan Keluar', 'rujukankeluar');
        $this->addBreadcrumb('Tambah', 'tambah');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/rujukan/tambah_rujukan_keluar', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function submitTambahRujukanKeluar()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $postData = $this->request->getPost([
            'nomor_rujuk', 'nomor_rawat', 'nomor_rm', 'nama_pasien',
            'tempat_rujuk', 'tanggal_rujuk', 'jam_rujuk',
            'keterangan_diagnosa', 'dokter_perujuk', 'kategori_rujuk',
            'pengantaran', 'keterangan'
        ]);

        if (empty($postData['nomor_rawat'])) {
            return $this->response->setJSON([
                'code' => 400,
                'status' => 'Bad Request',
                'data' => 'Nomor Rawat is required.'
            ]);
        }

        $jsonData = json_encode($postData);
        $url = $this->api_url . '/rujukankeluar';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
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

        if ($http_status === 201) {
            return redirect()->to(base_url('rujukankeluar'));
        }

        return $this->response->setJSON([
            'code' => $http_status,
            'status' => 'Error',
            'message' => 'Failed to submit rujukan',
            'response' => $response
        ]);
    }

    public function editRujukanKeluar($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $title = 'Edit Rujukan Keluar';
        $rujukan_url = $this->api_url . '/rujukankeluar/' . $nomorRawat;

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
        $this->addBreadcrumb('Rujukan Keluar', 'rujukankeluar');
        $this->addBreadcrumb('Edit', 'edit');
        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/rujukan/edit_rujukan_keluar', [
            'rujukan' => $rujukan_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function submitEditRujukanKeluar($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rujukankeluar/' . $nomorRawat;

        $postData = $this->request->getPost([
            'nomor_rujuk', 'nomor_rm', 'nama_pasien',
            'tempat_rujuk', 'tanggal_rujuk', 'jam_rujuk',
            'keterangan_diagnosa', 'dokter_perujuk', 'kategori_rujuk',
            'pengantaran', 'keterangan'
        ]);

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
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200) {
            return redirect()->to(base_url('rujukankeluar'));
        }

        return $this->response->setJSON([
            'code' => $http_status,
            'status' => 'Failed to update data',
            'response' => $response
        ]);
    }

    public function hapusRujukanKeluar($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rujukankeluar/' . $nomorRawat;

        $ch = curl_init($url);
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
            return redirect()->to(base_url('rujukankeluar'))->with('success', 'Data rujukan keluar berhasil dihapus.');
        }

        return $this->renderErrorView($http_status);
    }

    public function cetak($nomor_rawat)
    {
        $model = new RujukanModel();
        $rujukan = $model->find($nomor_rawat);
    
        if (!$rujukan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data tidak ditemukan");
        }
    
        return view('/admin/rujukan/cetak_surat', ['rujukan' => $rujukan]);
    }
    
}
