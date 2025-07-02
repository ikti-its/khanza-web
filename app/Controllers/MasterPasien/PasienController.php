<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class PasienController extends BaseController
{
    protected string $judul = 'Data Pasien';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'admin'],
        ['title' => 'Data Pasien', 'icon' => 'pasien']
    ];
    protected string $modul_path = '/masterpasien';
    protected string $api_path = '/masterpasien';
    protected string $kolom_id = 'no_rkm_medis';
    protected array $aksi = [
        'notif'    => false,
        'tambah'   => true,
        'audit'    => false,
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
            ['Laki-laki', 'Laki-laki'],
            ['Perempuan', 'Perempuan']
        ]],
        [1, 'Tempat Lahir', 'tmp_lahir', 'nama', 0],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 0],
        [1, 'Nama Ibu', 'nm_ibu', 'nama', 0],
        [1, 'Alamat Pasien', 'alamat', 'teks', 1],
        [1, 'Golongan Darah', 'gol_darah', 'status', 1, [
            ['-', '-'],
            ['A', 'A'],
            ['B', 'B'],
            ['AB', 'AB'],
            ['O', 'O']
        ]],
        [1, 'Pekerjaan', 'pekerjaan', 'nama', 0],
        [1, 'Status Pernikahan', 'stts_nikah', 'status', 0, [
            ['Menikah', 'Menikah'],
            ['Belum Menikah', 'Belum Menikah'],
            ['Janda', 'Janda'],
            ['Duda', 'Duda']
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

    public function tampilTambah()
    {
        return redirect()->to('masterpasien/tambah-pasien');
    }

    public function tampilUbah($id)
    {
        $this->konfig = [
            // [visible, Display, Kolom, Jenis, Required, *Opsi]
            [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks', 1],
            [1, 'Nama Pasien', 'nm_pasien', 'nama', 1],
            [1, 'No. KTP/SIM', 'no_ktp', 'indeks', 0],
            [1, 'Jenis Kelamin', 'jk', 'status', 1, [
                ['Laki-laki', 'Laki-laki'],
                ['Perempuan', 'Perempuan']
            ]],
            [1, 'Tempat Lahir', 'tmp_lahir', 'nama', 0],
            [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 0],
            [1, 'Nama Ibu', 'nm_ibu', 'nama', 0],
            [1, 'Alamat Pasien', 'alamat', 'teks', 1],
            [1, 'Golongan Darah', 'gol_darah', 'status', 1, [
                ['-', '-'],
                ['A', 'A'],
                ['B', 'B'],
                ['AB', 'AB'],
                ['O', 'O']
            ]],
            [1, 'Pekerjaan', 'pekerjaan', 'nama', 0],
            [1, 'Status Pernikahan', 'stts_nikah', 'status', 0, [
                ['Menikah', 'Menikah'],
                ['Belum Menikah', 'Belum Menikah'],
                ['Janda', 'Janda'],
                ['Duda', 'Duda']
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
        return parent::tampilUbah($id);
    }
}
