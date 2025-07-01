<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class UPMLK extends BaseController
{   
    protected string $judul = 'Uang Penghargaan Masa Kerja';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'], 
        ['title' => 'UPMK', 'icon' => 'upmk'],
    ];
    protected string $modul_path = '/upmk';
    protected string $kolom_id = 'no_upmk';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor UPMK'         , 'no_upmk'     , 'indeks', 1],
        [1, 'Masa Kerja (tahun)' , 'masa_kerja'  , 'jumlah', 1],
        [1, 'Pengali Upah'       , 'pengali_upah', 'teks'  , 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
