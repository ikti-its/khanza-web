<?php

namespace App\Controllers\MasterPasien;

use App\Controllers\BaseController;

class PasienMeninggal extends BaseController
{
    protected string $judul = 'Data Pasien Meninggal';
    protected array $breadcrumbs = [
        ['title' => 'Pasien', 'icon' => 'user'],
        ['title' => 'Meninggal', 'icon' => 'pasien'],
    ];
    protected string $modul_path  = '/pasienmeninggal';
    protected string $api_path    = '/pasienmeninggal';
    protected string $nama_tabel  = 'pasien_meninggal';
    protected string $kolom_id    = 'no_rkm_medis';
    protected array $aksi = [
        'notif'    => false,
        'tambah'   => true,
        'audit'    => false,
        'cetak'    => false,
        'tindakan' => false,
        'detail'   => true,
        'ubah'     => true,
        'hapus'    => true
    ];

    protected array $konfig = [
        // [visible, Display, Kolom, Jenis, Required, *Opsi]
        [1, 'No. Rekam Medis', 'no_rkm_medis', 'indeks', 1],
        [1, 'Nama Pasien', 'nm_pasien', 'nama', 1],
        [1, 'Jenis Kelamin', 'jk', 'status', 1, [
            ['L', 'Laki-laki'],
            ['P', 'Perempuan']
        ]],
        [1, 'Tanggal Lahir', 'tgl_lahir', 'tanggal', 1],
        [1, 'Umur', 'umur', 'teks', 1],
        [1, 'Gol. Darah', 'gol_darah', 'teks', 0],
        [1, 'Status Nikah', 'stts_nikah', 'status', 0, [
            ['Menikah', 'Menikah'],
            ['Belum Menikah', 'Belum Menikah'],
            ['Duda', 'Duda'],
            ['Janda', 'Janda']
        ]],
        [1, 'Agama', 'agama', 'status', 0, [
            ['Islam', 'Islam'],
            ['Kristen', 'Kristen'],
            ['Katolik', 'Katolik'],
            ['Hindu', 'Hindu'],
            ['Budha', 'Budha'],
            ['Konghucu', 'Konghucu'],
        ]],
        [1, 'Tanggal Meninggal', 'tanggal', 'tanggal', 1],
        [1, 'Jam Meninggal', 'jam', 'jam', 1],
        [1, 'ICD-X Utama', 'icdx', 'teks', 1],
        [1, 'ICD-X Antara 1', 'icdx_antara1', 'teks', 0],
        [1, 'ICD-X Antara 2', 'icdx_antara2', 'teks', 0],
        [1, 'ICD-X Langsung', 'icdx_langsung', 'teks', 0],
        [1, 'Keterangan', 'keterangan', 'teks', 0],
        [1, 'Kode Dokter', 'kode_dokter', 'teks', 0],
        [1, 'Dokter Penanggung Jawab', 'nama_dokter', 'teks', 1],
    ];

    protected array $meta_data = ['page' => 1, 'size' => 10, 'total' => 1];

    public function isiOtomatis($no_rkm_medis)
    {
        $client = \Config\Services::curlrequest();
        $url = base_url('/v1/masterpasien/' . $no_rkm_medis); // asumsi API MasterPasien sudah ada

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . session('jwt') // sesuaikan jika perlu
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data'])) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => [
                    'nm_pasien' => $data['data']['nm_pasien'],
                    'jk'        => $data['data']['jk'],
                    'tgl_lahir' => $data['data']['tgl_lahir']
                ]
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON(['status' => 'not found']);
    }
}
