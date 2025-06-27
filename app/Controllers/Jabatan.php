<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Jabatan extends BaseController
{
    protected string $judul = 'Data Jabatan';
    protected array $breadcrumbs = [
        ['title' => 'User',    'icon' => 'user'], 
        ['title' => 'Jabatan', 'icon' => 'jabatan'],
    ];
    protected string $modul_path = '/jabatan';
    protected string $kolom_id = 'no_jabatan';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis]
        [1, 'Nomor Jabatan', 'no_jabatan'   , 'indeks'],
        [1, 'Jenis Jabatan', 'jenis_jabatan', 'status', [
            ['Struktural', 'Struktural'],
            ['Fungsional', 'Fungsional'],
        ]],
        [1, 'Nama Jabatan' , 'nama_jabatan' , 'teks'],
        [1, 'Jenjang'      , 'jenjang'      , 'status',[
            ['Ahli Pertama', 'Ahli Pertama'],
            ['Muda', 'Muda'],
            ['Madya', 'Madya'],
            ['Utama', 'Utama'],
            ['Pemula', 'Pemula'],
            ['Mahir', 'Mahir'],
            ['Penyelia', 'Penyelia'],
            ['Ahli', 'Ahli'],
        ]],
        [1, 'Tunjangan'    , 'tunjangan'    , 'uang']
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
