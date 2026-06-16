<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusakDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenunjangRusakDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'logistik_utd',
            'penunjang_rusak_detail',
            [
                'id_penunjang_rusak_detail' => T::ID(10_000_000),
                'id_penunjang_rusak'        => T::FK_AUTO(),
                'id_barang'                 => T::FK_AUTO(),
                'jumlah'                    => T::QTY(1, 999),
                'harga_beli'                => T::MONEY(),
            ],
            'id_penunjang_rusak_detail',
            [],
            [
                [
                    'id_penunjang_rusak',
                    \App\Features\LogistikUTD\PenunjangRusak\PenunjangRusakDatabase::class,
                    'id_penunjang_rusak',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            false,
            'penunjang_rusak_detail.csv',
        );
    }
}
