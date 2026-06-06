<?php
declare(strict_types=1);

namespace App\Features\Role\Dokter;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class DokterDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'role',
            'dokter',
            [
                'id_dokter'   => T::ID(1_000_000),
                'id_orang'    => T::FK_AUTO(),
                'kode_dokter' => T::CODE(10),
                'spesialis'   => T::TEXT(),
            ],
            'id_dokter',
            [
                ['id_orang', 'spesialis'],
                ['kode_dokter'],
            ],
            [
                [
                    ['id_orang'],
                    \App\Features\Person\Orang\OrangDatabase::class,
                    ['id_orang'],
                ],
            ],
            false,
            'dokter.csv',
        );
    }
}
