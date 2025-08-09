<?php

namespace App\Controllers\Asuransi;

use App\Controllers\BaseController;

class Asuransi extends BaseController
{
    protected string $judul = 'Asuransi / Askes';
    protected array $breadcrumbs = [
        ['title' => 'User', 'icon' => 'user'],
        ['title' => 'Asuransi', 'icon' => 'asuransi'],
    ];
    protected string $modul_path  = '/asuransi';
    protected string $api_path = '/asuransi';
    protected string $nama_tabel = 'asuransi';
    protected string $kolom_id = 'kode_asuransi';
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
        [1, 'Kode Asuransi', 'kode_asuransi', 'indeks', 1],
        [1, 'Tipe Asuransi', 'tipe_asuransi', 'status', 1, [
            ['BPJS', 'BPJS'],
            ['Swasta', 'Swasta'],
            ['Perusahaan', 'Perusahaan'],
            ['Pemerintah', 'Pemerintah'],
            ['Internasional', 'Internasional'],
            ['Yayasan', 'Yayasan'],
            ['Pendidikan', 'Pendidikan']
        ]],
        [1, 'Nama Asuransi', 'nama_asuransi', 'nama', 1],
        [1, 'Perusahaan Asuransi', 'perusahaan_asuransi', 'nama', 0],
        [0, 'Alamat Asuransi', 'alamat_asuransi', 'nama', 0],
        [0, 'No. Telp', 'no_telp', 'nama', 0],
        [0, 'Attn', 'attn', 'nama', 0]
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
}
