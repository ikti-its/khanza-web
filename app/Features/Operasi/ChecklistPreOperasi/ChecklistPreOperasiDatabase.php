<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class ChecklistPreOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'checklist_pre_operasi',
            [
                'id_checklist'           => T::ID(300_000_000),
                'id_jadwal'              => T::FK_AUTO(),
                'waktu_checklist'        => T::DTIME(),
                'id_tindakan'            => T::FK_AUTO(),
                'id_sn_cn'               => T::FK_AUTO(),
                'id_dokter_bedah'        => T::FK_AUTO(),
                'id_dokter_anestesi'     => T::FK_AUTO(),
                'is_identitas_sesuai'    => T::BOOL(),
                'id_keadaan_umum'        => T::FK_AUTO(),
                'id_penandaan_area'      => T::FK_AUTO(),
                'is_ijin_bedah'          => T::BOOL(),
                'is_ijin_anestesi'       => T::BOOL(),
                'id_ijin_transfusi'      => T::FK_AUTO(),
                'id_persiapan_darah'     => T::FK_AUTO(),
                'ket_persiapan_darah'    => T::NOTE(),
                'id_perlengkapan_khusus' => T::FK_AUTO(),
                'id_petugas_ruangan'     => T::FK_AUTO(),
                'id_petugas_ok'          => T::FK_AUTO(),
            ],
            'id_checklist',
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
                    ['id_keadaan_umum'],
                    \App\Features\Operasi\RefKeadaanUmum\RefKeadaanUmumDatabase::class,
                    ['id_keadaan_umum'],
                ],
                [
                    ['id_penandaan_area'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_ijin_transfusi'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_persiapan_darah'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_perlengkapan_khusus'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
                [
                    ['id_petugas_ruangan'],
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
