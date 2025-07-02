<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class PPH21 extends BaseController
{   
    protected string $judul = 'Aturan Pajak Penghasilan (PPH 21)';
    protected array $breadcrumbs = [
        ['title' => 'User',  'icon' => 'user'], 
        ['title' => 'PPH21', 'icon' => 'pph21'],
    ];
    protected string $modul_path = '/aturan-penggajian/pph21';
    protected string $api_path = '/pph21';
    protected string $nama_tabel = 'pph21';
    protected string $kolom_id = 'no_pph21';
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
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Pajak'    , 'no_pph21'   , 'indeks', 1],
        [1, 'Batas Bawah PKP', 'pkp_bawah'  , 'uang'  , 1],
        [1, 'Batas Atas PKP' , 'pkp_atas'   , 'uang'  , 1],
        [1, 'Tarif Pajak (%)', 'tarif_pajak', 'jumlah', 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
