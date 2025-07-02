<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class MasterPasien_tambah extends BaseController
{
    public function tambahPasien()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
        $lastRM = $this->getLastNoRM(); // e.g. RM000007
        $nextRM = $this->generateNextNoRM($lastRM); // e.g. RM000008

        return view('admin/masterpasien/tambah_masterpasien', [
            'title' => 'Tambah Pasien Manual',
            'no_rkm_medis' => $nextRM
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

    private function getLastNoRM()
    {
        if (!session()->has('jwt_token')) return null;

        $token = session()->get('jwt_token');

        $ch = curl_init($this->api_url . "/masterpasien?limit=1&sort=desc");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            $lastData = $data['data'][0]['no_rkm_medis'] ?? null;
            return $lastData;
        }

        return null;
    }

    private function generateNextNoRM($lastNo)
    {
        if (!$lastNo || !preg_match('/^RM(\d{6})$/', $lastNo, $match)) {
            return 'RM000001'; // Default kalau kosong / tidak cocok format
        }

        $lastNum = (int) $match[1];
        $nextNum = $lastNum + 1;
        return 'RM' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }
}
