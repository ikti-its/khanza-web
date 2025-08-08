<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;
use App\Services\NomorGeneratorService;

class MasterPasienForm extends BaseController
{
    public function tampilTambah()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        helper('autonomor_helper'); // panggil helper

        // 🔁 Panggil service
        $nomorGen = new \App\Services\NomorGeneratorService();

        // 🔁 Generate No. RM
        $lastRM = $nomorGen->getLastNoRM();
        $nextRM = generateNextNoRM($lastRM);


        return view('admin/masterpasien/form_masterpasien', [
            'title' => 'Input Data Pasien',
            'no_rkm_medis' => $nextRM
        ]);
    }

    public function simpanTambah()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/masterpasien/tambah-pasien');
        }

        $tgl_lahir_raw   = $this->request->getPost('tgl_lahir');
        $tgl_daftar_raw  = $this->request->getPost('tgl_daftar');

        $tgl_lahir_iso   = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso  = $tgl_daftar_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_daftar_raw)) : null;

        $no_asuransi = $this->request->getPost('no_asuransi');
        if (empty($no_asuransi)) {
            $no_asuransi = '-';
        }

        $postData = [
            'no_rkm_medis' => $this->request->getPost('no_rkm_medis'),
            'nm_pasien' => $this->request->getPost('nm_pasien'),
            'no_ktp' => $this->request->getPost('no_ktp') ?: '-',
            'jk' => $this->request->getPost('jk'),
            'tmp_lahir' => $this->request->getPost('tmp_lahir'),
            'tgl_lahir' => $tgl_lahir_iso,
            'nm_ibu' => $this->request->getPost('nm_ibu'),
            'alamat' => $this->request->getPost('alamat') ?: '-',
            'gol_darah' => $this->request->getPost('gol_darah'),
            'pekerjaan' => $this->request->getPost('pekerjaan') ?: '-',
            'stts_nikah' => $this->request->getPost('stts_nikah'),
            'agama' => $this->request->getPost('agama'),
            'tgl_daftar' => $tgl_daftar_iso,
            'no_tlp' => $this->request->getPost('no_tlp') ?: '-',
            'umur' => $this->request->getPost('umur'),
            'pnd' => $this->request->getPost('pnd'),
            'asuransi' => $this->request->getPost('asuransi'),
            'no_asuransi' => $this->request->getPost('no_asuransi') ?: '-',
            'kd_kel' => $this->request->getPost('kd_kel'),
            'kd_kec' => $this->request->getPost('kd_kec'),
            'kd_kab' => $this->request->getPost('kd_kab'),
            'perusahaan_pasien' => $this->request->getPost('perusahaan_pasien') ?: '-',
            'suku_bangsa' => $this->request->getPost('suku_bangsa'),
            'bahasa_pasien' => $this->request->getPost('bahasa_pasien'),
            'cacat_fisik' => $this->request->getPost('cacat_fisik'),
            'email' => $this->request->getPost('email') ?: '-',
            'nip' => $this->request->getPost('nip') ?: '-',
            'kd_prop' => $this->request->getPost('kd_prop'),
            'stts_pasien' => $this->request->getPost('stts_pasien'),
        ];

        //Validasi Tanggal Lahir
        $tgl_lahir = $this->request->getPost('tgl_lahir');
        $now = date('Y-m-d');
        $batas_terlalu_tua = date('Y-m-d', strtotime('-110 years'));
        $batas_terlalu_muda = date('Y-m-d', strtotime('-12 months'));

        if ($tgl_lahir > $now) {
            return redirect()->back()->withInput()->with('error', 'Tanggal lahir tidak boleh di masa depan.');
        }

        if ($tgl_lahir < $batas_terlalu_tua) {
            return redirect()->back()->withInput()->with('error', 'Tanggal lahir terlalu lama. Maksimal umur 110 tahun.');
        }

        if ($tgl_lahir > $batas_terlalu_muda) {
            return redirect()->back()->withInput()->with('error', 'Pasien terlalu muda. Silakan gunakan form kelahiran bayi.');
        }

        //Validasi Email
        $email = $this->request->getPost('email');
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email tidak valid.');
        }

        //Validasi Notelp
        $no_tlp = $this->request->getPost('no_tlp');
        if (!empty($no_tlp) && !preg_match('/^(\\+62|08)[0-9]{8,13}$/', $no_tlp)) {
            return redirect()->back()->withInput()->with('error', 'Nomor telepon tidak valid. Gunakan format 08xxx atau +62xxx.');
        }

        //Validasi KTP
        $no_ktp = $this->request->getPost('no_ktp');
        if (!empty($no_ktp) && !preg_match('/^[0-9]{16}$/', $no_ktp)) {
            return redirect()->back()->withInput()->with('error', 'No. KTP harus 16 digit angka.');
        }


        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/masterpasien";

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
                return redirect()->to('/masterpasien')->with('success', 'Data pasien berhasil ditambahkan.');
            } else {
                return redirect()->back()->withInput()->with('error', "Gagal menambahkan pasien. HTTP: $http_status");
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
        $url = $this->api_url . "/masterpasien/" . $no_rkm_medis;

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

            // Antisipasi: jika data pasien ada di dalam key 'data' (sesuai response API kamu)
            $pasien = isset($result['data']) ? $result['data'] : $result;

            // Format tanggal lahir
            if (!empty($pasien['tgl_lahir'])) {
                $tgl = date_create($pasien['tgl_lahir']);
                $pasien['tgl_lahir'] = $tgl ? $tgl->format('Y-m-d') : '';
            }

            // Format tanggal daftar
            if (!empty($pasien['tgl_daftar'])) {
                $tgl = date_create($pasien['tgl_daftar']);
                $pasien['tgl_daftar'] = $tgl ? $tgl->format('Y-m-d') : '';
            }

            //dd($pasien);

            return view('admin/masterpasien/form_masterpasien', [
                'title'         => 'Ubah Data Pasien',
                'no_rkm_medis'  => $no_rkm_medis,
                'pasien'        => $pasien,         // ⬅️ pakai key 'pasien' yang lebih intuitif
                'mode'          => 'ubah'
            ]);
        } else {
            return redirect()->to('/masterpasien')->with('error', 'Gagal mengambil data pasien.');
        }
    }


    public function simpanUbah($no_rkm_medis)
    {
        //dd($this->request->getPost());

        if (!$this->request->getPost()) {
            return redirect()->to('/masterpasien/ubah-pasien/' . $no_rkm_medis);
        }

        $tgl_lahir_raw   = $this->request->getPost('tgl_lahir');
        $tgl_daftar_raw  = $this->request->getPost('tgl_daftar');

        $tgl_lahir_iso   = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso  = $tgl_daftar_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_daftar_raw)) : null;

        $postData = [
            'nm_pasien' => $this->request->getPost('nm_pasien'),
            'no_ktp' => $this->request->getPost('no_ktp') ?: '-',
            'jk' => $this->request->getPost('jk'),
            'tmp_lahir' => $this->request->getPost('tmp_lahir'),
            'tgl_lahir' => $tgl_lahir_iso,
            'nm_ibu' => $this->request->getPost('nm_ibu'),
            'alamat' => $this->request->getPost('alamat') ?: '-',
            'gol_darah' => $this->request->getPost('gol_darah'),
            'pekerjaan' => $this->request->getPost('pekerjaan') ?: '-',
            'stts_nikah' => $this->request->getPost('stts_nikah'),
            'agama' => $this->request->getPost('agama'),
            'tgl_daftar' => $tgl_daftar_iso,
            'no_tlp' => $this->request->getPost('no_tlp') ?: '-',
            'umur' => $this->request->getPost('umur'),
            'pnd' => $this->request->getPost('pnd'),
            'asuransi' => $this->request->getPost('asuransi'),
            'no_asuransi' => $this->request->getPost('no_asuransi') ?: '-',
            'kd_kel' => $this->request->getPost('kd_kel'),
            'kd_kec' => $this->request->getPost('kd_kec'),
            'kd_kab' => $this->request->getPost('kd_kab'),
            'perusahaan_pasien' => $this->request->getPost('perusahaan_pasien') ?: '-',
            'suku_bangsa' => $this->request->getPost('suku_bangsa'),
            'bahasa_pasien' => $this->request->getPost('bahasa_pasien'),
            'cacat_fisik' => $this->request->getPost('cacat_fisik'),
            'email' => $this->request->getPost('email') ?: '-',
            'nip' => $this->request->getPost('nip') ?: '-',
            'kd_prop' => $this->request->getPost('kd_prop'),
            'stts_pasien' => $this->request->getPost('stts_pasien'),
        ];

        // Validasi sama persis seperti tambah
        $tgl_lahir = $this->request->getPost('tgl_lahir');
        $now = date('Y-m-d');
        $batas_terlalu_tua = date('Y-m-d', strtotime('-110 years'));
        $batas_terlalu_muda = date('Y-m-d', strtotime('-12 months'));

        if ($tgl_lahir > $now) {
            return redirect()->back()->withInput()->with('error', 'Tanggal lahir tidak boleh di masa depan.');
        }

        $jsonPayload = json_encode($postData);
        //dd($jsonPayload);
        $url = $this->api_url . "/masterpasien/" . $no_rkm_medis;

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
            $error = curl_error($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // dd([
            //     'http_status' => $http_status,
            //     'curl_error' => $error,
            //     'api_response' => $response
            // ]);

            if ($response && in_array($http_status, [200, 204])) {
                return redirect()->to('/masterpasien')->with('success', 'Data pasien berhasil diperbarui.');
            } else {
                return redirect()->back()->withInput()->with('error', "Gagal memperbarui pasien. HTTP: $http_status");
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }
}
