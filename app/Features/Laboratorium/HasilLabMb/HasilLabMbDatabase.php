<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMb;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilLabMbDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'hasil_lab_mb',
            [
                'id_hasil_mb'           => T::ID(100_000_000),
                'id_permintaan_lab'     => T::FK_AUTO(),
                'id_permintaan_mb_item' => T::FK_AUTO(),
                'id_dokter_pj'          => T::FK_AUTO(),
                'id_petugas_lab'        => T::FK_AUTO(),
                'tgl_jam_hasil'         => T::DTIME(),
                'nilai_hasil'           => T::TEXT(),
                'keterangan_hasil'      => T::NOTE(),
            ],
            'id_hasil_mb',
            [],
            [
                [
                    ['id_permintaan_lab'],
                    \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderDatabase::class,
                    ['id_permintaan'],
                ],
                [
                    ['id_permintaan_mb_item'],
                    \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemDatabase::class,
                    ['id_permintaan_mb_item'],
                ],
                [
                    'id_dokter_pj',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
                ],
                [
                    'id_petugas_lab',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
        );
    }
}
