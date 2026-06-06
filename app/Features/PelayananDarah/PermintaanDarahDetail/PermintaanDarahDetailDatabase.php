<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarahDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanDarahDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'pelayanan_darah',
            'permintaan_darah_detail',
            [
                'id_permintaan_detail' => T::ID(100_000_000),
                'id_permintaan'        => T::FK_AUTO(),
                'id_komponen'          => T::FK_AUTO(),
                'id_golongan_darah'    => T::FK_AUTO(),
                'id_rhesus'            => T::FK_AUTO(),
                'jumlah'               => T::QTY(1, 99),
            ],
            'id_permintaan_detail',
            [
                ['id_permintaan', 'id_komponen', 'id_golongan_darah', 'id_rhesus'],
            ],
            [
                [
                    'id_permintaan',
                    \App\Features\PelayananDarah\PermintaanDarah\PermintaanDarahDatabase::class,
                    'id_permintaan',
                ],
                [
                    'id_komponen',
                    \App\Features\Darah\KomponenDarah\KomponenDarahDatabase::class,
                    'id_komponen',
                ],
                [
                    'id_golongan_darah',
                    \App\Features\Darah\GolonganDarah\GolonganDarahDatabase::class,
                    'id_golongan_darah',
                ],
                [
                    'id_rhesus',
                    \App\Features\Darah\Rhesus\RhesusDatabase::class,
                    'id_rhesus',
                ],
            ],
            false,
            'permintaan_darah_detail.csv',
        );
    }
}
