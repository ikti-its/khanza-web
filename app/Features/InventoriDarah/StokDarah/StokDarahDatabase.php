<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StokDarahDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_darah',
            'stok_darah',
            [
                'id_stok_darah'       => T::ID(300_000_000),
                'no_kantong'          => T::RECORD(20),
                'id_komponen'         => T::FK_AUTO(),
                'id_golongan_darah'   => T::FK_AUTO(),
                'id_rhesus'           => T::FK_AUTO(),
                'tanggal_pengambilan' => T::DATE(),
                'tanggal_kadaluarsa'  => T::EXPIRY(),
                'id_sumber_darah'     => T::FK_AUTO(),
                'id_status_stok'      => T::FK_AUTO(),
            ],
            'id_stok_darah',
            ['no_kantong'],
            [
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
                [
                    'id_sumber_darah',
                    \App\Features\InventoriDarah\SumberDarah\SumberDarahDatabase::class,
                    'id_sumber_darah',
                ],
                [
                    'id_status_stok',
                    \App\Features\InventoriDarah\StatusStok\StatusStokDatabase::class,
                    'id_status_stok',
                ],
            ],
            false,
            'stok_darah.csv',
        );
    }
}
