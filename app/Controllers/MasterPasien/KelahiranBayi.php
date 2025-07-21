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
        [1, 'Tempat Lahir', 'tmp_lahir', 'nama', 1],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 1],
        [1, 'Jam Lahir', 'jam', 'jam', 1],
        [1, 'Umur', 'umur', 'teks', 1],
        [1, 'Tanggal Daftar', 'tgl_daftar', 'tanggal', 1],
        [1, 'Nama Ibu', 'nm_ibu', 'nama', 1],
        [1, 'Umur Ibu', 'umur_ibu', 'teks', 1],
        [1, 'Nama Ayah', 'nm_ayah', 'nama', 1],
        [1, 'Umur Ayah', 'umur_ayah', 'teks', 1],
        [1, 'Alamat Ibu', 'alamat', 'teks', 1],
        [1, 'Berat Badan (gram)', 'bb', 'jumlah', 1],
        [1, 'Panjang Badan (cm)', 'pb', 'jumlah', 1],
        [1, 'Proses Lahir', 'proses_lahir', 'teks', 1],
        [1, 'Kelahiran ke-', 'kelahiran_ke', 'jumlah', 1],
        [1, 'Diagnosa', 'diagnosa', 'teks', 0],
        [1, 'Penyulit Kehamilan', 'penyulit_kehamilan', 'teks', 0],
        [1, 'Ketuban', 'ketuban', 'teks', 0],
        [1, 'Lingkar Perut (cm)', 'lk_perut', 'jumlah', 0],
        [1, 'Lingkar Kepala (cm)', 'lk_kepala', 'jumlah', 0],
        [1, 'Lingkar Dada (cm)', 'lk_dada', 'jumlah', 0],
        [1, 'Penolong', 'penolong', 'teks', 1],
        [1, 'No SKL', 'no_skl', 'teks', 0],
        [1, 'Gravida', 'gravida', 'jumlah', 0],
        [1, 'Para', 'para', 'jumlah', 0],
        [1, 'Abortus', 'abortus', 'jumlah', 0],

        // APGAR Score (ringkas)
        [1, 'APGAR N1', 'n1', 'jumlah', 0],
        [1, 'APGAR N5', 'n5', 'jumlah', 0],
        [1, 'APGAR N10', 'n10', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-1)
        [1, 'APGAR F1 (Face)', 'f1', 'jumlah', 0],
        [1, 'APGAR U1 (Upaya Napas)', 'u1', 'jumlah', 0],
        [1, 'APGAR T1 (Tonus Otot)', 't1', 'jumlah', 0],
        [1, 'APGAR R1 (Refleks)', 'r1', 'jumlah', 0],
        [1, 'APGAR W1 (Warna Kulit)', 'w1', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-5)
        [1, 'APGAR F5 (Face)', 'f5', 'jumlah', 0],
        [1, 'APGAR U5 (Upaya Napas)', 'u5', 'jumlah', 0],
        [1, 'APGAR T5 (Tonus Otot)', 't5', 'jumlah', 0],
        [1, 'APGAR R5 (Refleks)', 'r5', 'jumlah', 0],
        [1, 'APGAR W5 (Warna Kulit)', 'w5', 'jumlah', 0],

        // APGAR Score (terperinci menit ke-10)
        [1, 'APGAR F10 (Face)', 'f10', 'jumlah', 0],
        [1, 'APGAR U10 (Upaya Napas)', 'u10', 'jumlah', 0],
        [1, 'APGAR T10 (Tonus Otot)', 't10', 'jumlah', 0],
        [1, 'APGAR R10 (Refleks)', 'r10', 'jumlah', 0],
        [1, 'APGAR W10 (Warna Kulit)', 'w10', 'jumlah', 0],

        [1, 'Resusitasi', 'resusitas', 'teks', 0],
        [1, 'Obat Diberikan', 'obat', 'teks', 0],
        [1, 'Mikasi', 'mikasi', 'teks', 0],
        [1, 'Mekonium', 'mikonium', 'teks', 0],
        [1, 'Keterangan Tambahan', 'keterangan', 'teks', 0],
    ];

    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function tampilTambah()
    {
        return redirect()->to('/kelahiranbayi/tambah-pasien');
    }
}
