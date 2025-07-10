<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class KelahiranBayi_tambah extends BaseController
{
    protected $api_url = 'http://localhost:8080';

    public function tambahPasien()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        $lastRM = $this->getLastNoRM(); // contoh: RM000007
        $nextRM = $this->generateNextNoRM($lastRM); // contoh: RM000008

        return view('admin/masterpasien/tambah_kelahiranbayi', [
            'title' => 'Form Tambah Kelahiran Bayi',
            'form_data' => [
                'no_rkm_medis' => $nextRM
            ]
        ]);
    }

    public function simpanTambah()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/kelahiranbayi/tambah-pasien');
        }

        $tgl_lahir_raw   = $this->request->getPost('tgl_lahir');
        $tgl_daftar_raw  = $this->request->getPost('tgl_daftar');

        $tgl_lahir_iso   = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso  = $tgl_daftar_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_daftar_raw)) : null;

        $postData = [
            // String
            'no_rkm_medis'       => (string) $this->request->getPost('no_rkm_medis'),
            'nm_pasien'          => (string) $this->request->getPost('nm_pasien'),
            'jk'                 => (string) $this->request->getPost('jk'),
            'tmp_lahir'          => (string) $this->request->getPost('tmp_lahir'),
            'tgl_lahir'          => $tgl_lahir_iso,
            'jam'                => (string) $this->request->getPost('jam'),
            'umur'               => (string) $this->request->getPost('umur'),
            'tgl_daftar'         => $tgl_daftar_iso,
            'nm_ibu'             => (string) $this->request->getPost('nm_ibu'),
            'umur_ibu'           => (string) $this->request->getPost('umur_ibu'),
            'nm_ayah'            => (string) $this->request->getPost('nm_ayah'),
            'umur_ayah'          => (string) $this->request->getPost('umur_ayah'),
            'alamat'             => (string) $this->request->getPost('alamat'),
            'proses_lahir'       => (string) $this->request->getPost('proses_lahir'),
            'keterangan'         => (string) $this->request->getPost('keterangan'),
            'diagnosa'           => (string) $this->request->getPost('diagnosa'),
            'penyulit_kehamilan' => (string) $this->request->getPost('penyulit_kehamilan'),
            'ketuban'            => (string) $this->request->getPost('ketuban'),
            'penolong'           => (string) $this->request->getPost('penolong'),
            'no_skl'             => (string) $this->request->getPost('no_skl'),
            'resusitas'          => (string) $this->request->getPost('resusitas'),
            'obat'               => (string) $this->request->getPost('obat'),
            'mikasi'             => (string) $this->request->getPost('mikasi'),
            'mikonium'           => (string) $this->request->getPost('mikonium'),

            // Integer
            'bb'           => (int) $this->request->getPost('bb'),
            'kelahiran_ke' => (int) $this->request->getPost('kelahiran_ke'),
            'gravida'      => (int) $this->request->getPost('gravida'),
            'para'         => (int) $this->request->getPost('para'),
            'abortus'      => (int) $this->request->getPost('abortus'),

            'f1'  => (int) $this->request->getPost('f1'),
            'u1'  => (int) $this->request->getPost('u1'),
            't1'  => (int) $this->request->getPost('t1'),
            'r1'  => (int) $this->request->getPost('r1'),
            'w1'  => (int) $this->request->getPost('w1'),
            'n1'  => (int) $this->request->getPost('n1'),

            'f5'  => (int) $this->request->getPost('f5'),
            'u5'  => (int) $this->request->getPost('u5'),
            't5'  => (int) $this->request->getPost('t5'),
            'r5'  => (int) $this->request->getPost('r5'),
            'w5'  => (int) $this->request->getPost('w5'),
            'n5'  => (int) $this->request->getPost('n5'),

            'f10' => (int) $this->request->getPost('f10'),
            'u10' => (int) $this->request->getPost('u10'),
            't10' => (int) $this->request->getPost('t10'),
            'r10' => (int) $this->request->getPost('r10'),
            'w10' => (int) $this->request->getPost('w10'),
            'n10' => (int) $this->request->getPost('n10'),

            // Float
            'pb'        => (float) str_replace(',', '.', $this->request->getPost('pb')),
            'lk_perut'  => (float) str_replace(',', '.', $this->request->getPost('lk_perut')),
            'lk_kepala' => (float) str_replace(',', '.', $this->request->getPost('lk_kepala')),
            'lk_dada'   => (float) str_replace(',', '.', $this->request->getPost('lk_dada')),
        ];


        $jsonPayload = json_encode($postData);
        $url = $this->api_url . "/kelahiranbayi";

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

                $pasienData = [
                    'no_rkm_medis' => $this->request->getPost('no_rkm_medis'),
                    'nm_pasien'    => $this->request->getPost('nm_pasien'),
                    'jk'           => $this->request->getPost('jk'),
                    'tmp_lahir'    => $this->request->getPost('tmp_lahir'),
                    'tgl_lahir'    => $tgl_lahir_iso,
                    'nm_ibu'       => $this->request->getPost('nm_ibu'),
                    'alamat'       => $this->request->getPost('alamat'),
                    'umur'         => $this->request->getPost('umur'),
                    'tgl_daftar'   => $tgl_daftar_iso,
                ];

                // Tambahkan semua field opsional kosong agar payload lengkap
                $opsional = [
                    'no_ktp',
                    'gol_darah',
                    'pekerjaan',
                    'stts_nikah',
                    'agama',
                    'no_tlp',
                    'pnd',
                    'keluarga',
                    'namakeluarga',
                    'kd_pj',
                    'no_peserta',
                    'kd_kel',
                    'kd_kec',
                    'kd_kab',
                    'pekerjaanpj',
                    'alamatpj',
                    'kelurahanpj',
                    'kecamatanpj',
                    'kabupatenpj',
                    'suku_bangsa',
                    'bahasa_pasien',
                    'perusahaan_pasien',
                    'nip',
                    'email',
                    'cacat_fisik',
                    'kd_prop',
                    'propinsipj'
                ];
                foreach ($opsional as $field) {
                    $pasienData[$field] = "";
                }

                $pasienPayload = json_encode($pasienData);
                $urlPasien = $this->api_url . "/pasien";

                $ch2 = curl_init($urlPasien);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $pasienPayload);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ]);

                $responsePasien = curl_exec($ch2);
                $http_status_pasien = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                curl_close($ch2);

                // Optional: log atau tangani error jika $http_status_pasien != 201
                return redirect()->to('/kelahiranbayi')->with('success', 'Data kelahiran bayi berhasil ditambahkan.');
            } else {
                // Coba decode response biar tau error detailnya
                $responseData = json_decode($response, true);
                $errorMsg = print_r($responseData, true); // cetak semua isi response

                return redirect()->back()->withInput()->with('error', "Gagal menambahkan data. HTTP: $http_status - $errorMsg");
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
            return 'RM000001';
        }

        $lastNum = (int) $match[1];
        $nextNum = $lastNum + 1;
        return 'RM' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }
}
