<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PTKP extends BaseController
{   
    protected string $judul = 'Penghasilan Tidak Kena Pajak';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'], 
        ['title' => 'PTKP', 'icon' => 'ptkp'],
    ];
    protected string $modul_path = '/ptkp';
    protected string $kolom_id = 'no_ptkp';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis]
        [1, 'Nomor PTKP', 'no_ptkp'   , 'indeks'],
        [1, 'Kode PTKP' , 'kode_ptkp' , 'teks'],
        [1, 'Perkawinan', 'perkawinan', 'status'],
        [1, 'Tanggungan', 'tanggungan', 'jumlah'],
        [1, 'Nilai PTKP', 'nilai_ptkp', 'uang']
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
