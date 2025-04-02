<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RegistrasiController extends BaseController
{
    public function dataRegistrasi()
    {
        $title = 'Data Registrasi';
    
        // Check if the user has a valid session with JWT token
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $registrasi_url = $this->api_url . '/registrasi';
    
            // Initialize cURL to fetch registration data from Go API
            $ch_registrasi = curl_init($registrasi_url);
            curl_setopt($ch_registrasi, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_registrasi, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_registrasi = curl_exec($ch_registrasi);
            $http_status_code_registrasi = curl_getinfo($ch_registrasi, CURLINFO_HTTP_CODE);
            curl_close($ch_registrasi);
    
            // Check API response status
            if ($http_status_code_registrasi !== 200) {
                return $this->renderErrorView($http_status_code_registrasi);
            }
    
            // Decode JSON response
            $registrasi_data = json_decode($response_registrasi, true);

            // dd($registrasi_data);
    
            // Ensure we have valid data
            if (!isset($registrasi_data['data'])) {
                return $this->renderErrorView(500);
            }
    
            // Set up breadcrumbs (for UI navigation)
            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Registrasi', 'registrasi');
            $breadcrumbs = $this->getBreadcrumbs();
            
            // Ensure we have valid meta data
            if (!isset($registrasi_data['meta_data'])) {
                $meta_data = ['page' => 1, 'size' => 10, 'total' => 1]; // Provide default values
            } else {
                $meta_data = $registrasi_data['meta_data'];
                $meta_data['total'] = $meta_data['total'] ?? 1; // Set a default value for 'total' if missing
            }

            // Return the view with registration data
            return view('/admin/registrasi/registrasi_data', [
                'registrasi_data' => $registrasi_data['data'],
                // dd($registrasi_data),
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'medis_data' => $medis_data ?? [], // Ensure medis_data is always set
                'medis_tanpa_params_data' => $medis_tanpa_params_data ?? [], // Ensure it's always set
                'meta_data' => $meta_data ?? ['page' => 1, 'size' => 10] // Ensure meta_data is always set
            ]);
        } else {
            // If no JWT token, return unauthorized error
            return $this->renderErrorView(401);
        }
    }
    

    public function tambahRegistrasi()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $title = 'Tambah registrasi';
            // $satuan_url = $this->api_url . '/ref/inventory/satuan';
            // $industri_url = $this->api_url . '/ref/inventory/industri';
            // $jenis_url = $this->api_url . '/ref/inventory/jenis';
            // $kategori_url = $this->api_url . '/ref/inventory/kategori';
            // $golongan_url = $this->api_url . '/ref/inventory/golongan';

            // $ch_satuan = curl_init($satuan_url);
            // curl_setopt($ch_satuan, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch_satuan, CURLOPT_HTTPHEADER, [
            //     'Authorization: Bearer ' . $token,
            // ]);
            // $response_satuan = curl_exec($ch_satuan);
            // $http_status_code_satuan = curl_getinfo($ch_satuan, CURLINFO_HTTP_CODE);
            // curl_close($ch_satuan);

            // $ch_industri = curl_init($industri_url);
            // curl_setopt($ch_industri, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch_industri, CURLOPT_HTTPHEADER, [
            //     'Authorization: Bearer ' . $token,
            // ]);
            // $response_industri = curl_exec($ch_industri);
            // $http_status_code_industri = curl_getinfo($ch_industri, CURLINFO_HTTP_CODE);
            // curl_close($ch_industri);

            // $ch_jenis = curl_init($jenis_url);
            // curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
            //     'Authorization: Bearer ' . $token,
            // ]);
            // $response_jenis = curl_exec($ch_jenis);
            // $http_status_code_jenis = curl_getinfo($ch_jenis, CURLINFO_HTTP_CODE);
            // curl_close($ch_jenis);

            // $ch_kategori = curl_init($kategori_url);
            // curl_setopt($ch_kategori, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch_kategori, CURLOPT_HTTPHEADER, [
            //     'Authorization: Bearer ' . $token,
            // ]);
            // $response_kategori = curl_exec($ch_kategori);
            // $http_status_code_kategori = curl_getinfo($ch_kategori, CURLINFO_HTTP_CODE);
            // curl_close($ch_kategori);

            // $ch_golongan = curl_init($golongan_url);
            // curl_setopt($ch_golongan, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch_golongan, CURLOPT_HTTPHEADER, [
            //     'Authorization: Bearer ' . $token,
            // ]);
            // $response_golongan = curl_exec($ch_golongan);
            // $http_status_code_golongan = curl_getinfo($ch_golongan, CURLINFO_HTTP_CODE);
            // curl_close($ch_golongan);

            // if ($http_status_code_satuan !== 201) {
            //     return $this->renderErrorView($http_status_code_satuan);
            // }
            // if ($http_status_code_industri !== 201) {
            //     return $this->renderErrorView($http_status_code_industri);
            // }
            // if ($http_status_code_jenis !== 201) {
            //     return $this->renderErrorView($http_status_code_jenis);
            // }
            // if ($http_status_code_kategori !== 201) {
            //     return $this->renderErrorView($http_status_code_kategori);
            // }
            // if ($http_status_code_golongan !== 201) {
            //     return $this->renderErrorView($http_status_code_golongan);
            // }

            // $satuan_data = json_decode($response_satuan, true);
            // $industri_data = json_decode($response_industri, true);
            // $jenis_data = json_decode($response_jenis, true);
            // $kategori_data = json_decode($response_kategori, true);
            // $golongan_data = json_decode($response_golongan, true);

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Registrasi', 'registrasi');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/registrasi/tambah_registrasi', [
                // 'satuan_data' => $satuan_data['data'],
                // 'industri_data' => $industri_data['data'],
                // 'jenis_data' => $jenis_data['data'],
                // 'kategori_data' => $kategori_data['data'],
                // 'golongan_data' => $golongan_data['data'],
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } else {
            return $this->renderErrorView(401);
        }
    }

    public function submitTambahRegistrasi()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            
            // Get data from the form
            $nomor_reg = $this->request->getPost('nomor_reg');
            $nomor_rawat = $this->request->getPost('nomor_rawat');
            $tanggal = $this->request->getPost('tanggal');
            $nomor_rekam_medis = $this->request->getPost('nomor_rekam_medis');
            $jenis_kelamin = $this->request->getPost('jenis_kelamin');
            $poliklinik = $this->request->getPost('poliklinik');
            $dokter = $this->request->getPost('dokter');
            $nama = $this->request->getPost('nama');
            $umur = intval($this->request->getPost('umur'));
            $penanggung_jawab = $this->request->getPost('penanggung_jawab');
            $alamat_penanggung_jawab = $this->request->getPost('alamat_penanggung_jawab');
            $biaya_registrasi = intval($this->request->getPost('biaya_registrasi'));
            $status_rawat = $this->request->getPost('status_rawat');
            $status_bayar = $this->request->getPost('status_bayar');
            $jam = $this->request->getPost('jam');
            $hubungan_penanggung_jawab = $this->request->getPost('hubungan_penanggung_jawab');
            $nomor_telepon = $this->request->getPost('nomor_telepon');
            $status_registrasi = $this->request->getPost('status_registrasi');
            $status_poliklinik = $this->request->getPost('status_poliklinik');
            $jenis_bayar = $this->request->getPost('jenis_bayar');

            // Validate that dokter is not empty
        if (empty($dokter)) {
            return $this->response->setJSON([
                'code' => 400,
                'status' => 'Bad Request',
                'data' => 'Dokter field is required.'
            ]);
        }
            
            // Prepare data to be inserted into PostgreSQL or passed to another system
            $postDataRegistrasi = [
                'nomor_reg' => $nomor_reg,
                'nomor_rawat' => $nomor_rawat,
                'tanggal' => $tanggal,
                'nomor_rm' => $nomor_rekam_medis,
                'jenis_kelamin' => $jenis_kelamin,
                'poliklinik' => $poliklinik,
                'nama_dokter' => $dokter,
                'nama_pasien' => $nama,
                'kode_dokter' => "D001",
                'umur' => strval($umur),
                'penanggung_jawab' => $penanggung_jawab,
                'alamat_pj' => $alamat_penanggung_jawab,
                'biaya_registrasi' => $biaya_registrasi,
                'status_rawat' => $status_rawat,
                'status_bayar' => $status_bayar,
                'jam' => $jam,
                'hubungan_pj' => $hubungan_penanggung_jawab,
                'no_telepon' => $nomor_telepon,
                'status_registrasi' => $status_registrasi,
                'status_poli' => $status_poliklinik,
                'jenis_bayar' => $jenis_bayar,
            ];
    
            // Example cURL or database insertion logic goes here to save this data in PostgreSQL
            // Assuming you use cURL for external APIs like you did previously:
            
            $medis_url = $this->api_url . '/registrasi';
            
            $tambah_registrasi_JSON = json_encode($postDataRegistrasi);
    
            $ch_registrasi = curl_init($medis_url);
            curl_setopt($ch_registrasi, CURLOPT_POST, 1);
            curl_setopt($ch_registrasi, CURLOPT_POSTFIELDS, ($tambah_registrasi_JSON));
            curl_setopt($ch_registrasi, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_registrasi, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($tambah_registrasi_JSON),
                'Authorization: Bearer ' . $token,
            ]);
            $response_registrasi = curl_exec($ch_registrasi);
            $http_status_code_registrasi = curl_getinfo($ch_registrasi, CURLINFO_HTTP_CODE);
    
            if ($http_status_code_registrasi === 201) {
                return redirect()->to(base_url('registrasi'));
            } else {
                return $response_registrasi;
            }
            
            curl_close($ch_registrasi);
        } else {
            return $this->renderErrorView(401);
        }
    }
    

    public function editRegistrasi($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $title = 'Edit Registrasi';
        $registrasi_url = $this->api_url . '/registrasi/' . $nomorReg;
    
        $ch_registrasi = curl_init($registrasi_url);
        curl_setopt($ch_registrasi, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_registrasi, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch_registrasi);
        $http_status = curl_getinfo($ch_registrasi, CURLINFO_HTTP_CODE);
        curl_close($ch_registrasi);
    
        if ($http_status !== 200) {
            return $this->renderErrorView($http_status);
        }
    
        $registrasi_data = json_decode($response, true);
    
        // Additional reference data if needed (optional)
        // Example: $dokter_url = $this->api_url . '/ref/dokter';
        // You can also fetch poliklinik, jenis bayar, etc., if needed for dropdowns
    
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Registrasi', 'registrasi');
        $this->addBreadcrumb('Edit', 'edit');
    
        $breadcrumbs = $this->getBreadcrumbs();
    
        return view('/admin/registrasi/edit_registrasi', [
            'registrasi' => $registrasi_data['data'],
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            // Add other dropdown data if needed
        ]);
    }
    

    public function submitEditRegistrasi($nomorReg)
{
    if (session()->has('jwt_token')) {
        $token = session()->get('jwt_token');
        $registrasi_url = $this->api_url . '/registrasi/' . $nomorReg;

        // Get data from the form
        $nomor_reg = $this->request->getPost('nomor_reg');
        $nomor_rawat = $this->request->getPost('nomor_rawat');
        $tanggal = $this->request->getPost('tanggal');
        $nomor_rekam_medis = $this->request->getPost('nomor_rekam_medis');
        $jenis_kelamin = $this->request->getPost('jenis_kelamin');
        $poliklinik = $this->request->getPost('poliklinik');
        $dokter = $this->request->getPost('dokter');
        $nama = $this->request->getPost('nama');
        $umur = intval($this->request->getPost('umur'));
        $penanggung_jawab = $this->request->getPost('penanggung_jawab');
        $alamat_penanggung_jawab = $this->request->getPost('alamat_penanggung_jawab');
        $biaya_registrasi = intval($this->request->getPost('biaya_registrasi'));
        $status_rawat = $this->request->getPost('status_rawat');
        $status_bayar = $this->request->getPost('status_bayar');
        $jam = $this->request->getPost('jam');
        $hubungan_penanggung_jawab = $this->request->getPost('hubungan_penanggung_jawab');
        $nomor_telepon = $this->request->getPost('nomor_telepon');
        $status_registrasi = $this->request->getPost('status_registrasi');
        $status_poliklinik = $this->request->getPost('status_poliklinik');
        $jenis_bayar = $this->request->getPost('jenis_bayar');
        
        // Prepare data to be updated
        $postDataRegistrasi = [
            'nomor_reg' => $nomor_reg,
            'nomor_rawat' => $nomor_rawat,
            'tanggal' => $tanggal,
            'nomor_rm' => $nomor_rekam_medis,
            'jenis_kelamin' => $jenis_kelamin,
            'poliklinik' => $poliklinik,
            'nama_dokter' => $dokter,
            'nama_pasien' => $nama,
            'kode_dokter' => "D001",
            'umur' => strval($umur),
            'penanggung_jawab' => $penanggung_jawab,
            'alamat_pj' => $alamat_penanggung_jawab,
            'biaya_registrasi' => $biaya_registrasi,
            'status_rawat' => $status_rawat,
            'status_bayar' => $status_bayar,
            'jam' => $jam,
            'hubungan_pj' => $hubungan_penanggung_jawab,
            'no_telepon' => $nomor_telepon,
            'status_registrasi' => $status_registrasi,
            'status_poli' => $status_poliklinik,
            'jenis_bayar' => $jenis_bayar,
        ];

        // cURL setup for PUT request
        $tambah_registrasi_JSON = json_encode($postDataRegistrasi);

        $ch_registrasi = curl_init($registrasi_url);
        curl_setopt($ch_registrasi, CURLOPT_CUSTOMREQUEST, "PUT");  // Use PUT instead of POST
        curl_setopt($ch_registrasi, CURLOPT_POSTFIELDS, ($tambah_registrasi_JSON));
        curl_setopt($ch_registrasi, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_registrasi, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($tambah_registrasi_JSON),
            'Authorization: Bearer ' . $token,
        ]);
        $response_registrasi = curl_exec($ch_registrasi);
        $http_status_code_registrasi = curl_getinfo($ch_registrasi, CURLINFO_HTTP_CODE);

        // Handle the response based on the HTTP status code
        if ($http_status_code_registrasi === 200) {
            return redirect()->to(base_url('registrasi'));
        } else {
            return $response_registrasi;
        }

        curl_close($ch_registrasi);
    } else {
        return $this->renderErrorView(401);
    }
}


    public function hapusRegistrasi($nomorReg)
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $delete_url = $this->api_url . '/registrasi/' . $nomorReg;

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
                return redirect()->to(base_url('registrasi'))->with('success', 'Data registrasi berhasil dihapus.');
            } else {
                return $this->renderErrorView($http_status);
            }
        } else {
            return $this->renderErrorView(401);
        }
    }


}

