<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JadwalOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'jadwal_operasi',
            [
                'id_jadwal'            => T::ID(300_000_000),
                'nomor_operasi'        => T::TEXT()->nullable(),
                'id_permintaan'        => T::FK_AUTO(),
                'id_ruangan'           => T::FK_AUTO()->nullable(),
                'id_dokter_bedah'      => T::FK_AUTO()->nullable(),
                'id_dokter_anestesi'   => T::FK_AUTO()->nullable(),
                'tanggal'              => T::DATE()->nullable(),
                'waktu_mulai'          => T::TIME()->nullable(),
                'waktu_selesai'        => T::TIME()->nullable(),
                'id_status'            => T::FK_AUTO(),
            ],
            'id_jadwal',
            [],
            [
                [
                    ['id_permintaan'],
                    \App\Features\Operasi\PermintaanOperasi\PermintaanOperasiDatabase::class,
                    ['id_permintaan'],
                ],
                [
                    ['id_ruangan'],
                    \App\Features\Ruangan\RuanganDatabase::class,
                    ['id_ruangan'],
                ],
                [
                    ['id_dokter_bedah'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_status'],
                    \App\Features\Operasi\RefStatusOperasi\RefStatusOperasiDatabase::class,
                    ['id_status'],
                ],
            ],
        );
    }
}
