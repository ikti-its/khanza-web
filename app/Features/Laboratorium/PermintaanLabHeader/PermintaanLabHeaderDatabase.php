<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabHeader;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanLabHeaderDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'permintaan_lab_header',
            [
                'id_permintaan'        => T::ID(100_000_000),
                'no_permintaan'        => T::CODE(14),
                'nomor_reg'            => T::FK_AUTO(),
                'id_kategori_lab'      => T::FK_AUTO(),
                'id_dokter_perujuk'    => T::FK_AUTO(),
                'tgl_permintaan'       => T::DTIME(),
                'indikasi_klinis'      => T::TEXT(),
                'informasi_tambahan'   => T::NOTE(),
                'id_status_permintaan' => T::FK_AUTO(),
            ],
            'id_permintaan',
            [],
            [
                [
                    'nomor_reg',
                    \App\Features\RekamMedis\Registrasi\RegistrasiDatabase::class,
                    'nomor_reg',
                ],
                [
                    ['id_kategori_lab'],
                    \App\Features\Laboratorium\RefKategoriLab\RefKategoriLabDatabase::class,
                    ['id_kategori'],
                ],
                [
                    ['id_dokter_perujuk'],
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    ['id_dokter'],
                ],
                [
                    ['id_status_permintaan'],
                    \App\Features\Laboratorium\RefStatusPermintaan\RefStatusPermintaanDatabase::class,
                    ['id_status'],
                ],
            ],
        );
    }
}
