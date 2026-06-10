<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpnameDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StokOpnameDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'stok_opname_detail',
            [
                'id_detail'   => T::ID(100_000),
                'id_opname'   => T::FK_AUTO(),
                'id_barang'   => T::FK_AUTO(),
                'stok_sistem' => T::QTY(0, 1_000_000),
                'stok_fisik'  => T::QTY(0, 1_000_000),
                'selisih'     => T::QTY(-1_000_000, 1_000_000),
                'catatan'     => T::NOTE()->nullable(),
            ],
            'id_detail',
            [],
            [
                [
                    'id_opname',
                    \App\Features\InventoriNonMedis\StokOpname\StokOpnameDatabase::class,
                    'id_opname',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            true,
            'stok_opname_detail.csv',
        );
    }
}
