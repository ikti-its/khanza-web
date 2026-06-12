<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorSteward;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SkorStewardDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'skor_steward',
            [
                'id_skor_steward'    => T::ID(300_000_000),
                'nomor_reg'          => T::FK_AUTO(),
                'waktu_penilaian'    => T::DTIME(),
                'id_petugas'         => T::FK_AUTO(),
                'id_dokter_anestesi' => T::FK_AUTO(),
                'skor_kesadaran'     => T::FK_AUTO(),
                'skor_respirasi'     => T::FK_AUTO(),
                'skor_motorik'       => T::FK_AUTO(),
                // 'total_skor'         => T::SCORE(),
                'is_boleh_pindah' => T::BOOL(),
                'catatan_keluar'  => T::TEXT(),
                'instruksi_rr'    => T::TEXT(),
            ],
            'id_skor_steward',
            [],
            [
                [
                    ['nomor_reg'],
                    \App\Features\RekamMedis\Registrasi\RegistrasiDatabase::class,
                    ['id_registrasi'],
                ],
                [
                    ['id_petugas'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['skor_kesadaran'],
                    \App\Features\Operasi\RefStewardKesadaran\RefStewardKesadaranDatabase::class,
                    ['id_kesadaran'],
                ],
                [
                    ['skor_respirasi'],
                    \App\Features\Operasi\RefStewardRespirasi\RefStewardRespirasiDatabase::class,
                    ['id_respirasi'],
                ],
                [
                    ['skor_motorik'],
                    \App\Features\Operasi\RefStewardMotorik\RefStewardMotorikDatabase::class,
                    ['id_motorik'],
                ],
            ],
        );
    }
}
