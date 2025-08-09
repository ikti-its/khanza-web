<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class PasienMeninggalForm extends BaseController
{

    protected $api_url = 'http://localhost:8080';

    public function tampilTambah()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        return view('admin/masterpasien/form_pasienmeninggal', [
            'title' => 'Input Data Pasien Meninggal'
        ]);
    }


    public function simpanTambah()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/masterpasien/tambah-pasien');
        }

        $postData = [
            'no_rkm_medis'     => $this->request->getPost('no_rkm_medis'),
            'nm_pasien'        => $this->request->getPost('nm_pasien'),
            'jk'               => $this->request->getPost('jk'),
            'tgl_lahir'        => $this->request->getPost('tgl_lahir'),
            'umur'             => $this->request->getPost('umur'),
            'gol_darah'        => $this->request->getPost('gol_darah'),
            'stts_nikah'       => $this->request->getPost('stts_nikah'),
            'agama'            => $this->request->getPost('agama'),
            'tanggal'          => $this->request->getPost('tanggal'),
            'jam'              => $this->request->getPost('jam'),
            'icdx'             => $this->request->getPost('icdx'),
            'icdx_antara1'     => $this->request->getPost('icdx_antara1'),
            'icdx_antara2'     => $this->request->getPost('icdx_antara2'),
            'icdx_langsung'    => $this->request->getPost('icdx_langsung'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'nama_dokter'      => $this->request->getPost('nama_dokter'),
            'kode_dokter'      => $this->request->getPost('kode_dokter'),
        ];

        $no_rkm_medis = $postData['no_rkm_medis'];
        $tanggal_meninggal = $postData['tanggal'];
        $jam_meninggal = $postData['jam'];
        $tgl_lahir = $postData['tgl_lahir'];
        $now = date('Y-m-d');
        $currentTime = date('H:i');

        if ($tanggal_meninggal > $now) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali. Tanggal kematian tidak valid');
        }

        if ($tgl_lahir && strtotime($tanggal_meninggal) < strtotime($tgl_lahir)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal kematian tidak boleh lebih awal dari tanggal lahir.');
        }

        if ($tanggal_meninggal == $now && $jam_meninggal > $currentTime) {
            return redirect()->back()->withInput()->with('error', 'Jam kematian tidak boleh melebihi waktu saat ini.');
        }

        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/pasienmeninggal";

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            // Step 1: Simpan data ke pasienmeninggal
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
                // Step 2: PATCH status pasien di masterpasien
                $updatePayload = json_encode(['stts_pasien' => 'Meninggal']);
                $updateUrl = $this->api_url . "/masterpasien/status/" . $no_rkm_medis;

                $ch2 = curl_init($updateUrl);
                curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $updatePayload);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ]);
                $response2 = curl_exec($ch2);
                curl_close($ch2);

                return redirect()->to('/pasienmeninggal')->with('success', 'Data pasien meninggal telah dicatat dan status pasien diperbarui.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Data pasien tidak dapat disimpan. Pasien sudah pernah dicatat sebagai meninggal.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }

    public function tampilUbah($no_rkm_medis)
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        $token = session()->get('jwt_token');
        $url = $this->api_url . "/pasienmeninggal/" . $no_rkm_medis;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status === 200 && $response) {
            $result = json_decode($response, true);
            $pasien = isset($result['data']) ? $result['data'] : $result;

            // Format tanggal lahir
            if (!empty($pasien['tgl_lahir'])) {
                $pasien['tgl_lahir'] = date('Y-m-d', strtotime($pasien['tgl_lahir']));
            }

            // Format tanggal meninggal
            if (!empty($pasien['tanggal'])) {
                $pasien['tanggal'] = date('Y-m-d', strtotime($pasien['tanggal']));
            }

            // Format jam meninggal ⬅️ tambahkan ini
            if (!empty($pasien['jam'])) {
                $pasien['jam'] = date('H:i', strtotime($pasien['jam']));
            }


            return view('admin/masterpasien/form_pasienmeninggal', [
                'title'         => 'Ubah Data Pasien Meninggal',
                'no_rkm_medis'  => $no_rkm_medis,
                'pasien'        => $pasien,
                'mode'          => 'ubah'
            ]);
        } else {
            return redirect()->to('/pasienmeninggal')->with('error', 'Gagal mengambil data pasien meninggal.');
        }
    }


    public function simpanUbah($no_rkm_medis)
    {
        if (!$this->request->getPost()) {
            return redirect()->to("/pasienmeninggal/ubah-pasien/$no_rkm_medis");
        }

        $tgl_lahir_raw = $this->request->getPost('tgl_lahir');
        $tgl_lahir_iso = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tanggal_meninggal = $this->request->getPost('tanggal');
        $jam_meninggal = $this->request->getPost('jam');
        $now = date('Y-m-d');
        $currentTime = date('H:i');

        // Validasi
        if ($tanggal_meninggal > $now) {
            return redirect()->back()->withInput()->with('error', 'Tanggal kematian tidak valid.');
        }

        if ($tgl_lahir_raw && strtotime($tanggal_meninggal) < strtotime($tgl_lahir_raw)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal kematian tidak boleh lebih awal dari tanggal lahir.');
        }

        $postData = [
            'no_rkm_medis'     => $no_rkm_medis,
            'nm_pasien'        => $this->request->getPost('nm_pasien'),
            'jk'               => $this->request->getPost('jk'),
            'tgl_lahir'        => $this->request->getPost('tgl_lahir'),
            'umur'             => $this->request->getPost('umur'),
            'gol_darah'        => $this->request->getPost('gol_darah'),
            'stts_nikah'       => $this->request->getPost('stts_nikah'),
            'agama'            => $this->request->getPost('agama'),
            'tanggal'          => $tanggal_meninggal,
            'jam'              => $jam_meninggal,
            'icdx'             => $this->request->getPost('icdx'),
            'icdx_antara1'     => $this->request->getPost('icdx_antara1'),
            'icdx_antara2'     => $this->request->getPost('icdx_antara2'),
            'icdx_langsung'    => $this->request->getPost('icdx_langsung'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'nama_dokter'      => $this->request->getPost('nama_dokter'),
            'kode_dokter'      => $this->request->getPost('kode_dokter'),
        ];

        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/pasienmeninggal/" . $no_rkm_medis;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response && in_array($http_status, [200, 204])) {
                return redirect()->to('/pasienmeninggal')->with('success', 'Data pasien meninggal berhasil diperbarui.');
            } else {
                return redirect()->back()->withInput()->with('error', "Gagal memperbarui data. HTTP: $http_status");
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }
}
