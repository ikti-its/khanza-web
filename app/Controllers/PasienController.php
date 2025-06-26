<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PasienController extends BaseController
{
    protected string $judul = 'Data Pasien';
    protected array $breadcrumbs = [
        ['title' => 'Admin', 'icon' => 'admin'],
        ['title' => 'Data Pasien', 'icon' => 'pasien']
    ];
    protected string $modul_path = '/datapasien';
    protected string $kolom_id = 'nomor_reg';
    protected array $aksi = [
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];
    protected array $konfig = [
        [1, 'Nomor Registrasi', 'nomor_reg', 'indeks'],
        [1, 'Nomor Rawat',      'nomor_rawat', 'indeks'],
        [1, 'Tanggal',          'tanggal', 'tanggal'],
        [1, 'Jam',              'jam', 'jam'],
        [1, 'Nama',             'nama_pasien', 'nama'],
        [1, 'Jenis Kelamin',    'jenis_kelamin', 'status'],
        [1, 'Umur',             'umur', 'jumlah']
    ];
    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];
    protected array $tabel = [];

    public function tampilData()
    {
        if (session()->has('jwt_token')) {
            $token = session()->get('jwt_token');
            $page = $this->request->getGet('page') ?? 1;
            $size = $this->meta_data['size']; // default 10 dari atas

            $api_url = $this->api_url . "/registrasi?page={$page}&size={$size}";

            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_status !== 200) {
                return $this->renderErrorView($http_status);
            }

            $response_data = json_decode($response, true);
            $this->tabel = $response_data['data'] ?? [];
            $this->meta_data = $response_data['meta_data'] ?? $this->meta_data;

            return view('layouts/data', get_object_vars($this));
        }

        return $this->renderErrorView(401);
    }
}
