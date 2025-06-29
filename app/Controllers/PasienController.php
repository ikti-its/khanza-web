<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PasienController extends BaseController
{
    protected string $judul = 'Data Pasien';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'admin'],
        ['title' => 'Data Pasien', 'icon' => 'pasien']
    ];
    protected string $modul_path = '/masterpasien';
    protected string $kolom_id = 'no_rkm_medis';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks', 1],
        [1, 'Nama Pasien', 'nm_pasien', 'nama', 1],
        [1, 'No. KTP/SIM', 'no_ktp', 'indeks', 0],
        [1, 'Jenis Kelamin', 'jk', 'status', 1, [
            ['L', 'Laki-laki'],
            ['P', 'Perempuan']
        ]],
        [1, 'Tempat Lahir', 'tmp_lahir', 'nama', 0],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 0],
        [1, 'Nama Ibu', 'nm_ibu', 'nama', 0],
        [1, 'Alamat Pasien', 'alamat', 'teks', 1],
        [1, 'Golongan Darah', 'gol_darah', 'status', 0, [
            ['A', 'A'],
            ['B', 'B'],
            ['AB', 'AB'],
            ['O', 'O']
        ]],
        [1, 'Pekerjaan', 'pekerjaan', 'nama', 0],
        [1, 'Status Pernikahan', 'stts_nikah', 'status', 0, [
            ['Menikah', 'Menikah'],
            ['Belum Menikah', 'Belum Menikah'],
            ['Cerai', 'Cerai']
        ]],
        [1, 'Agama', 'agama', 'status', 0, [
            ['Islam', 'Islam'],
            ['Kristen', 'Kristen'],
            ['Katolik', 'Katolik'],
            ['Hindu', 'Hindu'],
            ['Budha', 'Budha'],
            ['Konghucu', 'Konghucu'],
            ['Lainnya', 'Lainnya']
        ]],
        [1, 'Tanggal Daftar', 'tgl_daftar', 'tanggal', 1],
        [1, 'No. Telepon', 'no_tlp', 'teks', 0],
        [1, 'Umur', 'umur', 'jumlah', 0],
        [1, 'Pendidikan', 'pnd', 'status', 0, [
            ['SD', 'SD'],
            ['SMP', 'SMP'],
            ['SMA', 'SMA'],
            ['D3', 'D3'],
            ['S1', 'S1'],
            ['S2', 'S2'],
            ['S3', 'S3']
        ]],
        [1, 'Penanggung Jawab', 'keluarga', 'status', 0, [
            ['Ayah', 'Ayah'],
            ['Ibu', 'Ibu'],
            ['Istri', 'Istri'],
            ['Suami', 'Suami'],
            ['Saudara', 'Saudara'],
            ['Anak', 'Anak'],
            ['Diri Sendiri', 'Diri Sendiri'],
            ['Lainnya', 'Lainnya']
        ]],
        [1, 'Nama PJ', 'namakeluarga', 'nama', 0],
        [1, 'Asuransi', 'kd_pj', 'teks', 0],
        [1, 'No. Peserta', 'no_peserta', 'teks', 0],
        [1, 'Pekerjaan PJ', 'pekerjaanpj', 'teks', 0],
        [1, 'Alamat PJ', 'alamatpj', 'teks', 0],
        [1, 'Suku', 'suku_bangsa', 'teks', 0],
        [1, 'Bahasa', 'bahasa_pasien', 'teks', 0],
        [1, 'Instansi Pasien', 'perusahaan_pasien', 'teks', 0],
        [1, 'NIP/NRP', 'nip', 'teks', 0],
        [1, 'Email', 'email', 'teks', 0],
        [1, 'Cacat Fisik', 'cacat_fisik', 'teks', 0],
        [1, 'Kode Kelurahan', 'kd_kel', 'teks', 0],
        [1, 'Kode Kecamatan', 'kd_kec', 'teks', 0],
        [1, 'Kode Kabupaten', 'kd_kab', 'teks', 0],
        [1, 'Kelurahan PJ', 'kelurahanpj', 'teks', 0],
        [1, 'Kecamatan PJ', 'kecamatanpj', 'teks', 0],
        [1, 'Kabupaten PJ', 'kabupatenpj', 'teks', 0],
        [1, 'Kode Provinsi', 'kd_prop', 'teks', 0],
        [1, 'Provinsi PJ', 'propinsipj', 'teks', 0],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
    protected array $tabel = [];

    public function simpanTambah()
    {
        $formData = $this->request->getPost();

        // Hapus CSRF token (tidak diperlukan oleh API)
        unset($formData['csrf_test_name']);

        // Pastikan semua field wajib dikirim, kosongkan jika tidak ada
        $requiredFields = [
            'no_rkm_medis',
            'nm_pasien',
            'no_ktp',
            'jk',
            'tmp_lahir',
            'tgl_lahir',
            'nm_ibu',
            'alamat',
            'gol_darah',
            'pekerjaan',
            'stts_nikah',
            'agama',
            'tgl_daftar',
            'no_tlp',
            'umur',
            'pnd',
            'keluarga',
            'namakeluarga',
            'kd_pj',
            'no_peserta',
            'pekerjaanpj',
            'alamatpj',
            'suku_bangsa',
            'bahasa_pasien',
            'perusahaan_pasien',
            'nip',
            'email',
            'cacat_fisik',
            'kd_kel',
            'kd_kec',
            'kd_kab',
            'kelurahanpj',
            'kecamatanpj',
            'kabupatenpj',
            'kd_prop',
            'propinsipj'
        ];
        foreach ($requiredFields as $key) {
            if (!isset($formData[$key])) {
                $formData[$key] = '';
            }
        }

        // Format tanggal
        $formData['tgl_lahir'] = date('Y-m-d', strtotime($formData['tgl_lahir']));
        $formData['tgl_daftar'] = date('Y-m-d', strtotime($formData['tgl_daftar']));




        // Pastikan integer dikirim sebagai string (Go expect string semua)
        $intFields = ['kd_kel', 'kd_kec', 'kd_kab', 'kd_prop'];
        foreach ($intFields as $key) {
            $formData[$key] = (string) $formData[$key];
        }

        // Kirim ke API
        $token = session()->get('jwt_token');
        $api_url = $this->api_url . '/masterpasien';

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($formData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        dd([
            'http_code' => $httpCode,
            'response' => $response,
            'sent_data' => $formData,
        ]);

        if ($httpCode === 201 || $httpCode === 200) {
            return redirect()->to('/masterpasien')->with('success', 'Data berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan data');
    }
}
