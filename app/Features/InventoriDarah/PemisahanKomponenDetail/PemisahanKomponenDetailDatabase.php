<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponenDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PemisahanKomponenDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_darah',
            'pemisahan_komponen_detail',
            [
                'id_pemisahan_detail' => T::ID(300_000_000),
                'id_pemisahan'        => T::FK_AUTO(),
                'no_kantong'          => T::RECORD(25),
                'id_komponen'         => T::FK_AUTO(),
                'tanggal_kadaluarsa'  => T::EXPIRY(),
            ],
            'id_pemisahan_detail',
            ['no_kantong'],
            [
                [
                    'id_pemisahan',
                    \App\Features\InventoriDarah\PemisahanKomponen\PemisahanKomponenDatabase::class,
                    'id_pemisahan',
                ],
                [
                    'id_komponen',
                    \App\Features\InventoriDarah\KomponenDarah\KomponenDarahDatabase::class,
                    'id_komponen',
                ],
            ],
            false,
            'pemisahan_komponen_detail.csv',
        );
    }
}
