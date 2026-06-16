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
                'tanggal_kunjungan' => T::DTIME(),
                'id_dokter'         => T::FK_AUTO(),
                'id_pasien'         => T::FK_AUTO(),
                'id_pj_pasien'      => T::FK_AUTO(),
                'alamat_pj'         => T::TEXT(),
                'hubungan_pj'       => T::TEXT(),
                'biaya_registrasi'  => T::MONEY(),
                'jenis_bayar'       => T::NAME(50),
                'status_rawat'      => T::NAME(50),
                'status_bayar'      => T::FK_AUTO(), 
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
                    'status_bayar',
                    \App\Features\RawatInap\StatusBayar\StatusBayarDatabase::class,
                    'id_status_bayar',
                ],
            ],
            false,
            'registrasi.csv',
        );
    }
}
