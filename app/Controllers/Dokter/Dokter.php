<?php

namespace App\Controllers\Dokter;

use App\Controllers\BaseController;

class Dokter extends BaseController
{
    protected string $judul = 'Daftar Dokter';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'user'],
        ['title' => 'Dokter', 'icon' => 'dokter'],
    ];
    protected string $modul_path = '/dokter';
    protected string $api_path = '/dokter';
    protected string $nama_tabel = 'dokter';
    protected string $kolom_id = 'kode_dokter';
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
        [1, 'Kode Dokter', 'kode_dokter', 'indeks', 1],
        [1, 'Nama Dokter', 'nama_dokter', 'nama', 1],
        [1, 'Jenis Kelamin', 'jenis_kelamin', 'status', 1, [
            ['L', 'Laki-laki'],
            ['P', 'Perempuan']
        ]],
        [1, 'Alamat Tinggal', 'alamat_tinggal', 'teks', 0],
        [1, 'No. Telepon', 'no_telp', 'teks', 0],
        [1, 'Spesialis', 'spesialis', 'nama', 0],
        [1, 'Izin Praktik', 'izin_praktik', 'teks', 0]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
