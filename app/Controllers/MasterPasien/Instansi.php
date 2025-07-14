<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class Instansi extends BaseController
{
    protected string $judul = 'Data Instansi/Perusahaan Pasien';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Instansi', 'icon' => 'instansi'],
    ];
    protected string $modul_path  = '/instansi';
    protected string $api_path = '/instansi';
    protected string $nama_tabel = 'data_instansi';
    protected string $kolom_id = 'kode_instansi';
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
        [1, 'Kode Instansi', 'kode_instansi', 'indeks', 1],
        [1, 'Nama Instansi', 'nama_instansi', 'nama', 1],
        [1, 'Alamat Instansi', 'alamat_instansi', 'teks', 0],
        [1, 'Kota', 'kota', 'teks', 0],
        [1, 'No. Telepon', 'no_telp', 'teks', 0],
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
