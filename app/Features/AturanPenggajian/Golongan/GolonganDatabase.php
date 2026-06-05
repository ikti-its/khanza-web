<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Golongan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class GolonganDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penggajian',
            'golongan',
            [
                'no_golongan'   => T::ID(20),
                'kode_golongan' => T::CODE(4),
                'nama_golongan' => T::NAME(16),
                'pendidikan'    => T::FK_AUTO(),
                'gaji_pokok'    => T::MONEY(),
            ],
            'no_golongan',
            [
                'nama_golongan',
                'kode_golongan',
            ],
            [
                [
                    'pendidikan',
                    \App\Features\Pendidikan\JenjangPendidikan\JenjangPendidikanDatabase::class,
                    'id_jenjang',
                ],
            ],
            true,
            'golongan.csv',
        );
    }
}
