<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\Registrasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RegistrasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rekam_medis',
            'registrasi',
            [
                'id_registrasi'     => T::ID(100_000_000),
                'nomor_reg'         => T::RECORD(20),
                'nomor_rawat'       => T::RECORD(20),
                'datetime'          => T::DTIME(),
                'id_dokter'         => T::FK_AUTO(),
                'id_pasien'         => T::FK_AUTO(),
                'id_pj_pasien'      => T::FK_AUTO(),
                'id_alamat_pj'      => T::FK_AUTO(),
                'poliklinik'        => T::FK_AUTO(),
                'hubungan_pj'       => T::NAME(50),
                'no_telepon'        => T::NAME(20),
                'biaya_registrasi'  => T::MONEY(),
                'jenis_bayar'       => T::NAME(50),
                'status_registrasi' => T::NAME(50),
                'status_rawat'      => T::NAME(50),
                'status_poli'       => T::NAME(50),
                'status_bayar'      => T::NAME(50),
            ],
            'id_registrasi',
            ['nomor_reg'],
            [
                [
                    'id_dokter',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
                ],
                [
                    'id_pasien',
                    \App\Features\Role\Pasien\PasienDatabase::class,
                    'id_pasien',
                ],
                [
                    'id_pj_pasien',
                    \App\Features\Person\Orang\OrangDatabase::class,
                    'id_orang',
                ],
                [
                    ['id_alamat_pj'],
                    \App\Features\Lokasi\Alamat\AlamatDatabase::class,
                    ['id_alamat']
                ],
                [
                    ['poliklinik'],
                    \App\Features\Poliklinik\PoliklinikDatabase::class,
                    ['id_poliklinik'],
                ],
            ],
            false,
            'registrasi.csv'
        );
    }
}
