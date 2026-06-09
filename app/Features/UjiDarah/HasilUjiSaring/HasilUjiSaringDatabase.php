<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilUjiSaring;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilUjiSaringDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'uji_darah',
            'hasil_uji_saring',
            [
                'id_uji_saring'        => T::ID(100_000_000),
                'id_pengambilan_darah' => T::FK_AUTO(),
                'tanggal_uji'          => T::DATE(),
                'id_metode_uji'        => T::FK_AUTO(),
                'id_petugas'           => T::FK_AUTO(),
                'hbsag'                => T::BOOL(),
                'hcv'                  => T::BOOL(),
                'hiv'                  => T::BOOL(),
                'sifilis'              => T::BOOL(),
                'malaria'              => T::BOOL()->nullable(),
            ],
            'id_uji_saring',
            ['id_pengambilan_darah'],
            [
                [
                    'id_pengambilan_darah',
                    \App\Features\Donor\PengambilanDarah\PengambilanDarahDatabase::class,
                    'id_pengambilan_darah',
                ],
                [
                    'id_metode_uji',
                    \App\Features\UjiDarah\MetodeUji\MetodeUjiDatabase::class,
                    'id_metode_uji',
                ],
                [
                    'id_petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            false,
            'hasil_uji_saring.csv',
        );
    }
}
