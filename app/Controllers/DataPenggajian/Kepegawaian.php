<?php

namespace App\Controllers\DataPenggajian;

use App\Controllers\BaseController;

class Kepegawaian extends BaseController
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

    public function tampilData()
    {               
        $db = \Config\Database::connect();
        $query = $db->query("SELECT a.*, b.nama as nama_pegawai FROM sik.kepegawaian a JOIN sik.pegawai  b ON a.no_pegawai =  b.id");
        $results = $query->getResult();

        for($i = 0; $i < sizeof($results); $i++){
            $results[$i] = json_decode(json_encode($results[$i]), true);
        }
        return view('/layouts/data', [
            'judul'       => $this->judul,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'aksi'        => $this->aksi, 
            'tabel'       => $results,
        ]);
    }

    public function tampilUbah($id){
        $breadcrumbs = [
            ['title' => 'Ubah', 'icon', 'Ubah']
        ];
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM sik.kepegawaian WHERE no_pegawai = " . "'$id'");
        $results = $query->getResult();

        for($i = 0; $i < sizeof($results); $i++){
            $results[$i] = json_decode(json_encode($results[$i]), true);
        }
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Ubah ' . $this->judul,
            'breadcrumbs' => array_merge($this->getBreadcrumbs(), $breadcrumbs),
            'modul_path'  => $this->modul_path,
            'kolom_id'    => $this->kolom_id,
            'konfig'      => $this->konfig,
            'baris'       => $results[0],
            'form_action' => '/submitedit/' . $results[0][$this->kolom_id],
        ]);
    }

    public function tampilAudit(){
        $audit_konfig = [
            // [1, 'Nomor Perubahan'  , 'change_id' , 'indeks'],
            // [1, 'Nama'             , 'nama'      , 'teks'],
            // [1, 'Aksi Perubahan'   , 'action'    , 'status'],
            // [1, 'IP Address'       , 'user_ip'   , 'teks'],
            // [1, 'MAC Address'      , 'user_mac'  , 'teks'],
            // // [1, 'Pengubah'         , 'changed_by', 'indeks'],
            // [1, 'Tanggal Perubahan', 'changed_at', 'tanggal'],
        ];
        $breadcrumbs = [
            ['title' => 'Audit', 'icon', 'audit']
        ];
        $db = \Config\Database::connect();
        $query = $db->query("SELECT a.*, b.nama as nama_pegawai FROM sik.kepegawaian a JOIN sik.pegawai  b ON a.no_pegawai =  b.id");
        $results = $query->getResult();

        for($i = 0; $i < sizeof($results); $i++){
            $results[$i] = json_decode(json_encode($results[$i]), true);
        }
        return view('/layouts/audit', [
            'judul'       => 'Audit ' . $this->judul,
            'breadcrumbs' => array_merge($this->getBreadcrumbs(), $breadcrumbs),
            'meta_data'   => $this->meta_data,
            'modul_path'  => $this->modul_path . '/audit',
            'kolom_id'    => 'no_pegawai',
            'konfig'      => array_merge($audit_konfig, $this->konfig),
            'tabel'       => $results
        ]);
    }
}



