<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class UMR extends BaseController
{   
    protected string $judul = 'Upah Minimum Provinsi/Kota/Kabupaten';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'], 
        ['title' => 'UMR' , 'icon' => 'umr'],
    ];
    protected string $modul_path = '/aturan-penggajian/umr';
    protected string $api_path = '/umr';
    protected string $kolom_id = 'no_umr';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor UMR'   , 'no_umr'      , 'indeks', 1],
        [1, 'Provinsi'    , 'provinsi'    , 'teks'  , 1],
        [1, 'Kota/Kab'    , 'kotakab'     , 'teks'  , 1],
        [1, 'Jenis'       , 'jenis'       , 'status', 1, [
            ['UMP', 'UMP'],
            ['UMK', 'UMK'],
        ]],
        [1, 'Upah Minimum', 'upah_minimum', 'uang'  , 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
