<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RujukanModel;


class RujukanKeluar extends BaseController
{

protected array $breadcrumbs = [];
    protected string $judul = 'Rujukan Keluar';
    protected string $modul_path = '/rujukankeluar';
    protected string $api_path = '/rujukankeluar';
    protected string $kolom_id = 'nomor_rujuk';
    protected string $nama_tabel = 'rujukan_keluar';
    protected array $aksi = [
                            'cetak'    => true,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                            'ambulans' => true
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rujuk'        , 'nomor_rujuk', 'indeks'],
                            [0, 'Nomor Rawat'        , 'nomor_rawat', 'indeks'],
                            [0, 'Nomor Rekam Medis'  , 'nomor_rm'   , 'indeks'],
                            [1, 'Nama Pasien'        , 'nama_pasien', 'indeks'],
                            [1, 'Tempat Rujuk'       , 'tempat_rujuk', 'teks'],
                            [0, 'Tanggal Rujuk'      , 'tanggal_rujuk', 'tanggal'],
                            [0, 'Jam Rujuk'          , 'jam_rujuk', 'jam'],
                            [0, 'Keterangan Diagnosa', 'keterangan_diagnosa', 'teks'],
                            [0, 'Dokter Perujuk'     , 'dokter_perujuk', 'nama'],
                            [1, 'Kategori Rujuk'     , 'kategori_rujuk', 'status'],
                            [1, 'Pengantaran'        , 'pengantaran', 'teks'],
                            [0, 'Keterangan'         , 'keterangan', 'teks'],
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataRujukanKeluar()
    {
        $title = 'Data Rujukan Keluar';

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $rujukan_url = $this->api_url . '/rujukankeluar';
            $ambulans_url = $this->api_url . '/ambulans';

            $ch_ambulans = curl_init($ambulans_url);
            curl_setopt($ch_ambulans, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_ambulans, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response_ambulans = curl_exec($ch_ambulans);
            $http_status_ambulans = curl_getinfo($ch_ambulans, CURLINFO_HTTP_CODE);
            curl_close($ch_ambulans);
            $ambulans_data = [];

            if ($http_status_ambulans === 200) {
                $decoded_ambulans = json_decode($response_ambulans, true);
                $ambulans_data = $decoded_ambulans['data'] ?? [];
            }

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
                'ambulans_list' => $ambulans_data,
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
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        // Step 1: Fetch Rujukan Keluar
        $url_rujukan = $this->api_url . '/rujukankeluar/' . $nomor_rawat;
        $ch = curl_init($url_rujukan);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data tidak ditemukan");
        }

        $data = json_decode($response, true);
        $rujukan = $data['data'] ?? null;

        if (is_string($rujukan)) {
            $rujukan = json_decode($rujukan, true);
        }

        if (!$rujukan || !is_array($rujukan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data tidak ditemukan");
        }

        // Step 2: Fetch Organisasi Info
        $url_org = $this->api_url . '/organisasi';
        $ch_org = curl_init($url_org);
        curl_setopt($ch_org, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_org, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $response_org = curl_exec($ch_org);
        curl_close($ch_org);

        $data_org = json_decode($response_org, true);
        $organisasi = $data_org['data'] ?? null;

        if (is_string($organisasi)) {
            $organisasi = json_decode($organisasi, true);
        }

        // Step 3: Fetch Rawat Inap
        $url_rawatinap = $this->api_url . '/rawatinap/' . $nomor_rawat;
        $ch_rawat = curl_init($url_rawatinap);
        curl_setopt($ch_rawat, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_rawat, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $response_rawat = curl_exec($ch_rawat);
        curl_close($ch_rawat);

        $data_rawat = json_decode($response_rawat, true);
        $rawatinap = $data_rawat['data'] ?? null;

        if (is_string($rawatinap)) {
            $rawatinap = json_decode($rawatinap, true);
        }

        // Step 4: Fetch Tindakan
        $url_tindakan = $this->api_url . '/tindakan/' . $nomor_rawat;
        $ch_tindakan = curl_init($url_tindakan);
        curl_setopt($ch_tindakan, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_tindakan, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $response_tindakan = curl_exec($ch_tindakan);
        curl_close($ch_tindakan);

        $data_tindakan = json_decode($response_tindakan, true);
        $tindakan_list = $data_tindakan['data'] ?? [];

        if (is_string($tindakan_list)) {
            $tindakan_list = json_decode($tindakan_list, true);
        }

        $tindakan_enriched = [];

        $tindakan_enriched = [];

        foreach ($tindakan_list as $tindakan) {
            $kode = $tindakan['tindakan'] ?? '';

            if (!$kode) {
                $tindakan['nama_tindakan'] = 'Kode kosong';
                $tindakan_enriched[] = $tindakan;
                continue;
            }

            $url_jenis = $this->api_url . '/tindakan/jenis2?kode=' . urlencode($kode);

            $ch_jenis = curl_init($url_jenis);
            curl_setopt($ch_jenis, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_jenis, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ]);

            $response_jenis = curl_exec($ch_jenis);
            curl_close($ch_jenis);

            $data_jenis = json_decode($response_jenis, true);
            $nama_tindakan = $kode;

            if (isset($data_jenis['data']) && is_array($data_jenis['data']) && isset($data_jenis['data']['nama_tindakan'])) {
                $nama_tindakan = $data_jenis['data']['nama_tindakan'];
            }

            $tindakan['nama_tindakan'] = $nama_tindakan;
            $tindakan_enriched[] = $tindakan;
        }

        // Step 5: Fetch Obat
        $url_obat = $this->api_url . '/pemberian-obat/' . $nomor_rawat;
        $ch_obat = curl_init($url_obat);
        curl_setopt($ch_obat, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_obat, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $response_obat = curl_exec($ch_obat);
        curl_close($ch_obat);

        $data_obat = json_decode($response_obat, true);
        $obat_list = $data_obat['data'] ?? [];

        if (is_string($obat_list)) {
            $obat_list = json_decode($obat_list, true);
        }

    // dd($tindakan_enriched);
        return view('/admin/rujukan/cetak_surat', [
            'rujukan'      => $rujukan,
            'organisasi'   => $organisasi,
            'rawatinap'    => $rawatinap,
            'tindakanList' => $tindakan_enriched,
            'obatList'     => $obat_list,
        ]);
    }




    
    public function panggilAmbulans($noAmbulans)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . "/ambulans/request";

        $data = [
            'no_ambulans' => $noAmbulans,
            'message'     => 'Permintaan ambulans untuk rujukan keluar'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 201) {
            return redirect()->to(base_url('rujukankeluar'))->with('success', 'Permintaan ambulans berhasil dikirim.');
        } else {
            return $this->renderErrorView($status);
        }
    }

    public function submitFromRawatinapToRujukanKeluar($nomor_rawat)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->back()->with('error', 'Session token missing.');
        }

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

        if (!isset($data['data']) || $data['data'] === null) {
            return redirect()->back()->with('error', 'Rawat inap data not found.');
        }

        $rawatinap = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];
        if (!is_array($rawatinap)) {
            return redirect()->back()->with('error', 'Invalid rawat inap data format.');
        }

        // Step 2: Prefill rujukan keluar data
        $prefill = [
            'nomor_rawat'        => $rawatinap['nomor_rawat'] ?? '',
            'nomor_rm'           => $rawatinap['nomor_rm'] ?? '',
            'nama_pasien'        => $rawatinap['nama_pasien'] ?? '',
            'tanggal_rujuk'      => date('Y-m-d'),
            'jam_rujuk'          => date('H:i:s'),
            'diagnosa_awal'      => $rawatinap['diagnosa_awal'] ?? '',
            'dokter_perujuk'     => $rawatinap['kd_dokter'] ?? '',
            'tempat_rujuk'       => '',
            'kategori_rujuk'     => '',
            'pengantaran'        => '',
            'nomor_rujuk'        => 'R' . date('YmdHis'), // Example autogenerated ID
        ];

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rujukan Keluar', 'rujukankeluar');
        $this->addBreadcrumb('Tambah', 'tambah');

        return view('/admin/rujukan/tambah_rujukan_keluar', [
            'title'      => 'Tambah Rujukan Keluar',
            'breadcrumbs'=> $this->getBreadcrumbs(),
            'prefill'    => $prefill,
        ]);
    }
}