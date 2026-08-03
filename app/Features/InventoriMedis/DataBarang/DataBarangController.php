<?php
declare(strict_types=1);

namespace App\Features\InventoriMedis\DataBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class DataBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataBarangModel(),
            [
                ['Inventori Medis', 'inventori_medis'],
                ['Data Barang',     'data_barang'],
            ],
            'Data Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_barang',     'ID Barang'],
                [SHOW, REQUIRED, I::TEXT,   'kode_barang',   'Kode Barang'],
                [SHOW, REQUIRED, I::NAME,   'nama',          'Nama'],
                [SHOW, REQUIRED, I::NUMBER, 'isi',           'Isi'],
                [HIDE, REQUIRED, I::NUMBER, 'kapasitas',     'Kapasitas'],
                [HIDE, OPTIONAL, I::TEXT,   'kode_industri', 'Kode Industri'],
                [HIDE, REQUIRED, I::TEXT,   'kode_satbesar', 'Kode Satuan Besar'],
                [HIDE, OPTIONAL, I::TEXT,   'kode_sat',      'Kode Satuan'],
                [SHOW, OPTIONAL, I::TEXT,   'kode_jenis',    'Kode Jenis'],
                [HIDE, OPTIONAL, I::TEXT,   'kode_kategori', 'Kode Kategori'],
                [HIDE, OPTIONAL, I::TEXT,   'kode_golongan', 'Kode Golongan'],
                [SHOW, REQUIRED, I::MONEY,  'h_dasar',       'Harga Dasar'],
                [SHOW, OPTIONAL, I::MONEY,  'h_beli',        'Harga Beli'],
                [HIDE, OPTIONAL, I::MONEY,  'h_ralan',       'Harga Ralan'],
                [HIDE, OPTIONAL, I::MONEY,  'h_kelas1',      'Harga Kelas 1'],
                [HIDE, OPTIONAL, I::MONEY,  'h_kelas2',      'Harga Kelas 2'],
                [HIDE, OPTIONAL, I::MONEY,  'h_kelas3',      'Harga Kelas 3'],
                [HIDE, OPTIONAL, I::MONEY,  'h_utama',       'Harga Utama'],
                [HIDE, OPTIONAL, I::MONEY,  'h_vip',         'Harga VIP'],
                [HIDE, OPTIONAL, I::MONEY,  'h_vvip',        'Harga VVIP'],
                [HIDE, OPTIONAL, I::MONEY,  'h_beliluar',    'Harga Beli Luar'],
                [HIDE, OPTIONAL, I::MONEY,  'h_jualbebas',   'Harga Jual Bebas'],
                [HIDE, OPTIONAL, I::MONEY,  'h_karyawan',    'Harga Karyawan'],
                [HIDE, OPTIONAL, I::NUMBER, 'stok_minimum',  'Stok Minimum'],
                [SHOW, REQUIRED, I::DATE,   'kadaluwarsa',   'Kadaluwarsa'],
            ],
        );
    }

    public function list(): ResponseInterface
    {
        $rows = $this->model
            ->db
            ->table('inventori_medis.data_barang')
            ->select(['id_barang', 'kode_barang', 'nama', 'h_dasar'])
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
