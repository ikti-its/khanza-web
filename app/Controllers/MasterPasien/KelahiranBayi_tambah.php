<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;
use App\Services\NomorGeneratorService;


class KelahiranBayi_tambah extends BaseController
{
    protected $api_url = 'http://localhost:8080';

    public function tambahPasien()
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

        return view('admin/masterpasien/tambah_kelahiranbayi', [
            'title' => 'Form Tambah Kelahiran Bayi',
            'form_data' => [
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

        $token = session()->get('jwt_token');
        $api_url = $this->api_url;

        // Kirim ke kelahiranbayi API
        $response1 = $this->sendToAPI("$api_url/kelahiranbayi", $postData, $token);
        if (!$response1['success']) {
            return redirect()->back()->withInput()->with('error', "Gagal simpan ke kelahiran bayi: {$response1['message']}");
        }

        // Siapkan data ke masterpasien
        $pasienData = [
            'no_rkm_medis' => $postData['no_rkm_medis'],
            'nm_pasien'    => $postData['nm_pasien'],
            'jk'           => $postData['jk'],
            'tmp_lahir'    => $postData['tmp_lahir'],
            'tgl_lahir'    => $tgl_lahir_iso,
            'nm_ibu'       => $postData['nm_ibu'],
            'alamat'       => $postData['alamat'],
            'umur'         => $postData['umur'],
            'tgl_daftar'   => $tgl_daftar_iso,
        ];

        // Tambahkan semua field opsional default kosong
        $opsional = [
            'no_ktp',
            'gol_darah',
            'pekerjaan',
            'stts_nikah',
            'agama',
            'no_tlp',
            'pnd',
            'kd_pj',
            'no_peserta',
            'kd_kel',
            'kd_kec',
            'kd_kab',
            'pekerjaanpj',
            'suku_bangsa',
            'bahasa_pasien',
            'perusahaan_pasien',
            'nip',
            'email',
            'cacat_fisik',
            'kd_prop',
            'keluarga' // ← tambah ini
        ];
        foreach ($opsional as $field) {
            $pasienData[$field] = '';
        }

        $response2 = $this->sendToAPI("$api_url/masterpasien", $pasienData, $token);
        if (!$response2['success']) {
            return redirect()->to('/kelahiranbayi')->with('warning', "Bayi berhasil ditambahkan, tapi gagal ke masterpasien: {$response2['message']}");
        }

        return redirect()->to('/kelahiranbayi')->with('success', 'Bayi & pasien berhasil ditambahkan.');
    }

    // Fungsi reusable untuk kirim ke API
    private function sendToAPI($url, $data, $token)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Debug logging: tulis hasil ke log CodeIgniter
        log_message('error', "[API DEBUG] POST to $url status: $status response: $res CURL error: $curlError");

        // Coba decode JSON (cek dulu valid)
        $decoded = json_decode($res, true);

        if (in_array($status, [200, 201])) {
            return ['success' => true, 'status' => $status, 'data' => $decoded];
        } else {
            $error = $decoded['error'] ?? $res ?? 'Unknown error';
            return ['success' => false, 'message' => $error];
        }
    }
}
