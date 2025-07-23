<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class KelahiranBayi extends BaseController
{
    protected string $judul = 'Data Kelahiran Bayi';
    protected array $breadcrumbs = [
        ['title' => 'Registrasi', 'icon' => 'user'],
        ['title' => 'Kelahiran Bayi', 'icon' => 'bayi'],
    ];
    protected string $modul_path  = '/kelahiranbayi';
    protected string $api_path    = '/kelahiranbayi';
    protected string $nama_tabel  = 'kelahiran_bayi';
    protected string $kolom_id    = 'no_rkm_medis';
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
        [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks', 1],
        [1, 'Nama Bayi', 'nm_pasien', 'nama', 1],
        [1, 'Jenis Kelamin', 'jk', 'status', 1, [
            ['L', 'Laki-laki'],
            ['P', 'Perempuan']
        ]],
        [0, 'Tempat Lahir', 'tmp_lahir', 'nama', 1],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 1],
        [1, 'Jam Lahir', 'jam', 'jam', 1],
        [1, 'Umur', 'umur', 'teks', 1],
        [0, 'Tanggal Daftar', 'tgl_daftar', 'tanggal', 1],
        [1, 'Nama Ibu', 'nm_ibu', 'nama', 1],
        [0, 'Umur Ibu', 'umur_ibu', 'teks', 1],
        [0, 'Nama Ayah', 'nm_ayah', 'nama', 1],
        [0, 'Umur Ayah', 'umur_ayah', 'teks', 1],
        [0, 'Alamat Ibu', 'alamat', 'teks', 1],
        [0, 'Berat Badan (gram)', 'bb', 'jumlah', 1],
        [0, 'Panjang Badan (cm)', 'pb', 'jumlah', 1],
        [0, 'Proses Lahir', 'proses_lahir', 'teks', 1],
        [0, 'Kelahiran ke-', 'kelahiran_ke', 'jumlah', 1],
        [0, 'Diagnosa', 'diagnosa', 'teks', 0],
        [0, 'Penyulit Kehamilan', 'penyulit_kehamilan', 'teks', 0],
        [0, 'Ketuban', 'ketuban', 'teks', 0],
        [0, 'Lingkar Perut (cm)', 'lk_perut', 'jumlah', 0],
        [0, 'Lingkar Kepala (cm)', 'lk_kepala', 'jumlah', 0],
        [0, 'Lingkar Dada (cm)', 'lk_dada', 'jumlah', 0],
        [0, 'Penolong', 'penolong', 'teks', 1],
        [1, 'No SKL', 'no_skl', 'teks', 0],
        [0, 'Gravida', 'gravida', 'jumlah', 0],
        [0, 'Para', 'para', 'jumlah', 0],
        [0, 'Abortus', 'abortus', 'jumlah', 0],

        // APGAR Score (ringkas)
        [0, 'APGAR N1', 'n1', 'jumlah', 0],
        [0, 'APGAR N5', 'n5', 'jumlah', 0],
        [0, 'APGAR N10', 'n10', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-1)
        [0, 'APGAR F1 (Face)', 'f1', 'jumlah', 0],
        [0, 'APGAR U1 (Upaya Napas)', 'u1', 'jumlah', 0],
        [0, 'APGAR T1 (Tonus Otot)', 't1', 'jumlah', 0],
        [0, 'APGAR R1 (Refleks)', 'r1', 'jumlah', 0],
        [0, 'APGAR W1 (Warna Kulit)', 'w1', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-5)
        [0, 'APGAR F5 (Face)', 'f5', 'jumlah', 0],
        [0, 'APGAR U5 (Upaya Napas)', 'u5', 'jumlah', 0],
        [0, 'APGAR T5 (Tonus Otot)', 't5', 'jumlah', 0],
        [0, 'APGAR R5 (Refleks)', 'r5', 'jumlah', 0],
        [0, 'APGAR W5 (Warna Kulit)', 'w5', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-10)
        [0, 'APGAR F10 (Face)', 'f10', 'jumlah', 0],
        [0, 'APGAR U10 (Upaya Napas)', 'u10', 'jumlah', 0],
        [0, 'APGAR T10 (Tonus Otot)', 't10', 'jumlah', 0],
        [0, 'APGAR R10 (Refleks)', 'r10', 'jumlah', 0],
        [0, 'APGAR W10 (Warna Kulit)', 'w10', 'jumlah', 0],

        [0, 'Resusitasi', 'resusitas', 'teks', 0],
        [0, 'Obat Diberikan', 'obat', 'teks', 0],
        [0, 'Mikasi', 'mikasi', 'teks', 0],
        [0, 'Mekonium', 'mikonium', 'teks', 0],
        [0, 'Keterangan Tambahan', 'keterangan', 'teks', 0],
    ];

    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function tampilTambah()
    {
        return redirect()->to('/kelahiranbayi/tambah-pasien');
    }

    public function tampilUbah($id)
    {
        return redirect()->to("kelahiranbayi/ubah-pasien/$id");
    }
}
