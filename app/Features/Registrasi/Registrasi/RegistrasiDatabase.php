<?php
declare(strict_types=1);

namespace App\Features\Registrasi\Registrasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RegistrasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'registrasi',
            'registrasi',
            [
                'id_registrasi'     => T::ID(100_000_000),
                'nomor_reg'         => T::RECORD(20),
                'nomor_rawat'       => T::RECORD(20),
                'tanggal_reg'       => T::DTIME(),
                'id_dokter'         => T::FK_AUTO(),
                'id_pasien'         => T::FK_AUTO(),
                'id_pj_pasien'      => T::FK_AUTO(),
                'id_alamat_pj'      => T::FK_AUTO(),
                'unit'              => T::FK_AUTO(),
                'hubungan_pj'       => T::FK_AUTO(),
                'no_telepon'        => T::NAME(20)->nullable(),
                'biaya_registrasi'  => T::MONEY(),
                'jenis_bayar'       => T::FK_AUTO(),
                'status_registrasi' => T::FK_AUTO()->nullable(),
                'status_rawat'      => T::FK_AUTO(),
                'status_poli'       => T::FK_AUTO()->nullable(),
                'status_bayar'      => T::FK_AUTO(),
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
                    ['unit'],
                    \App\Features\Unit\UnitDatabase::class,
                    ['id_unit'],
                ],
                [
                    ['hubungan_pj'],
                    \App\Features\Registrasi\HubunganPj\HubunganPjDatabase::class,
                    ['id_hubungan_pj'],
                ],
                [
                    ['jenis_bayar'],
                    \App\Features\Registrasi\JenisBayar\JenisBayarDatabase::class,
                    ['id_jenis_bayar'],
                ],
                [
                    ['status_registrasi'],
                    \App\Features\Registrasi\StatusRegistrasi\StatusRegistrasiDatabase::class,
                    ['id_status_registrasi'],
                ],
                [
                    ['status_rawat'],
                    \App\Features\Registrasi\StatusRawat\StatusRawatDatabase::class,
                    ['id_status_rawat'],
                ],
                [
                    ['status_poli'],
                    \App\Features\Registrasi\StatusPoli\StatusPoliDatabase::class,
                    ['id_status_poli'],
                ],
                [
                    ['status_bayar'],
                    \App\Features\Registrasi\StatusBayar\StatusBayarDatabase::class,
                    ['id_status_bayar'],
                ],
            ],
            false,
            'registrasi.csv'
        );
    }
}
