<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisRusakDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class MedisRusakDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'logistik_utd',
            'medis_rusak_detail',
            [
                'id_medis_rusak_detail' => T::ID(10_000_000),
                'id_medis_rusak'        => T::FK_AUTO(),
                'id_barang'             => T::FK_AUTO(),
                'jumlah'                => T::QTY(1, 999),
                'harga_beli'            => T::MONEY(),
            ],
            'id_medis_rusak_detail',
            [],
            [
                [
                    'id_medis_rusak',
                    \App\Features\LogistikUTD\MedisRusak\MedisRusakDatabase::class,
                    'id_medis_rusak',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriMedis\DataBarang\DataBarangDatabase::class,
                    'id_barang',
                ],
            ],
        );
    }
}
