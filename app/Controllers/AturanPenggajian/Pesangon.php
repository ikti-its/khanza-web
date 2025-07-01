<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class Pesangon extends BaseController
{   
    protected string $judul = 'Uang Pesangon';
    protected array $breadcrumbs = [
        ['title' => 'User'     , 'icon' => 'user'], 
        ['title' => 'Pesangon' , 'icon' => 'pesangon'],
    ];
    protected string $modul_path = '/pesangon';
    protected string $kolom_id = 'no_pesangon';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Pesangon'     , 'no_pesangon' , 'indeks', 1],
        [1, 'Masa Kerja (tahun)' , 'masa_kerja'  , 'jumlah', 1],
        [1, 'Pengali Upah'       , 'pengali_upah', 'teks'  , 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];
}
