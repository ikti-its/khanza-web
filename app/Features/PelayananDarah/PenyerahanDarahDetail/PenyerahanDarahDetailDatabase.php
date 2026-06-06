<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PenyerahanDarahDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenyerahanDarahDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'pelayanan_darah',
            'penyerahan_darah_detail',
            [
                'id_penyerahan_detail' => T::ID(100_000_000),
                'id_penyerahan'        => T::FK_AUTO(),
                'id_stok_darah'        => T::FK_AUTO(),
                'jasa_sarana'          => T::MONEY(),
                'paket_bhp'            => T::MONEY(),
                'kso'                  => T::MONEY(),
                'manajemen'            => T::MONEY(),
                // 'total'                    => T::MONEY(),
            ],
            'id_penyerahan_detail',
            ['id_stok_darah'],
            [
                [
                    'id_penyerahan',
                    \App\Features\PelayananDarah\PenyerahanDarah\PenyerahanDarahDatabase::class,
                    'id_penyerahan',
                ],
                [
                    'id_stok_darah',
                    \App\Features\InventoriDarah\StokDarah\StokDarahDatabase::class,
                    'id_stok_darah',
                ],
            ],
            false,
            'penyerahan_darah_detail.csv',
        );
    }
}
