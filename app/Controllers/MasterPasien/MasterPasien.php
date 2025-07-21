<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class MasterPasien extends BaseController
{
    protected string $judul = 'Data Pasien';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'admin'],
        ['title' => 'Data Pasien', 'icon' => 'pasien']
    ];
    protected string $modul_path = '/masterpasien';
    protected string $api_path = '/masterpasien';
    protected string $nama_tabel = 'pasien';
    protected string $kolom_id = 'no_rkm_medis';
    protected array $aksi = [
        'notif'    => false,
        'tambah'   => true,
        'audit'    => true,
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks'],
        [1, 'Nama Pasien', 'nm_pasien', 'nama'],
        [0, 'No. KTP/SIM', 'no_ktp', 'indeks'],
        [1, 'Jenis Kelamin', 'jk', 'status'],
        [0, 'Tempat Lahir', 'tmp_lahir', 'nama'],
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
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function tampilTambah()
    {
        return redirect()->to('masterpasien/tambah-pasien');
    }

    public function tampilUbah($id)
    {
        return redirect()->to("masterpasien/ubah-pasien/$id");
    }
}
