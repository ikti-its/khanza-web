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
                'id_hasil_mb'              => T::ID(100_000_000),
                'id_permintaan_lab'        => T::FK_AUTO(),
                'nomor_reg'                => T::FK_AUTO(),
                'kode_dokter_pj'           => T::FK_AUTO(),
                'id_petugas_lab'           => T::FK_AUTO(),
                'kode_dokter_perujuk'      => T::FK_AUTO(),
                'tgl_jam_hasil'            => T::DTIME(),
                'id_item_pemeriksaan'      => T::FK_AUTO(),
                'id_parameter_pemeriksaan' => T::FK_AUTO(),
                'nilai_hasil'              => T::TEXT(),
                'keterangan_hasil'         => T::NOTE(),
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
                    'nomor_reg',
                    \App\Features\RekamMedis\Registrasi\RegistrasiDatabase::class,
                    'nomor_reg',
                ],
                [
                    'kode_dokter_pj',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
                ],
                [
                    'id_petugas_lab',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'kode_dokter_perujuk',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
                ],
                [
                    ['id_item_pemeriksaan'],
                    \App\Features\Laboratorium\RefItemPemeriksaanLab\RefItemPemeriksaanLabDatabase::class,
                    ['id_item_lab'],
                ],
                [
                    ['id_parameter_pemeriksaan'],
                    \App\Features\Laboratorium\RefParameterPemeriksaanLab\RefParameterPemeriksaanLabDatabase::class,
                    ['id_parameter'],
                ],
            ],
        );
    }
}
