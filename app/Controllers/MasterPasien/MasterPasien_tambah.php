<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;
use App\Services\NomorGeneratorService;

class MasterPasien_tambah extends BaseController
{
    public function tambahPasien()
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


        return view('admin/masterpasien/tambah_masterpasien', [
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

        $postData = [
            'no_rkm_medis' => $this->request->getPost('no_rkm_medis'),
            'nm_pasien' => $this->request->getPost('nm_pasien'),
            'no_ktp' => $this->request->getPost('no_ktp'),
            'jk' => $this->request->getPost('jk'),
            'tmp_lahir' => $this->request->getPost('tmp_lahir'),
            'tgl_lahir' => $tgl_lahir_iso,
            'nm_ibu' => $this->request->getPost('nm_ibu'),
            'alamat' => $this->request->getPost('alamat'),
            'gol_darah' => $this->request->getPost('gol_darah'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'stts_nikah' => $this->request->getPost('stts_nikah'),
            'agama' => $this->request->getPost('agama'),
            'tgl_daftar' => $tgl_daftar_iso,
            'no_tlp' => $this->request->getPost('no_tlp'),
            'umur' => $this->request->getPost('umur'),
            'pnd' => $this->request->getPost('pnd'),
            'keluarga' => $this->request->getPost('keluarga'),
            'namakeluarga' => $this->request->getPost('namakeluarga'),
            'kd_pj' => $this->request->getPost('kd_pj'),
            'no_peserta' => $this->request->getPost('no_peserta'),
            'kd_kel' => $this->request->getPost('kd_kel'),
            'kd_kec' => $this->request->getPost('kd_kec'),
            'kd_kab' => $this->request->getPost('kd_kab'),
            'pekerjaanpj' => $this->request->getPost('pekerjaanpj'),
            'alamatpj' => $this->request->getPost('alamatpj'),
            'kelurahanpj' => $this->request->getPost('kelurahanpj'),
            'kecamatanpj' => $this->request->getPost('kecamatanpj'),
            'kabupatenpj' => $this->request->getPost('kabupatenpj'),
            'perusahaan_pasien' => $this->request->getPost('perusahaan_pasien'),
            'suku_bangsa' => $this->request->getPost('suku_bangsa'),
            'bahasa_pasien' => $this->request->getPost('bahasa_pasien'),
            'cacat_fisik' => $this->request->getPost('cacat_fisik'),
            'email' => $this->request->getPost('email'),
            'nip' => $this->request->getPost('nip'),
            'kd_prop' => $this->request->getPost('kd_prop'),
            'propinsipj' => $this->request->getPost('propinsipj'),
        ];

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
}
