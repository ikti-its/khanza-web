<?php
declare(strict_types=1);

namespace App\Controllers\DataPenggajian;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class PHK extends ControllerTemplateLegacy
{
    protected string $judul = 'Data PHK';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'PHK', 'icon' => 'PHK'],
    ];
    protected string $modul_path  = '/data-penggajian/phk';
    protected string $api_path = '/data-penggajian/phk';
    protected string $nama_tabel = 'data_phk';
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
        [1, 'Lama Bekerja (Bulan)', 'lama_bekerja', 'jumlah', 1],
        [1, 'Pesangon', 'pesangon', 'uang', 1],
        [1, 'UPMK', 'upmk', 'uang', 1],
        [1, 'Nominal', 'nominal', 'uang', 1],
        [1, 'Status', 'status', 'status', 1, [
            ['Belum Dibayar', 'Belum Dibayar'],
            ['Sudah Dibayar', 'Sudah Dibayar']
        ]]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
