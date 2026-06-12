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
                'id_permintaan'        => T::FK_AUTO(),
                'id_ruangan'           => T::FK_AUTO(),
                'id_tindakan'          => T::FK_AUTO(),
                'id_dokter_bedah'      => T::FK_AUTO(),
                'id_dokter_anestesi'   => T::FK_AUTO(),
                'tanggal'              => T::DATE(),
                'waktu_mulai'          => T::TIME(),
                'waktu_selesai'        => T::TIME(),
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
                    ['id_tindakan'],
                    \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiDatabase::class,
                    ['id_tindakan'],
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
