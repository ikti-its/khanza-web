<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Instansi extends BaseController
{
    protected string $judul = 'Data Instansi/Perusahaan Pasien';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Instansi', 'icon' => 'instansi'],
    ];
    protected string $modul_path  = '/datainstansi';
    protected string $api_path = '/datainstansi';
    protected string $kolom_id = 'kode_instansi'; // harus sama persis dengan response
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => false,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Kode Instansi', 'kode_instansi', 'indeks', 1],
        [1, 'Nama Instansi', 'nama_instansi', 'nama', 1],
        [1, 'Alamat Instansi', 'alamat_instansi', 'teks', 0],
        [1, 'Kota', 'kota', 'teks', 0],
        [1, 'No. Telepon', 'no_telp', 'teks', 0],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
