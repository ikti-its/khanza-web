<?php

namespace App\Controllers\DataPenggajian;

use App\Controllers\BaseController;

class Penggajian extends BaseController
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

     public function tampilData()
    {               
        $db = \Config\Database::connect();
        $query = $db->query("SELECT a.*, b.nama as nama_pegawai FROM sik.penggajian a JOIN sik.pegawai  b ON a.no_pegawai =  b.id");
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
        $query = $db->query("SELECT a.*, b.nama as nama_pegawai FROM sik.penggajian a JOIN sik.pegawai  b ON a.no_pegawai =  b.id WHERE no_pegawai = ". "'$id'");
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
        $query = $db->query("SELECT a.*, b.nama as nama_pegawai FROM sik.penggajian a JOIN sik.pegawai  b ON a.no_pegawai =  b.id");
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
