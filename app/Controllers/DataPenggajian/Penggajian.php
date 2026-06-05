<?php
declare(strict_types=1);

namespace App\Controllers\DataPenggajian;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class Penggajian extends ControllerTemplateLegacy
{
    protected string $judul = 'Data Penggajian';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Penggajian', 'icon' => 'penggajian'],
    ];
    protected string $modul_path  = '/data-penggajian/penggajian';
    protected string $api_path = '/data-penggajian/penggajian';
    protected string $nama_tabel = 'penggajian';
    protected string $kolom_id = 'no_pegawai';
    protected array $aksi = [
        'notif'    => false,
        'tambah'   => false,
        'audit'    => true,
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => false
    ];
    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'Nomor Pegawai', 'no_pegawai', 'indeks', 1],
        [1, 'Nama Pegawai', 'nama_pegawai', 'nama', 1],
        [1, 'Bulan', 'bulan', 'jumlah', 1],
        [1, 'Tahun', 'tahun', 'jumlah', 1],
        [1, 'Gaji Pokok', 'gaji_pokok', 'uang', 1],
        [1, 'Tunjangan', 'tunjangan', 'uang', 1],
        [1, 'BPJS', 'bpjs', 'uang', 1],
        [1, 'Pajak', 'pajak', 'uang', 1],
        [1, 'Nominal', 'nominal', 'uang', 1],
        [1, 'Status', 'status', 'status', 1, [
            ['Belum Dibayar', 'Belum Dibayar'],
            ['Sudah Dibayar', 'Sudah Dibayar']
        ]]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
