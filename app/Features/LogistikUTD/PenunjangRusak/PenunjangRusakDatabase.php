<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusak;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenunjangRusakDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'logistik_utd',
            'penunjang_rusak',
            [
                'id_penunjang_rusak' => T::ID(10_000_000),
                'id_petugas'         => T::FK_AUTO(),
                'tanggal_rusak'      => T::DTIME(),
                'keterangan'         => T::NOTE(),
            ],
            'id_penunjang_rusak',
            [],
            [
                [
                    'id_petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            false,
            'penunjang_rusak.csv',
        );
    }
}
