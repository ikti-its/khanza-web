<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostop;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class ChecklistPostopDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'checklist_postop',
            [
                'id_checklist_post'    => T::ID(300_000_000),
                'id_jadwal'            => T::FK_AUTO(),
                'waktu_checklist'      => T::DTIME(),
                'id_tindakan'          => T::FK_AUTO(),
                'id_sn_cn'             => T::FK_AUTO(),
                'id_dokter_bedah'      => T::FK_AUTO(),
                'id_dokter_anestesi'   => T::FK_AUTO(),
                'id_kesadaran_pascaop' => T::FK_AUTO(),
                'jenis_cairan_infus'   => T::TEXT(),
                'id_jaringan_pa_vc'    => T::FK_AUTO(),
                'id_kateter_urine'     => T::FK_AUTO(),
                'waktu_pasang_kateter' => T::TIME(),
                'id_warna_urine'       => T::FK_AUTO(),
                'jumlah_urine_cc'      => T::VITAL(0, 10_000),
                'catatan_luka_operasi' => T::NOTE(),
                'id_petugas_anestesi'  => T::FK_AUTO(),
                'id_petugas_ok'        => T::FK_AUTO(),
            ],
            'id_checklist_post',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_tindakan'],
                    \App\Features\Operasi\RefTindakanOperasi\RefTindakanOperasiDatabase::class,
                    ['id_tindakan'],
                ],
                [
                    ['id_sn_cn'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
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
                    ['id_kesadaran_pascaop'],
                    \App\Features\Operasi\RefKesadaranPascaop\RefKesadaranPascaopDatabase::class,
                    ['id_kesadaran'],
                ],
                [
                    ['id_jaringan_pa_vc'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_kateter_urine'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_warna_urine'],
                    \App\Features\Operasi\RefWarnaUrine\RefWarnaUrineDatabase::class,
                    ['id_warna_urine'],
                ],
                [
                    ['id_petugas_anestesi'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_petugas_ok'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
            ],
        );
    }
}
