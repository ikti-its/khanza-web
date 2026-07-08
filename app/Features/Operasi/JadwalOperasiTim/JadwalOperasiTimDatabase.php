<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasiTim;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JadwalOperasiTimDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'jadwal_operasi_tim',
            [
                'id_jadwal_tim' => T::ID(100_000_000),
                'id_jadwal'     => T::FK_AUTO(),
                'id_dokter'     => T::FK_AUTO()->nullable(),
                'id_petugas'    => T::FK_AUTO()->nullable(),
                'id_peran'      => T::FK_AUTO()->nullable(),
            ],
            'id_jadwal_tim',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_dokter'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_petugas'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_peran'],
                    \App\Features\Operasi\RefPeranTimMedis\RefPeranTimMedisDatabase::class,
                    ['id_peran'],
                ],
            ],
        );
    }
}
