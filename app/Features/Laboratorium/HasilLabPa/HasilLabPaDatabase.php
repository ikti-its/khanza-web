<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPa;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilLabPaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'hasil_lab_pa',
            [
                'id_hasil_pa'         => T::ID(100_000_000),
                'id_permintaan_lab'   => T::FK_AUTO(),
                'id_dokter_pj'        => T::FK_AUTO(),
                'id_petugas_lab'      => T::FK_AUTO(),
                'tgl_jam_hasil'       => T::DTIME(),
                'id_permintaan_pa_item' => T::FK_AUTO(),
                'diagnosa_klinis'     => T::TEXT(),
                'makroskopik'         => T::TEXT(),
                'mikroskopik'         => T::TEXT(),
                'kesimpulan'          => T::NOTE(),
                'kesan'               => T::NOTE()->nullable(),
            ],
            'id_hasil_pa',
            [],
            [
                [
                    ['id_permintaan_lab'],
                    \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderDatabase::class,
                    ['id_permintaan'],
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
                [
                    ['id_permintaan_pa_item'],
                    \App\Features\Laboratorium\RefItemPemeriksaanLab\RefItemPemeriksaanLabDatabase::class,
                    ['id_permintaan_pa_item'],
                ],
            ],
        );
    }
}
