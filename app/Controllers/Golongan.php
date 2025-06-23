<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Golongan extends BaseController
{
    protected string $judul = 'Golongan Pegawai';
    protected array $breadcrumbs = [
        ['title' => 'User',     'icon' => 'user'], 
        ['title' => 'Golongan', 'icon' => 'golongan'],
    ];
    protected string $modul_path = '/golongan';
    protected string $kolom_id = 'no_golongan';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis]
        [1, 'Nomor Golongan', 'no_golongan'  , 'indeks'],
        [1, 'Kode Golongan' , 'kode_golongan', 'teks'],
        [1, 'Nama Golongan' , 'nama_golongan', 'teks'],
        [1, 'Pendidikan'    , 'pendidikan'   , 'teks'],
        [1, 'Gaji Pokok'    , 'gaji_pokok'   , 'uang']
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];
}
