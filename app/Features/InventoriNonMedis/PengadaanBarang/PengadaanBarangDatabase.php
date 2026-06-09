<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengadaanBarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'pengadaan_barang',
            [
                'id_pengadaan'               => T::ID(50_000),
                'no_pengadaan'               => T::CODE(20)->nullable(),
                'id_pengajuan'               => T::FK_AUTO(),
                'id_suplier'                 => T::FK_AUTO(),
                'tanggal'                    => T::DTIME(),
                'id_status_pengadaan_barang' => T::FK_AUTO(),
                'catatan'                    => T::NOTE()->nullable(),
                'total_harga'               => T::MONEY()->nullable(),
            ],
            'id_pengadaan',
            [],
            [
                [
                    'id_pengajuan',
                    \App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangDatabase::class,
                    'id_pengajuan',
                ],
                [
                    'id_suplier',
                    \App\Features\InventoriNonMedis\Suplier\SuplierDatabase::class,
                    'id_suplier',
                ],
                [
                    'id_status_pengadaan_barang',
                    \App\Features\InventoriNonMedis\Lookup\StatusPengadaanBarang\StatusPengadaanBarangDatabase::class,
                    'id_status_pengadaan_barang',
                ],
            ],
            true,
            'pengadaan_barang.csv',
        );
    }
}
