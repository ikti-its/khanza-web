<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DokterController extends BaseController
{
    protected string $judul = 'Data Dokter';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'admin'],
        ['title' => 'Dokter', 'icon' => 'dokter'],
    ];
    protected string $modul_path = '/datadokter';
    protected string $kolom_id = 'kode_dokter';
    protected array $aksi = [
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
