<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPk;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilLabPkDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'hasil_lab_pk',
            [
                'id_hasil_pk'              => T::ID(100_000_000),
                'id_permintaan_lab'        => T::FK_AUTO(),
                'nomor_reg'                => T::FK_AUTO(),
                'kode_dokter_pj'           => T::FK_AUTO(),
                'id_petugas_lab'           => T::FK_AUTO(),
                'kode_dokter_perujuk'      => T::FK_AUTO(),
                'tgl_jam_hasil'            => T::DTIME(),
                'id_kategori_usia'         => T::FK_AUTO(),
                'id_item_pemeriksaan'      => T::FK_AUTO(),
                'id_parameter_pemeriksaan' => T::FK_AUTO(),
                'nilai_hasil'              => T::TEXT(),
                'keterangan_hasil'         => T::NOTE()->nullable(),
            ],
            'id_hasil_pk',
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
                    'id_registrasi',
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
                    ['id_kategori_usia'],
                    \App\Features\Laboratorium\RefKategoriUsiaLab\RefKategoriUsiaLabDatabase::class,
                    ['id_kategori_usia'],
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
