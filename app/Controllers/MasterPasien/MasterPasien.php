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
        [1, 'No. KTP/SIM', 'no_ktp', 'indeks'],
        [1, 'Jenis Kelamin', 'jk', 'status'],
        [1, 'Tempat Lahir', 'tmp_lahir', 'nama'],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal'],
        [1, 'Nama Ibu', 'nm_ibu', 'nama'],
        [1, 'Alamat Pasien', 'alamat', 'teks'],
        [1, 'Golongan Darah', 'gol_darah', 'status'],
        [1, 'Pekerjaan', 'pekerjaan', 'nama'],
        [1, 'Status Pernikahan', 'stts_nikah', 'status'],
        [1, 'Agama', 'agama', 'status'],
        [1, 'Tanggal Daftar', 'tgl_daftar', 'tanggal'],
        [1, 'No. Telepon', 'no_tlp', 'teks'],
        [1, 'Umur', 'umur', 'teks'],
        [1, 'Pendidikan', 'pnd', 'status'],
        [1, 'Asuransi', 'kd_pj', 'teks'],
        [1, 'No. Peserta', 'no_peserta', 'teks'],
        [1, 'Suku', 'suku_bangsa', 'teks'],
        [1, 'Bahasa', 'bahasa_pasien', 'teks'],
        [1, 'Instansi Pasien', 'perusahaan_pasien', 'teks'],
        [1, 'NIP/NRP', 'nip', 'teks'],
        [1, 'Email', 'email', 'teks'],
        [1, 'Cacat Fisik', 'cacat_fisik', 'teks'],
        [1, 'Kode Kelurahan', 'kd_kel', 'teks'],
        [1, 'Kode Kecamatan', 'kd_kec', 'teks'],
        [1, 'Kode Kabupaten', 'kd_kab', 'teks'],
        [1, 'Kode Provinsi', 'kd_prop', 'teks'],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function tampilTambah()
    {
        return redirect()->to('masterpasien/tambah-pasien');
    }

    public function tampilUbah($id)
    {
        return redirect()->to('masterpasien/ubah-pasien');
    }
}
