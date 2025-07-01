<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class BPJS extends BaseController
{
    protected string $judul = 'Data BPJS';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'BPJS', 'icon' => 'bpjs'],
    ];
    protected string $modul_path  = '/bpjs';
    protected string $kolom_id = 'no_bpjs';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Program', 'no_bpjs', 'indeks', 1],
        [1, 'Nama Program', 'nama_program', 'nama', 1],
        [1, 'Penyelenggara', 'penyelenggara', 'status', 1, [
            ['BPJS Kesehatan', 'BPJS Kesehatan'],
            ['BPJS Ketenagakerjaan', 'BPJS Ketenagakerjaan']
        ]],
        [1, 'Tarif (%)', 'tarif', 'jumlah', 1],
        [1, 'Batas Bawah', 'batas_bawah', 'uang', 1],
        [1, 'Batas Atas', 'batas_atas', 'uang', 1]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
