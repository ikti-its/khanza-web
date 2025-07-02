<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RawatInap extends BaseController
{

protected array $breadcrumbs = [];
    protected string $judul = 'Rawat Inap';
    protected string $modul_path = '/rawatinap';
    protected string $api_path = '/rawatinap';
    protected string $kolom_id = 'nomor_rawat';
    protected string $nama_tabel = 'rawat_inap';
    protected array $aksi = [
                            'cetak'    => false,
                            'tindakan' => true,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
    protected array $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rawat'      , 'nomor_rawat'   , 'indeks'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'      , 'indeks'],
                            [1, 'Nama Pasien'      , 'nama_pasien'   , 'nama'],
                            [0, 'Alamat Pasien'    , 'alamat_pasien' , 'teks'],
                            [0, 'Penanggung Jawab' , 'penanggung_jawab'   , 'nama'],
                            [0, 'Hubungan Penanggung Jawab', 'hubungan_pj', 'teks'],
                            [0, 'Jenis Bayar'      , 'jenis_bayar'   , 'status'],
                            [0, 'Kamar'            , 'kamar'         , 'teks'],
                            [0, 'Tarif Kamar'      , 'tarif_kamar'   , 'uang'],
                            [1, 'Diagnosa Awal'    , 'diagnosa_awal' , 'teks'],
                            [0, 'Diagnosa Akhir'   , 'diagnosa_akhir','teks'],
                            [0, 'Tanggal Masuk'    , 'tanggal_masuk' , 'tanggal'],
                            [0, 'Jam Masuk'        , 'jam_masuk'     , 'jam'],
                            [0, 'Tanggal Keluar'   , 'tanggal_keluar', 'tanggal'],
                            [0, 'Jam Keluar'       , 'jam_keluar'    , 'jam'],
                            // [0, 'Total Biaya Kamar'      , 'total_biaya_kamar'   , 'uang'],
                            // [0, 'Total Biaya Tindakan'      , 'total_biaya_tindakan'   , 'uang'],
                            // [0, 'Total Biaya Obat'      , 'total_biaya_obat'   , 'uang'],
                            [0, 'Total Biaya'      , 'total_biaya'   , 'uang'],
                            [0, 'Status Pulang'    , 'status_pulang' , 'status'],
                            [0, 'Lama'             , 'lama_ranap'    , 'teks'],
                            [1, 'Dokter'           , 'dokter_pj'     , 'indeks'],
                            [0, 'Status Bayar'     , 'status_bayar'  , 'status']
                            
                        ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function dataRawatInap()
    {
        $title = 'Data Rawat Inap';

        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap';

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

        $data = json_decode($response, true);
        if (!isset($data['data'])) {
            return $this->renderErrorView(500);
        }

        $rawatinapList = $data['data'];

        foreach ($rawatinapList as &$ri) {
            $tanggalMasuk = $ri['tanggal_masuk'] ?? null;
            $tarifKamar = floatval($ri['tarif_kamar'] ?? 0);

            if ($tanggalMasuk) {
                $tanggalKeluar = ($ri['tanggal_keluar'] === '0001-01-01' || empty($ri['tanggal_keluar']))
                    ? date('Y-m-d') : $ri['tanggal_keluar'];

                try {
                    $start = new \DateTime($tanggalMasuk);
                    $end = new \DateTime($tanggalKeluar);
                    $interval = $start->diff($end);
                    $lamaRanap = max($interval->days, 1);
                } catch (\Exception $e) {
                    $lamaRanap = 1;
                }

                $ri['lama_ranap'] = $lamaRanap;
                $totalBiayaKamar = $lamaRanap * $tarifKamar;
            } else {
                $ri['lama_ranap'] = 0;
                $totalBiayaKamar = 0;
            }

            // ✅ Fetch biaya from tindakan
            $nomorRawat = $ri['nomor_rawat'];
            $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;

            $ct = curl_init($tindakan_url);
            curl_setopt($ct, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ct, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $tindakan_response = curl_exec($ct);
            curl_close($ct);

            $tindakan_data = json_decode($tindakan_response, true);
            $tindakanList = $tindakan_data['data'] ?? [];

            // Normalize to array if single object
            if (isset($tindakanList['nomor_rawat'])) {
                $tindakanList = [$tindakanList];
            }

            $totalBiayaTindakan = 0;
            foreach ($tindakanList as $tindakan) {
                $totalBiayaTindakan += intval($tindakan['biaya'] ?? 0);
            }

            // ✅ Fetch biaya from pemberian obat
            $pemberian_url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
            $cp = curl_init($pemberian_url);
            curl_setopt($cp, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cp, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $pemberian_response = curl_exec($cp);
            curl_close($cp);

            $pemberian_data = json_decode($pemberian_response, true);
            $pemberianList = $pemberian_data['data'] ?? [];
    // dd($pemberianList);
            if (isset($pemberianList['nomor_rawat'])) {
                $pemberianList = [$pemberianList];
            }

            $totalBiayaObat = 0;
            foreach ($pemberianList as $obat) {
                $totalBiayaObat += intval($obat['total'] ?? 0);
            }
    // dd($totalBiayaObat);
            $ri['total_biaya'] = $totalBiayaKamar + $totalBiayaTindakan + $totalBiayaObat;
            $ri['total_biaya_kamar'] = $totalBiayaKamar;
            $ri['total_biaya_tindakan'] = $totalBiayaTindakan;
            $ri['total_biaya_obat'] = $totalBiayaObat;
        }

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rawat Inap', 'rawatinap');
        $breadcrumbs = $this->getBreadcrumbs();
    // dd($rawatinapList);
        return view('/admin/rawatinap/rawatinap_data', [
            'rawatinap_data' => $rawatinapList,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'meta_data' => $data['meta_data'] ?? ['page' => 1, 'size' => 10, 'total' => 1]
        ]);
    }

    public function submitTambahRawatInap()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');

        $postData = [
            'nomor_rawat' => $this->request->getPost('nomor_rawat'),
            'nomor_rm' => $this->request->getPost('nomor_rm'),
            'nama_pasien' => $this->request->getPost('nama_pasien'),
            'alamat_pasien' => $this->request->getPost('alamat_pasien'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'hubungan_pj' => $this->request->getPost('hubungan_pj'),
            'dokter_pj' => $this->request->getPost('dokter_pj'),
            'jenis_bayar' => $this->request->getPost('jenis_bayar'),
            'diagnosa_awal' => $this->request->getPost('diagnosa_awal'),
            'kamar' => $this->request->getPost('kamar'),
            'tarif_kamar' => floatval(str_replace(',', '', $this->request->getPost('tarif_kamar'))),
            'status_kamar' => $this->request->getPost('status_kamar'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'jam_masuk' => $this->request->getPost('jam_masuk') ?: date('H:i:s'),
        ];
    // dd($postData);
        $ch = curl_init($this->api_url . '/rawatinap');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // dd($response);

        if ($httpStatus === 200 || $httpStatus === 201) {
            return redirect()->to('/rawatinap')->with('success', 'Rawat Inap berhasil ditambahkan.');
        }

        return $this->renderErrorView($httpStatus);
    }


    public function tambahRawatInap()
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $title = 'Tambah Rawat Inap';

        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rawat Inap', 'rawatinap');
        $this->addBreadcrumb('Tambah', 'tambah');

        $breadcrumbs = $this->getBreadcrumbs();

        return view('/admin/rawatinap/tambah_rawatinap', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function tambahRawatInapBaru($nomorReg)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }
    
        $token = session()->get('jwt_token');
        $registrasiUrl = $this->api_url . '/registrasi/' . $nomorReg;
    
        $ch = curl_init($registrasiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // dd($response);
    
        if ($httpStatus !== 200) {
            return $this->renderErrorView($httpStatus);
        }
    
        $registrasi = json_decode($response, true)['data'];
    
        $title = 'Tambah Rawat Inap';
        $breadcrumbs = [
            ['title' => 'Rawat Inap', 'url' => '/rawatinap'],
            ['title' => 'Tambah', 'url' => null]
        ];
    
        return view('admin/rawatinap/tambah_rawatinapbaru', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'registrasi' => $registrasi,
            'tanggal_masuk' => date('Y-m-d'),
            'jam_masuk' => date('H:i:s'),
        ]);
    }
    
    public function editRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            return $this->renderErrorView($status);
        }

        $data = json_decode($response, true);
        $ri = $data['data'];

        // Hitung lama ranap dan total biaya kamar
        $tanggalMasuk = $ri['tanggal_masuk'] ?? null;
        $tarifKamar = floatval($ri['tarif_kamar'] ?? 0);
        if ($tanggalMasuk) {
            $tanggalKeluar = ($ri['tanggal_keluar'] === '0001-01-01' || empty($ri['tanggal_keluar']))
                ? date('Y-m-d') : $ri['tanggal_keluar'];

            try {
                $start = new \DateTime($tanggalMasuk);
                $end = new \DateTime($tanggalKeluar);
                $interval = $start->diff($end);
                $lamaRanap = max($interval->days, 1);
            } catch (\Exception $e) {
                $lamaRanap = 1;
            }

            $ri['lama_ranap'] = $lamaRanap;
            $totalBiayaKamar = $lamaRanap * $tarifKamar;
        } else {
            $ri['lama_ranap'] = 0;
            $totalBiayaKamar = 0;
        }

        // Fetch tindakan
        $tindakan_url = $this->api_url . '/tindakan/' . $nomorRawat;
        $ct = curl_init($tindakan_url);
        curl_setopt($ct, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ct, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $tindakan_response = curl_exec($ct);
        curl_close($ct);

        $tindakan_data = json_decode($tindakan_response, true);
        $tindakanList = $tindakan_data['data'] ?? [];
        if (isset($tindakanList['nomor_rawat'])) {
            $tindakanList = [$tindakanList];
        }

        $totalBiayaTindakan = 0;
        foreach ($tindakanList as $t) {
            $totalBiayaTindakan += intval($t['biaya'] ?? 0);
        }

        // Fetch pemberian obat
        $pemberian_url = $this->api_url . '/pemberian-obat/' . $nomorRawat;
        $cp = curl_init($pemberian_url);
        curl_setopt($cp, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cp, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $pemberian_response = curl_exec($cp);
        curl_close($cp);

        $pemberian_data = json_decode($pemberian_response, true);
        $pemberianList = $pemberian_data['data'] ?? [];
        if (isset($pemberianList['nomor_rawat'])) {
            $pemberianList = [$pemberianList];
        }

        $totalBiayaObat = 0;
        foreach ($pemberianList as $obat) {
            $totalBiayaObat += intval($obat['total'] ?? 0);
        }

        $ri['total_biaya_kamar'] = $totalBiayaKamar;
        $ri['total_biaya_tindakan'] = $totalBiayaTindakan;
        $ri['total_biaya_obat'] = $totalBiayaObat;
        $ri['total_biaya'] = $totalBiayaKamar + $totalBiayaTindakan + $totalBiayaObat;

        // Breadcrumbs dan render view
        $this->addBreadcrumb('User', 'user');
        $this->addBreadcrumb('Rawat Inap', 'rawatinap');
        $this->addBreadcrumb('Edit', 'edit');
    // dd($ri);
        return view('/admin/rawatinap/edit_rawatinap', [
            'rawatinap' => $ri,
            'title' => 'Edit Rawat Inap',
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }


    public function submitEditRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $postData = $this->getRawatInapPostData();
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
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            return redirect()->to(base_url('rawatinap'));
        }

        return $response;
    }

    public function hapusRawatInap($nomorRawat)
    {
        if (!session()->has('jwt_token')) {
            return $this->renderErrorView(401);
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . '/rawatinap/' . $nomorRawat;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 204) {
            return redirect()->to(base_url('rawatinap'))->with('success', 'Data rawat inap berhasil dihapus.');
        }

        return $this->renderErrorView($status);
    }

    private function getRawatInapPostData(): array
    {
        // Get raw input
        $jamMasukInput = $this->request->getPost('jam_masuk');
        $jamKeluarInput = $this->request->getPost('jam_keluar');

        // Fallback to current time if input is empty
        $jamMasuk = $jamMasukInput ? date('H:i:s', strtotime($jamMasukInput)) : date('H:i:s');
        $jamKeluar = $jamKeluarInput ? date('H:i:s', strtotime($jamKeluarInput)) : date('H:i:s');
    
        return [
            'nomor_rawat'       => $this->request->getPost('nomor_rawat'),
            'nomor_rm'          => $this->request->getPost('nomor_rm'),
            'nama_pasien'       => $this->request->getPost('nama_pasien'),
            'alamat_pasien'     => $this->request->getPost('alamat_pasien'),
            'penanggung_jawab'  => $this->request->getPost('penanggung_jawab'),
            'hubungan_pj'       => $this->request->getPost('hubungan_pj'),
            'jenis_bayar'       => $this->request->getPost('jenis_bayar'),
            'kamar'             => $this->request->getPost('kamar'),
            'tarif_kamar'       => floatval($this->request->getPost('tarif_kamar')),
            'diagnosa_awal'     => $this->request->getPost('diagnosa_awal'),
            'diagnosa_akhir'    => $this->request->getPost('diagnosa_akhir'),
            'tanggal_masuk'     => $this->request->getPost('tanggal_masuk'),
            'jam_masuk'         => $jamMasuk,
            'tanggal_keluar'    => $this->request->getPost('tanggal_keluar'),
            'jam_keluar'        => $jamKeluar,
            'total_biaya'       => floatval($this->request->getPost('total_biaya')),
            'status_pulang'     => $this->request->getPost('status_pulang'),
            'lama_ranap'        => floatval($this->request->getPost('lama_ranap')),
            'dokter_pj'         => $this->request->getPost('dokter_pj'),
            'status_bayar'      => $this->request->getPost('status_bayar'),
        ];
    }
}