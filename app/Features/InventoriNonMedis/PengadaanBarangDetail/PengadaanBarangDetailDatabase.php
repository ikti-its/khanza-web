<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarangDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengadaanBarangDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'pengadaan_barang_detail',
            [
                'id_detail'    => T::ID(250_000),
                'id_pengadaan' => T::FK_AUTO(),
                'id_barang'    => T::FK_AUTO(),
                'qty'          => T::QTY(0, 100_000),
                'harga_satuan' => T::MONEY()->nullable(),
                'subtotal'     => T::MONEY()->nullable(),
            ],
            'id_detail',
            [],
            [
                [
                    'id_pengadaan',
                    \App\Features\InventoriNonMedis\PengadaanBarang\PengadaanBarangDatabase::class,
                    'id_pengadaan',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            true,
            'pengadaan_barang_detail.csv',
        );
    }
}
