<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;
use App\Services\NomorGeneratorService;

class KelahiranBayiForm extends BaseController
{
    protected $api_url = 'http://localhost:8080';

    public function tampilTambah()
    {
        if (!session()->has('jwt_token')) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        helper('autonomor_helper');

        // 🔁 Panggil service
        $nomorGen = new \App\Services\NomorGeneratorService();

        // 🔁 Generate No. RM
        $lastRM = $nomorGen->getLastNoRM();
        $nextRM = generateNextNoRM($lastRM);

        // 🔁 Generate No. SKL
        $tgl_lahir = date('Y-m-d');
        $bulan = date('m', strtotime($tgl_lahir));
        $tahun = date('Y', strtotime($tgl_lahir));
        $lastSKL = $nomorGen->getLastSKL($bulan, $tahun);
        $nextSKL = generateNextSKL($lastSKL, $tgl_lahir);

        return view('admin/masterpasien/form_kelahiranbayi', [
            'title' => 'Form Tambah Kelahiran Bayi',
            'bayi' => [
                'no_rkm_medis' => $nextRM,
                'no_skl' => $nextSKL
            ]
        ]);
    }

    public function simpanTambah()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/kelahiranbayi/tambah-pasien');
        }

        // Ambil data form
        $tgl_lahir_raw   = $this->request->getPost('tgl_lahir');
        $tgl_daftar_raw  = $this->request->getPost('tgl_daftar');

        // Format ISO dan validasi tanggal
        $tgl_lahir_iso   = $tgl_lahir_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso  = $tgl_daftar_raw ? date('Y-m-d\TH:i:sP', strtotime($tgl_daftar_raw)) : null;

        $now = date('Y-m-d');
        $jam_lahir = $this->request->getPost('jam');
        $currentTime = date('H:i');

        if ($tgl_lahir_raw > $now || $tgl_daftar_raw > $now || ($tgl_daftar_raw < $tgl_lahir_raw)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal tidak valid.');
        }

        if ($tgl_lahir_raw == $now && strtotime($jam_lahir) > strtotime($currentTime)) {
            return redirect()->back()->withInput()->with('error', 'Jam lahir tidak boleh melebihi waktu sekarang.');
        }

        // Validasi nama huruf
        foreach (['nm_pasien', 'nm_ibu', 'nm_ayah'] as $field) {
            if (!preg_match('/^[a-zA-Z\s\'-]+$/u', $this->request->getPost($field))) {
                return redirect()->back()->withInput()->with('error', "$field hanya boleh huruf, spasi, petik, strip.");
            }
        }

        // Validasi angka > 0
        $angkaPositif = ['bb', 'pb', 'lk_perut', 'lk_kepala', 'lk_dada', 'umur', 'umur_ibu', 'umur_ayah', 'kelahiran_ke', 'gravida', 'para', 'abortus'];
        foreach ($angkaPositif as $f) {
            $v = (float) str_replace(',', '.', $this->request->getPost($f));
            if ($v <= 0) return redirect()->back()->withInput()->with('error', "$f harus lebih dari 0");
        }

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
            'no_rm_ibu'          => (string) $this->request->getPost('no_rm_ibu'),
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

        $url = $this->api_url . "/kelahiranbayi";

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $ch = curl_init($url);

            // Log isi payload
            // log_message('debug', 'Payload simpanUbah: ' . json_encode($postData));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // dd($response, $http_status);

            if ($response && in_array($http_status, [200, 201])) {
                return redirect()->to('/kelahiranbayi')->with('success', 'Bayi berhasil ditambahkan.');
            } else {
                $decoded = json_decode($response, true);
                $apiMessage = $decoded['message'] ?? $decoded['error'] ?? 'Gagal simpan data bayi.';

                return redirect()->back()->withInput()->with('error', "HTTP $http_status - $apiMessage");
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
        $url = $this->api_url . "/kelahiranbayi/" . $no_rkm_medis;

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
            $bayi = isset($result['data']) ? $result['data'] : $result;

            if (!empty($bayi['tgl_lahir'])) {
                $tgl = date_create($bayi['tgl_lahir']);
                $bayi['tgl_lahir'] = $tgl ? $tgl->format('Y-m-d') : '';
            }

            if (!empty($bayi['tgl_daftar'])) {
                $tgl = date_create($bayi['tgl_daftar']);
                $bayi['tgl_daftar'] = $tgl ? $tgl->format('Y-m-d') : '';
            }

            // Format jam meninggal ⬅️ tambahkan ini
            if (!empty($bayi['jam'])) {
                $bayi['jam'] = date('H:i', strtotime($bayi['jam']));
            }

            return view('admin/masterpasien/form_kelahiranbayi', [
                'title'        => 'Ubah Data Kelahiran Bayi',
                'bayi'         => $bayi,
                'no_rkm_medis' => $no_rkm_medis,
                'mode'         => 'ubah'
            ]);
        } else {
            return redirect()->to('/kelahiranbayi')->with('error', 'Gagal mengambil data bayi.');
        }
    }

    public function simpanUbah($no_rkm_medis)
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/kelahiranbayi/ubah-pasien/' . $no_rkm_medis);
        }

        $tgl_lahir_raw   = $this->request->getPost('tgl_lahir');
        $tgl_daftar_raw  = $this->request->getPost('tgl_daftar');
        $tgl_lahir_iso   = $tgl_lahir_raw ? date('Y-m-d', strtotime($tgl_lahir_raw)) : null;
        $tgl_daftar_iso  = $tgl_daftar_raw ? date('Y-m-d', strtotime($tgl_daftar_raw)) : null;

        $now = date('Y-m-d');
        $jam_lahir = $this->request->getPost('jam');
        $currentTime = date('H:i');

        if ($tgl_lahir_raw > $now || $tgl_daftar_raw > $now || ($tgl_daftar_raw < $tgl_lahir_raw)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal tidak valid.');
        }

        if ($tgl_lahir_raw == $now && strtotime($jam_lahir) > strtotime($currentTime)) {
            return redirect()->back()->withInput()->with('error', 'Jam lahir tidak boleh melebihi waktu sekarang.');
        }

        foreach (['nm_pasien', 'nm_ibu', 'nm_ayah'] as $field) {
            if (!preg_match('/^[a-zA-Z\s\'-]+$/u', $this->request->getPost($field))) {
                return redirect()->back()->withInput()->with('error', "$field hanya boleh huruf, spasi, petik, strip.");
            }
        }

        $angkaPositif = ['bb', 'pb', 'lk_perut', 'lk_kepala', 'lk_dada', 'umur', 'umur_ibu', 'umur_ayah', 'kelahiran_ke', 'gravida', 'para', 'abortus'];
        foreach ($angkaPositif as $f) {
            $v = (float) str_replace(',', '.', $this->request->getPost($f));
            if ($v <= 0) return redirect()->back()->withInput()->with('error', "$f harus lebih dari 0");
        }

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
            'no_rm_ibu'          => (string) $this->request->getPost('no_rm_ibu'),
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

        $url = $this->api_url . "/kelahiranbayi/" . $no_rkm_medis;

        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $ch = curl_init($url);

            // Log isi payload
            // log_message('debug', 'Payload simpanUbah: ' . json_encode($postData));

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ]);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // dd($response, $http_status);

            if ($response && in_array($http_status, [200, 204])) {
                return redirect()->to('/kelahiranbayi')->with('success', 'Data bayi berhasil diperbarui.');
            } else {
                $decoded = json_decode($response, true);
                $apiMessage = $decoded['message'] ?? $decoded['error'] ?? 'Gagal update data bayi.';
                return redirect()->back()->withInput()->with('error', "HTTP $http_status - $apiMessage");
            }
        } else {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }
    }
}
