<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class PasienMeninggal_tambah extends BaseController
{

    protected $api_url = 'http://localhost:8080';

    public function tambahPasien()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        return view('admin/masterpasien/tambah_pasienmeninggal', [
            'title' => 'Input Data Pasien Meninggal'
        ]);
    }


    public function simpanTambah()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/masterpasien/tambah-pasien');
        }

        $tgl_lahir_raw = $this->request->getPost('tgl_lahir');
        $tgl_lahir_iso = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso = date('Y-m-d\TH:i:sP');

        $postData = [
            'no_rkm_medis'     => $this->request->getPost('no_rkm_medis'),
            'nm_pasien'        => $this->request->getPost('nm_pasien'),
            'jk'               => $this->request->getPost('jk'),
            'tgl_lahir'        => $this->request->getPost('tgl_lahir'),
            'umur'             => $this->request->getPost('umur'),
            'gol_darah'        => $this->request->getPost('gol_darah'),
            'stts_nikah'       => $this->request->getPost('stts_nikah'),
            'agama'            => $this->request->getPost('agama'),
            'tanggal'          => $this->request->getPost('tanggal'),        // Tanggal meninggal
            'jam'              => $this->request->getPost('jam'),            // Jam meninggal
            'icdx'             => $this->request->getPost('icdx'),           // Penyebab utama
            'icdx_antara1'     => $this->request->getPost('icdx_antara1'),   // Komorbid/penyebab antara 1
            'icdx_antara2'     => $this->request->getPost('icdx_antara2'),   // Komorbid/penyebab antara 2
            'icdx_langsung'    => $this->request->getPost('icdx_langsung'),  // Sebab langsung
            'keterangan'       => $this->request->getPost('keterangan'),     // Catatan tambahan
            'nama_dokter'      => $this->request->getPost('nama_dokter'),    // Nama dokter
            'kode_dokter'      => $this->request->getPost('kode_dokter'),    // Kode dokter
        ];


        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/pasienmeninggal";

        $tanggal_meninggal = $this->request->getPost('tanggal');
        $now = date('Y-m-d');

        if ($tanggal_meninggal > $now) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali. Tanggal kematian tidak valid');
        }

        $tanggal_meninggal = $this->request->getPost('tanggal');
        $jam_meninggal = $this->request->getPost('jam');
        $now = date('Y-m-d');
        $currentTime = date('H:i');

        // Cek jika tanggal meninggal di masa depan
        if ($tanggal_meninggal > $now) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali. Tanggal kematian tidak valid');
        }

        // Cek jika tanggal kematian lebih awal dari tanggal lahir
        $tgl_lahir = $this->request->getPost('tgl_lahir');
        if ($tgl_lahir && strtotime($tanggal_meninggal) < strtotime($tgl_lahir)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal kematian tidak boleh lebih awal dari tanggal lahir.');
        }

        // Jika tanggal meninggal hari ini, validasi jam tidak boleh melebihi jam sekarang
        if ($tanggal_meninggal == $now && $jam_meninggal > $currentTime) {
            return redirect()->back()->withInput()->with('error', 'Jam kematian tidak boleh melebihi waktu saat ini.');
        }



        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response && in_array($http_status, [200, 201])) {
                return redirect()->to('/pasienmeninggal')->with('success', 'Data pasien meninggal telah dicatat');
            } else {
                return redirect()->back()->withInput()->with('error', 'Data pasien tidak dapat disimpan. Pasien sudah pernah dicatat sebagai meninggal.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }

    public function fetchPasienByRM()
    {
        $no_rkm_medis = $this->request->getGet('no_rkm_medis');
        if (!$no_rkm_medis) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No RM kosong']);
        }

        $url = 'http://localhost:8080/v1/masterpasien/' . urlencode($no_rkm_medis);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200 && $response) {
            $data = json_decode($response, true);
            return $this->response->setJSON(['data' => $data['data'] ?? null]);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Pasien tidak ditemukan']);
    }
}
