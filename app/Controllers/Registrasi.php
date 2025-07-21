<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Registrasi extends BaseController
{
    protected array $breadcrumbs = [];
    protected string $judul = 'Registrasi';
    protected string $modul_path = '/registrasi';
    protected string $api_path = '/registrasi';
    protected string $nama_tabel = 'registrasi';
    protected string $kolom_id = 'nomor_reg';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => true,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true,
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Registrasi', 'nomor_reg', 'indeks'],
        [1, 'Nomor Rawat', 'nomor_rawat', 'indeks'],
        [1, 'Tanggal', 'tanggal', 'tanggal'],
        [1, 'Jam', 'jam', 'jam'],
        [0, 'Nomor Rekam Medis', 'nomor_rm', 'indeks'],
        [1, 'Nama', 'nama_pasien', 'nama'],
        [1, 'Jenis Kelamin', 'jenis_kelamin', 'status'],
        [0, 'Umur', 'umur', 'jumlah'],
        [0, 'Poliklinik', 'poliklinik', 'status'],
        [0, 'Dokter', 'nama_dokter', 'nama'],
        [0, 'Penanggung Jawab', 'penanggung_jawab', 'nama'],
        [0, 'Hubungan Penanggung Jawab', 'hubungan_pj', 'teks'],
        [0, 'Alamat Penanggung Jawab', 'alamat_pj', 'teks'],
        [1, 'Pekerjaan PJ', 'pekerjaanpj', 'teks'],
        [1, 'Alamat PJ', 'alamat_pj', 'teks'],
        [1, 'Kelurahan PJ', 'kelurahanpj', 'teks'],
        [1, 'Kecamatan PJ', 'kecamatanpj', 'teks'],
        [1, 'Kabupaten PJ', 'kabupatenpj', 'teks'],
        [1, 'Provinsi PJ', 'propinsipj', 'teks'],
        [1, 'No. Telp PJ', 'notelp_pj', 'teks'],
        [1, 'Nomor Telepon', 'no_telepon', 'teks'],
        [1, 'Biaya Registrasi', 'biaya_registrasi', 'uang'],
        [1, 'Status Registrasi', 'status_registrasi', 'status'],
        [1, 'Status Rawat', 'status_rawat', 'status'],
        [1, 'Status Poliklinik', 'status_poli', 'status'],
        [1, 'Jenis Bayar', 'jenis_bayar', 'status'],
        [1, 'Status Bayar', 'status_bayar', 'status'],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];



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

            $this->addBreadcrumb('User', 'user');
            $this->addBreadcrumb('Registrasi', 'registrasi');
            $this->addBreadcrumb('Tambah', 'tambah');

            $breadcrumbs = $this->getBreadcrumbs();

            return view('/admin/registrasi/tambah_registrasi', [
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
            $nomor_rekam_medis = $this->request->getPost('no_rkm_medis');
            $jenis_kelamin = $this->request->getPost('jenis_kelamin');
            $poliklinik = $this->request->getPost('poliklinik');
            $kode_dokter = $this->request->getPost('kode_dokter');
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
            $pekerjaan_pj = $this->request->getPost('pekerjaanpj');
            $kelurahan_pj = $this->request->getPost('kelurahanpj');
            $kecamatan_pj = $this->request->getPost('kecamatanpj');
            $kabupaten_pj = $this->request->getPost('kabupatenpj');
            $propinsi_pj = $this->request->getPost('propinsipj');
            $notelp_pj    = $this->request->getPost('notelp_pj');

            // Validate that dokter is not empty
            // if (empty($dokter)) {
            //     return $this->response->setJSON([
            //         'code' => 400,
            //         'status' => 'Bad Request',
            //         'data' => 'Dokter field is required.'
            //     ]);
            // }

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
                'kode_dokter' => $this->request->getPost('kode_dokter'),
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
                'pekerjaanpj' => $pekerjaan_pj,
                'kelurahanpj' => $kelurahan_pj,
                'kecamatanpj' => $kecamatan_pj,
                'kabupatenpj' => $kabupaten_pj,
                'propinsipj'  => $propinsi_pj,
                'notelp_pj'   => $notelp_pj,
            ];
            // dd($postDataRegistrasi);
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

        // dd($registrasi_data);

        // Additional reference data if needed (optional)
        // Example: $dokter_url = $this->api_url . '/ref/dokter';
        // You can also fetch poliklinik, jenis bayar, etc., if needed for dropdowns

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Registrasi', 'registrasi');
        $this->addBreadcrumb('Edit', 'edit');

        $breadcrumbs = $this->getBreadcrumbs();
        // dd($registrasi_data);
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
            $pekerjaan_pj = $this->request->getPost('pekerjaanpj');
            $kelurahan_pj = $this->request->getPost('kelurahanpj');
            $kecamatan_pj = $this->request->getPost('kecamatanpj');
            $kabupaten_pj = $this->request->getPost('kabupatenpj');
            $propinsi_pj  = $this->request->getPost('propinsipj');
            $notelp_pj    = $this->request->getPost('notelp_pj');

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
                'pekerjaanpj' => $pekerjaan_pj,
                'kelurahanpj' => $kelurahan_pj,
                'kecamatanpj' => $kecamatan_pj,
                'kabupatenpj' => $kabupaten_pj,
                'propinsipj'  => $propinsi_pj,
                'notelp_pj'   => $notelp_pj,
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

    public function triggerNotif()
    {
        $nomorReg = $this->request->getPost('nomor_reg');

        // Optional: validate
        if (!$nomorReg) {
            return redirect()->back()->with('error', 'Nomor Registrasi tidak ditemukan.');
        }

        // Call the Go API to update the status_kamar
        $client = \Config\Services::curlrequest();
        $response = $client->request('PUT', 'http://127.0.0.1:8080/v1/registrasi/' . $nomorReg . '/assign-room-false');

        if ($response->getStatusCode() === 200) {
            return redirect()->back()->with('success', 'Status kamar berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah status kamar.');
        }
    }
}
