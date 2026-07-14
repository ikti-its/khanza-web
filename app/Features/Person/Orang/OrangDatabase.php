<?php
declare(strict_types=1);

namespace App\Features\Person\Orang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

/*
 * Tabel orang digunakan untuk menyimpan data identitas individu
 * yang terdaftar di dalam sistem.
 *
 * Data ini bersifat master dan dapat direlasikan dengan berbagai
 * fitur lain seperti donor, pasien, petugas, atau pengguna sistem.
 */

final class OrangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'person',
            'orang',
            [
                'id_orang'          => T::ID(300_000_000),
                'nik'               => T::CODE(16),
                'nama'              => T::NAME(60),
                'id_jenis_kelamin'  => T::FK_AUTO(),
                'id_agama'          => T::FK_AUTO(),
                'id_pernikahan'     => T::FK_AUTO(),
                'id_golongan_darah' => T::FK_AUTO(),
                'id_alamat'         => T::FK_AUTO(),
                'tempat_lahir_kota' => T::FK_AUTO(),
                'tanggal_lahir'     => T::DATE(),
            ],
            'id_orang',
            ['nik'],
            [
                [
                    'id_jenis_kelamin',
                    \App\Features\Person\JenisKelamin\JenisKelaminDatabase::class,
                    'id_jenis_kelamin',
                ],
                [
                    'id_agama',
                    \App\Features\Person\Agama\AgamaDatabase::class,
                    'id_agama',
                ],
                [
                    'id_pernikahan',
                    \App\Features\Person\Pernikahan\PernikahanDatabase::class,
                    'id_pernikahan',
                ],
                [
                    'id_golongan_darah',
                    \App\Features\Darah\GolonganDarah\GolonganDarahDatabase::class,
                    'id_golongan_darah',
                ],
                [
                    'id_alamat',
                    \App\Features\Lokasi\Alamat\AlamatDatabase::class,
                    'id_alamat',
                ],
                [
                    'tempat_lahir_kota',
                    \App\Features\Lokasi\Kota\KotaDatabase::class,
                    'id_kota',
                ],
            ],
            false,
            'orang.csv',
        );
    }
}
