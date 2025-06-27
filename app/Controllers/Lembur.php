<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Lembur extends BaseController
{   
    protected string $judul = 'Upah Lembur';
    protected array $breadcrumbs = [
        ['title' => 'User'  , 'icon' => 'user'], 
        ['title' => 'Lembur', 'icon' => 'lembur'],
    ];
    protected string $modul_path = '/lembur';
    protected string $kolom_id = 'no_lembur';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis]
        [1, 'Nomor Lembur', 'no_lembur', 'indeks'],
        [1, 'Jenis Lembur', 'jenis_lembur', 'status',[
            ['Libur Nasional', 'Libur Nasional'],
            ['Hari Biasa', 'Hari Biasa']
        ]],
        [1, 'Jam Lembur'  , 'jam_lembur'  , 'jumlah'],
        [1, 'Pengali Upah', 'pengali_upah', 'jumlah']
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
