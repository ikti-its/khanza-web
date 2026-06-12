<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarangDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanBarangDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'permintaan_barang_detail',
            [
                'id_detail'        => T::ID(500_000),
                'id_permintaan'    => T::FK_AUTO(),
                'id_barang'        => T::FK_AUTO()->nullable(),
                'nama_barang_baru' => T::NAME(100)->nullable(),
                'qty'              => T::QTY(0, 100_000),
                'qty_disetujui'    => T::QTY(0, 100_000)->nullable(),
                'catatan'          => T::NOTE()->nullable(),
            ],
            'id_detail',
            [],
            [
                [
                    'id_permintaan',
                    \App\Features\InventoriNonMedis\PermintaanBarang\PermintaanBarangDatabase::class,
                    'id_permintaan',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            true,
            'permintaan_barang_detail.csv',
        );
    }
}
