<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class TagihanOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'tagihan_operasi',
            [
                'id_tagihan'         => T::ID(300_000_000),
                'id_jadwal'          => T::FK_AUTO(),
                'id_kategori'        => T::FK_AUTO(),
                'jenis_anestesi'     => T::TEXT(),
                'tanggal_mulai'      => T::DTIME()->nullable(),
                'tanggal_selesai'    => T::DTIME()->nullable(),
                'total_tagihan'      => T::MONEY(),
                'diagnosis_pre'      => T::TEXT()->nullable(),
                'diagnosis_post'     => T::TEXT()->nullable(),
                'jaringan'           => T::TEXT()->nullable(),
                'laporan'            => T::TEXT()->nullable(),
                'is_pa'              => T::BOOL(),
                'id_operator_1'      => T::FK_AUTO()->nullable(),
                'id_operator_2'      => T::FK_AUTO()->nullable(),
                'id_operator_3'      => T::FK_AUTO()->nullable(),
                'id_dokter_anestesi' => T::FK_AUTO()->nullable(),
                'id_dokter_anak'     => T::FK_AUTO()->nullable(),
                'id_dokter_pj_anak'  => T::FK_AUTO()->nullable(),
                'id_dokter_umum'     => T::FK_AUTO()->nullable(),
                'id_ast_operator_1'  => T::FK_AUTO()->nullable(),
                'id_ast_operator_2'  => T::FK_AUTO()->nullable(),
                'id_ast_operator_3'  => T::FK_AUTO()->nullable(),
                'id_bidan_1'         => T::FK_AUTO()->nullable(),
                'id_bidan_2'         => T::FK_AUTO()->nullable(),
                'id_bidan_3'         => T::FK_AUTO()->nullable(),
                'id_perawat_luar'    => T::FK_AUTO()->nullable(),
                'id_instrumen'       => T::FK_AUTO()->nullable(),
                'id_ast_anestesi_1'  => T::FK_AUTO()->nullable(),
                'id_ast_anestesi_2'  => T::FK_AUTO()->nullable(),
                'id_perawat_resus'   => T::FK_AUTO()->nullable(),
                'id_onloop_1'        => T::FK_AUTO()->nullable(),
                'id_onloop_2'        => T::FK_AUTO()->nullable(),
                'id_onloop_3'        => T::FK_AUTO()->nullable(),
                'id_onloop_4'        => T::FK_AUTO()->nullable(),
                'id_onloop_5'        => T::FK_AUTO()->nullable(),
            ],
            'id_tagihan',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_kategori'],
                    \App\Features\Operasi\RefKategoriOperasi\RefKategoriOperasiDatabase::class,
                    ['id_kategori'],
                ],
                [
                    ['id_operator_1'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_operator_2'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_operator_3'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_anak'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_pj_anak'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_umum'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_ast_operator_1'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_ast_operator_2'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_ast_operator_3'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_bidan_1'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_bidan_2'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_bidan_3'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_perawat_luar'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_instrumen'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_ast_anestesi_1'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_ast_anestesi_2'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_perawat_resus'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_onloop_1'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_onloop_2'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_onloop_3'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_onloop_4'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_onloop_5'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
            ],
        );
    }
}
