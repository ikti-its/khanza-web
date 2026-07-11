<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Barang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class BarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'barang',
            [
                'id_barang'       => T::ID(2_000),
                'kode_barang'     => T::CODE(10),
                'nama_barang'     => T::NAME(100),
                'id_satuan'       => T::FK_AUTO(),
                'id_jenis_barang' => T::FK_AUTO(),
                'stok'            => T::QTY(0, 1_000_000),
                'stok_minimum'    => T::QTY(0, 1_000_000)->nullable(),
                'harga_satuan'    => T::MONEY()->nullable(),
            ],
            'id_barang',
            ['kode_barang'],
            [
                [
                    'id_jenis_barang',
                    \App\Features\InventoriNonMedis\JenisBarang\JenisBarangDatabase::class,
                    'id_jenis_barang',
                ],
                [
                    'id_satuan',
                    \App\Features\InventoriNonMedis\Satuan\SatuanDatabase::class,
                    'id_satuan',
                ],
            ],
            true,
            'barang.csv',
        );
    }
}
