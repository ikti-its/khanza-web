<?php
declare(strict_types=1);

namespace App\Controllers\DataPenggajian;
use App\Core\Controller\Legacy\ControllerTemplateLegacy;

class Kepegawaian extends ControllerTemplateLegacy
{
    protected string $judul = 'Data Kepegawaian';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Kepegawaian', 'icon' => 'kepegawaian'],
    ];
    protected string $modul_path  = '/data-penggajian/kepegawaian';
    protected string $api_path = '/data-penggajian/kepegawaian';
    protected string $nama_tabel = 'kepegawaian';
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
        // [0, 'Tanggal Masuk', 'tanggal_masuk', 'tanggal', 1],
        [1, 'Status', 'status', 'status', 1, [
            ['Aktif', 'Aktif'],
            ['Nonaktif', 'Nonaktif']
        ]],
        [1, 'Golongan', 'golongan', 'teks', 1],
        [1, 'Jabatan', 'jabatan', 'teks', 1],
        [0, 'JKN', 'jkn', 'teks', 1],
        [0, 'JKK', 'jkk', 'teks', 1],
        [0, 'JKM', 'jkm', 'teks', 1],
        [0, 'JHT', 'jht', 'teks', 1],
        [0, 'JP' , 'jp' , 'teks', 1],
        [0, 'JKP', 'jkp', 'teks', 1],
        [1, 'PTKP', 'ptkp', 'teks', 1],
        [1, 'Bank', 'bank', 'teks', 1],
        [1, 'Rekening', 'rekening', 'teks', 1]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
