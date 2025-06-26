<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PasienController extends BaseController
{
    protected string $judul = 'Data Pasien';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Pasien', 'icon' => 'pasien'],
    ];
    protected string $modul_path  = '/datapasien';
    protected string $kolom_id = 'no_bpjs';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis]
        [1, 'Nomor Registrasi', 'no_bpjs', 'indeks'],
        [1, 'Nama Rawat', 'nama_program', 'nama'],
        [1, 'Tanggal', 'penyelenggara', 'status'],
        [1, 'Jam', 'tarif', 'jumlah'],
        [1, 'Nama', 'batas_bawah', 'uang'],
        [1, 'Jenis Kelamin', 'batas_atas', 'uang'],
        [1, 'Umur', 'batas_atas', 'uang'],
        [1, 'Aksi', 'batas_atas', 'uang']
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
