<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\UpahMinimumProvinsi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class UpahMinimumProvinsiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penggajian',
            'ump',
            [
                'no_ump'       => T::ID(3000),
                'tahun'        => T::YEAR('tahun'),
                'provinsi'     => T::FK_AUTO(),
                'upah_minimum' => T::MONEY(),
            ],
            'no_ump',
            [
                ['tahun', 'provinsi'],
            ],
            [
                [
                    'provinsi',
                    \App\Features\Lokasi\Provinsi\ProvinsiDatabase::class,
                    'id_provinsi',
                ],
            ],
            true,
            'ump.csv',
        );
    }
}
