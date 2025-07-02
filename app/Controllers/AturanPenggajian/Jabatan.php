<?php

namespace App\Controllers\AturanPenggajian;

use App\Controllers\BaseController;

class Jabatan extends BaseController
{
    protected string $judul = 'Aturan Jabatan Pegawai';
    protected array $breadcrumbs = [
        ['title' => 'User',    'icon' => 'user'], 
        ['title' => 'Jabatan', 'icon' => 'jabatan'],
    ];
    protected string $modul_path = '/aturan-penggajian/jabatan';
    protected string $api_path = '/jabatan';
    protected string $nama_tabel = 'jabatan_pegawai';
    protected string $kolom_id = 'no_jabatan';
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
        [1, 'Nomor Jabatan', 'no_jabatan'   , 'indeks' , 1],
        [1, 'Jenis Jabatan', 'jenis_jabatan', 'status' , 1, [
            ['Struktural', 'Struktural'],
            ['Fungsional', 'Fungsional'],
        ]],
        [1, 'Nama Jabatan' , 'nama_jabatan' , 'teks'   , 1],
        [1, 'Jenjang'      , 'jenjang'      , 'status' , 1, [
            ['Ahli Pertama', 'Ahli Pertama'],
            ['Muda', 'Muda'],
            ['Madya', 'Madya'],
            ['Utama', 'Utama'],
            ['Pemula', 'Pemula'],
            ['Mahir', 'Mahir'],
            ['Penyelia', 'Penyelia'],
            ['Ahli', 'Ahli'],
        ]],
        [1, 'Tunjangan'    , 'tunjangan'    , 'uang'  , 1],
    ];
    protected array $meta_data =['page' => 1, 'size' => 10, 'total' => 1];    
}
