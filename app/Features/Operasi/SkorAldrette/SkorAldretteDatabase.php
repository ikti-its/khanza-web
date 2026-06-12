<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorAldrette;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class SkorAldretteDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'skor_aldrette',
            [
                'id_skor_aldrette'   => T::ID(300_000_000),
                'id_jadwal'          => T::FK_AUTO(),
                'waktu_penilaian'    => T::DTIME(),
                'id_petugas'         => T::FK_AUTO(),
                'id_dokter_anestesi' => T::FK_AUTO(),
                'skor_aktivitas'     => T::FK_AUTO(),
                'skor_respirasi'     => T::FK_AUTO(),
                'skor_tekanan_darah' => T::FK_AUTO(),
                'skor_kesadaran'     => T::FK_AUTO(),
                'skor_warna_kulit'   => T::FK_AUTO(),
                // 'total_skor'          => T::SCORE(),
                'is_boleh_pindah' => T::BOOL(),
                'catatan_keluar'  => T::NOTE(),
                'instruksi_rr'    => T::NOTE(),
            ],
            'id_skor_aldrette',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
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
                    ['skor_aktivitas'],
                    \App\Features\Operasi\RefAldretteAktivitas\RefAldretteAktivitasDatabase::class,
                    ['id_aktivitas'],
                ],
                [
                    ['skor_respirasi'],
                    \App\Features\Operasi\RefAldretteRespirasi\RefAldretteRespirasiDatabase::class,
                    ['id_respirasi'],
                ],
                [
                    ['skor_tekanan_darah'],
                    \App\Features\Operasi\RefAldretteTekananDarah\RefAldretteTekananDarahDatabase::class,
                    ['id_td'],
                ],
                [
                    ['skor_kesadaran'],
                    \App\Features\Operasi\RefAldretteKesadaran\RefAldretteKesadaranDatabase::class,
                    ['id_kesadaran'],
                ],
                [
                    ['skor_warna_kulit'],
                    \App\Features\Operasi\RefAldretteWarnaKulit\RefAldretteWarnaKulitDatabase::class,
                    ['id_warna'],
                ],
            ],
        );
    }
}
