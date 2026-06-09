<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStokDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class TransaksiStokDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'transaksi_stok_detail',
            [
                'id_detail'    => T::ID(5_000_000),
                'id_transaksi' => T::FK_AUTO(),
                'id_barang'    => T::FK_AUTO(),
                'qty'          => T::QTY(0, 100_000),
                'harga_satuan' => T::MONEY()->nullable(),
                'stok_sebelum' => T::QTY(0, 1_000_000),
                'stok_sesudah' => T::QTY(0, 1_000_000),
            ],
            'id_detail',
            [],
            [
                [
                    'id_transaksi',
                    \App\Features\InventoriNonMedis\TransaksiStok\TransaksiStokDatabase::class,
                    'id_transaksi',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            true,
            'transaksi_stok_detail.csv',
        );
    }
}
