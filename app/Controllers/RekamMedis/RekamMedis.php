<?php

namespace App\Controllers\RekamMedis;

use App\Controllers\BaseController;

class RekamMedis extends BaseController
{
    protected string $registrasi_api_url = 'http://localhost:8080/v1';
    protected string $judul = 'Rekam Medis Pasien';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Rekam Medis Pasien', 'icon' => 'pasien']
    ];
    protected string $modul_path = '/rekam-medis';
    protected string $api_path = '/masterpasien';
    protected string $nama_tabel = 'pasien';
    protected string $kolom_id = 'no_rkm_medis';
    protected array $aksi = [
        'notif'    => false,
        'tambah'   => false,
        'audit'    => true,
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => false,
        'ubah'     => false,
        'hapus'    => false,
        'detail2'  => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks'],
        [1, 'Nama Pasien', 'nm_pasien', 'nama'],
        [0, 'No. KTP/SIM', 'no_ktp', 'indeks'],
        [1, 'Jenis Kelamin', 'jk', 'status'],
        [1, 'Tempat Lahir', 'tmp_lahir', 'nama'],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal'],
        [0, 'Nama Ibu', 'nm_ibu', 'nama'],
        [0, 'Alamat Pasien', 'alamat', 'teks'],
        [0, 'Golongan Darah', 'gol_darah', 'status'],
        [0, 'Pekerjaan', 'pekerjaan', 'nama'],
        [0, 'Status Pernikahan', 'stts_nikah', 'status'],
        [0, 'Agama', 'agama', 'status'],
        [0, 'Tanggal Daftar', 'tgl_daftar', 'tanggal'],
        [0, 'No. Telepon', 'no_tlp', 'teks'],
        [1, 'Umur', 'umur', 'teks'],
        [0, 'Pendidikan', 'pnd', 'status'],
        [0, 'Asuransi', 'asuransi', 'teks'],
        [0, 'No. Asuransi / Polis', 'no_asuransi', 'teks'],
        [0, 'Suku', 'suku_bangsa', 'teks'],
        [0, 'Bahasa', 'bahasa_pasien', 'teks'],
        [0, 'Instansi Pasien', 'perusahaan_pasien', 'teks'],
        [0, 'NIP/NRP', 'nip', 'teks'],
        [0, 'Email', 'email', 'teks'],
        [0, 'Cacat Fisik', 'cacat_fisik', 'teks'],
        [0, 'Kode Kelurahan', 'kd_kel', 'teks'],
        [0, 'Kode Kecamatan', 'kd_kec', 'teks'],
        [0, 'Kode Kabupaten', 'kd_kab', 'teks'],
        [0, 'Kode Provinsi', 'kd_prop', 'teks'],
        [1, 'Status Pasien', 'stts_pasien', 'status'],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];



    public function detail($id)
    {
        // 1. Ambil data pasien utama dari masterpasien
        $response = $this->fetchDataUsingCurl('GET', $this->api_path . '/' . $id);
        if ($response['kode'] !== 200 || !isset($response['data']['data'])) {
            return $this->renderErrorView(404, 'Data pasien tidak ditemukan.');
        }

        $pasien = $response['data']['data'];

        // Format jenis kelamin
        $pasien['jk'] = match ($pasien['jk']) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-'
        };

        // Format tanggal lahir dan umur
        $tgl_lahir = $pasien['tgl_lahir'] ?? null;
        $pasien['tgl_lahir'] = $this->formatTanggalIndo($tgl_lahir);
        $pasien['umur'] = $this->hitungUmur($tgl_lahir);

        // 2. Ambil data registrasi terakhir dari endpoint registrasi
        $registrasiEndpoint = 'http://localhost:8080/v1/registrasi/pasien/' . $id;
        $registrasi = $this->fetchDataUsingCurl(
            'GET',
            '/registrasi/pasien/' . $id,
            null,
            null,
            null,
            $this->registrasi_api_url
        );

        // dd($registrasi);

        // Jika hasil registrasi berupa array (karena .data mungkin array), ambil [0]
        $pasien['registrasi'] = [];

        if (
            isset($registrasi['kode']) &&
            $registrasi['kode'] === 200 &&
            isset($registrasi['data']['data']) &&
            is_array($registrasi['data']['data']) &&
            count($registrasi['data']['data']) > 0
        ) {
            $pasien['registrasi'] = $registrasi['data']['data'];
        }




        // Tambahkan breadcrumb
        $this->breadcrumbs[] = ['title' => 'Detail'];

        return view('admin/rekam_medis/detail', [
            'judul' => 'Detail Rekam Medis',
            'pasien' => $pasien,
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }



    private function formatTanggalIndo(?string $tanggal): string
    {
        if (!$tanggal) return '-';

        $tanggal = substr($tanggal, 0, 10);
        $timestamp = strtotime($tanggal);

        if (class_exists('IntlDateFormatter')) {
            $formatter = new \IntlDateFormatter(
                'id_ID',
                \IntlDateFormatter::LONG,
                \IntlDateFormatter::NONE
            );
            $date = date_create_from_format('Y-m-d', $tanggal);
            if ($date) {
                return $formatter->format($date);
            }
        }

        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $bulanInggris = date('F', $timestamp);
        $tanggalHari = date('d', $timestamp);
        $tahun = date('Y', $timestamp);
        $bulan = $bulanIndo[$bulanInggris] ?? $bulanInggris;

        return "{$tanggalHari} {$bulan} {$tahun}";
    }

    private function hitungUmur(?string $tgl_lahir): string
    {
        if (!$tgl_lahir) return '-';

        $tgl = new \DateTime(substr($tgl_lahir, 0, 10));
        $now = new \DateTime();

        $diff = $now->diff($tgl);

        return "{$diff->y} Th {$diff->m} Bl {$diff->d} Hr";
    }
}
