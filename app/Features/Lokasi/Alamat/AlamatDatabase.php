<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Alamat;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class AlamatDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'lokasi',
            'alamat',
            [
                'id_alamat'      => T::ID(300_000_000),
                'id_provinsi'    => T::FK_AUTO(),
                'id_kota_lokal'  => T::FK_AUTO(),
                'id_kec_lokal'   => T::FK_AUTO(),
                'id_desa_lokal'  => T::FK_AUTO(),
                'rw'             => T::INT(1, 10)->nullable(),
                'rt'             => T::INT(1, 10)->nullable(),
                'alamat_lengkap' => T::TEXT(),
            ],
            'id_alamat',
            [],
            [
                [
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal', 'id_desa_lokal'],
                    \App\Features\Lokasi\Desa\DesaDatabase::class,
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal', 'id_desa_lokal'],
                ],
                [
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal'],
                    \App\Features\Lokasi\Kecamatan\KecamatanDatabase::class,
                    ['id_provinsi', 'id_kota_lokal', 'id_kec_lokal'],
                ],
                [
                    ['id_provinsi', 'id_kota_lokal'],
                    \App\Features\Lokasi\Kota\KotaDatabase::class,
                    ['id_provinsi', 'id_kota_lokal'],
                ],
                [
                    'id_provinsi',
                    \App\Features\Lokasi\Provinsi\ProvinsiDatabase::class,
                    'id_provinsi',
                ],
            ],
            false,
            'alamat.csv',
        );
    }
}
