<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\SkriningRawatJalan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SkriningRawatJalanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'skrining_rj',
            'skrining_rawat_jalan',
            [
                'id_skrining'      => T::ID(100_000_000),
                'no_rm'            => T::FK_AUTO(),
                'tgl_skrining'     => T::DATE(),
                'jam_skrining'     => T::TIME(),
                'id_kesadaran'     => T::FK_AUTO(),
                'id_pernafasan'    => T::FK_AUTO(),
                'id_skala_nyeri'   => T::FK_AUTO(),
                'id_nyeri_dada'    => T::FK_AUTO(),
                'id_batuk'         => T::FK_AUTO(),
                'is_geriatri'      => T::BOOL(),
                'is_risiko_jatuh'  => T::BOOL(),
                'id_keputusan'     => T::FK_AUTO(),
                'id_petugas'       => T::FK_AUTO(),
            ],
            'id_skrining',
            [],
            [
                [
                    ['no_rm'], 
                    \App\Features\Role\Pasien\PasienDatabase::class,
                    ['nomor_rm'],
                ],
                [
                    ['id_kesadaran'],
                    \App\Features\SkriningRawatJalan\RefSkriningKesadaran\RefSkriningKesadaranDatabase::class,
                    ['id_kesadaran'],
                ],
                [
                    ['id_pernafasan'],
                    \App\Features\SkriningRawatJalan\RefSkriningPernafasan\RefSkriningPernafasanDatabase::class,
                    ['id_pernafasan'],
                ],
                [
                    ['id_skala_nyeri'],
                    \App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri\RefSkriningSkalaNyeriDatabase::class,
                    ['id_skala_nyeri'],
                ],
                [
                    ['id_nyeri_dada'],
                    \App\Features\SkriningRawatJalan\RefSkriningNyeriDada\RefSkriningNyeriDadaDatabase::class,
                    ['id_nyeri_dada'],
                ],
                [
                    ['id_batuk'],
                    \App\Features\SkriningRawatJalan\RefSkriningBatuk\RefSkriningBatukDatabase::class,
                    ['id_batuk'],
                ],
                [
                    ['id_keputusan'],
                    \App\Features\SkriningRawatJalan\RefSkriningKeputusan\RefSkriningKeputusanDatabase::class,
                    ['id_keputusan'],
                ],
                [
                    ['id_petugas'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
            ],
        );
    }
}
