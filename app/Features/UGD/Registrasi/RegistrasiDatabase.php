<?php
declare(strict_types=1);

namespace App\Features\UGD\Registrasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RegistrasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'ugd',
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
                'hubungan_pj'       => T::FK_AUTO(),
                'biaya_registrasi'  => T::MONEY(),
                'jenis_bayar'       => T::FK_AUTO(),
                'status_rawat'      => T::FK_AUTO(),
                'status_bayar'      => T::FK_AUTO(), 
                'id_status_triase'  => T::FK_AUTO(),
            ],
            'id_registrasi',
            ['nomor_reg', 'nomor_rawat'],
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
                    ['hubungan_pj'],
                    \App\Features\UGD\HubunganPj\HubunganPjDatabase::class,
                    ['id_hubungan_pj']
                ],
                [
                    ['jenis_bayar'],
                    \App\Features\UGD\JenisBayar\JenisBayarDatabase::class,
                    ['id_jenis_bayar']
                ],
                [
                    ['status_rawat'],
                    \App\Features\UGD\StatusRawat\StatusRawatDatabase::class,
                    ['id_status_rawat'],
                ],
                [
                    ['status_bayar'],
                    \App\Features\UGD\StatusBayar\StatusBayarDatabase::class,
                    ['id_status_bayar'],
                ],
                [
                    ['id_status_triase'],
                    \App\Features\UGD\StatusTriase\StatusTriaseDatabase::class,
                    ['id_status_triase'],
                ],
            ],
            false,
            'registrasi.csv',
        );
    }
}
