<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class THR extends BaseController
{   
    protected string $judul = 'Tunjangan Hari Raya';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'], 
        ['title' => 'THR' , 'icon' => 'thr'],
    ];
    protected string $modul_path = '/thr';
    protected string $kolom_id = 'no_thr';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor THR'          , 'no_thr'     , 'indeks', 1],
        [1, 'Masa Kerja (bulan)' , 'masa_kerja' , 'jumlah', 1],
        [1, 'Pengali Upah'       , 'pengali_upah', 'teks' , 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
