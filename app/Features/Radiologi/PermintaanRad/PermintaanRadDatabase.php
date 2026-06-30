<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanRadDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'radiologi',
            'permintaan_rad',
            [
                'id_permintaan'        => T::ID(100_000_000),
                'no_permintaan'        => T::RECORD(20),
                'nomor_reg'            => T::FK_AUTO(),
                'tgl_jam_permintaan'   => T::DTIME(),
                'informasi_tambahan'   => T::NOTE(),
                'indikasi_klinis'      => T::TEXT(),
                'id_status_permintaan' => T::FK_AUTO(),
                'tgl_jam_sampel'       => T::DTIME()->nullable(),
            ],
            'id_permintaan',
            ['no_permintaan'],
            [
                [
                    ['nomor_reg'],
                    \App\Features\Registrasi\Registrasi\RegistrasiDatabase::class,
                    ['nomor_reg'],
                ],
                [
                    ['id_status_permintaan'],
                    \App\Features\Radiologi\RefStatusPermintaanRad\RefStatusPermintaanRadDatabase::class,
                    ['id_status'],
                ],
            ],
        );
    }
}
