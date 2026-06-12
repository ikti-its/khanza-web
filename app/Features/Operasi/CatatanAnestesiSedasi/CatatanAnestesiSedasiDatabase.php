<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class CatatanAnestesiSedasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'catatan_anestesi_sedasi',
            [
                'id_catatan_anestesi'  => T::ID(300_000_000),
                'id_jadwal'            => T::FK_AUTO(),
                'waktu_catatan'        => T::DTIME(),
                'id_tindakan'          => T::FK_AUTO(),
                'diagnosa_pra_bedah'   => T::TEXT(),
                'diagnosa_paska_bedah' => T::TEXT(),
                'id_dokter_anestesi'   => T::FK_AUTO(),
                'id_dokter_bedah'      => T::FK_AUTO(),
                'id_perawat_anestesi'  => T::FK_AUTO(),
                'id_perawat_bedah'     => T::FK_AUTO(),
                'jam_pengkajian'       => T::TIME(),
                'id_kesadaran'         => T::FK_AUTO(),
                'sistolik'             => T::VITAL(0, 300),
                'diastolik'            => T::VITAL(0, 200),
                'nadi'                 => T::VITAL(0, 300),
                'respiratory_rate'     => T::VITAL(0, 100),
                'suhu'                 => T::TEMP(),
                'saturasi_o2'          => T::VITAL(0, 100),
                'tinggi_badan_cm'      => T::BODY(),
                'berat_badan_kg'       => T::BODY(),
                'id_golongan_darah'    => T::FK_AUTO(),
                'id_rhesus'            => T::FK_AUTO(),
                'hemoglobin'           => T::LAB(),
                'hematokrit'           => T::LAB(),
                'leukosit'             => T::VITAL(0, 1_000_000),
                'trombosit'            => T::VITAL(0, 1_000_000),
                'bleeding_time_bt'     => T::VITAL(0, 60),
                'clotting_time_ct'     => T::VITAL(0, 60),
                'gula_darah_sewaktu'   => T::VITAL(0, 2_000),
                'klinis_lain_lain'     => T::NOTE(),
                'id_asa'               => T::FK_AUTO(),
                'is_alergi'            => T::BOOL(),
                'ket_alergi'           => T::NOTE(),
                'penyulit_pra'         => T::NOTE(),
                'is_lanjut_tindakan'   => T::BOOL(),
                'id_jenis_sedasi'      => T::FK_AUTO(),
                'ket_sedasi'           => T::NOTE(),
                'is_epidural'          => T::BOOL(),
                'is_spinal'            => T::BOOL(),
                'is_anestesi_umum'     => T::BOOL(),
                'ket_anestesi_umum'    => T::NOTE(),
                'is_blok_perifer'      => T::BOOL(),
                'ket_blok_perifer'     => T::NOTE(),
                'is_batal_tindakan'    => T::BOOL(),
                'alasan_batal'         => T::NOTE(),
            ],
            'id_catatan_anestesi',
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
                    ['id_dokter_anestesi'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_dokter_bedah'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_perawat_anestesi'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_perawat_bedah'],
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    ['id_petugas'],
                ],
                [
                    ['id_golongan_darah'],
                    \App\Features\Darah\GolonganDarah\GolonganDarahDatabase::class,
                    ['id_golongan_darah'],
                ],
                [
                    ['id_rhesus'],
                    \App\Features\Darah\Rhesus\RhesusDatabase::class,
                    ['id_rhesus'],
                ],
                [
                    ['id_kesadaran'],
                    \App\Features\Operasi\RefKesadaran\RefKesadaranDatabase::class,
                    ['id_kesadaran'],
                ],
                [
                    ['id_asa'],
                    \App\Features\Operasi\RefAngkaAsa\RefAngkaAsaDatabase::class,
                    ['id_asa'],
                ],
                [
                    ['id_jenis_sedasi'],
                    \App\Features\Operasi\RefJenisSedasi\RefJenisSedasiDatabase::class,
                    ['id_jenis_sedasi'],
                ],
            ],
        );
    }
}
