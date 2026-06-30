<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanDarahDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'pelayanan_darah',
            'permintaan_darah',
            [
                'id_permintaan'        => T::ID(30_000_000),
                'no_permintaan'        => T::RECORD(20),
                'id_registrasi'        => T::FK_AUTO(),
                'id_dokter_pengirim'   => T::FK_AUTO(),
                'tanggal_permintaan'   => T::DTIME(),
                'id_status_permintaan' => T::FK_AUTO(),
            ],
            'id_permintaan',
            ['no_permintaan'],
            [
                [
                    'id_registrasi',
                    \App\Features\Registrasi\Registrasi\RegistrasiDatabase::class,
                    'id_registrasi',
                ],
                [
                    'id_dokter_pengirim',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
                ],
                [
                    'id_status_permintaan',
                    \App\Features\PelayananDarah\StatusPermintaan\StatusPermintaanDatabase::class,
                    'id_status_permintaan',
                ],
            ],
            false,
            'permintaan_darah.csv',
        );
    }
}
