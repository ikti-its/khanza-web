<?php
declare(strict_types=1);

namespace App\Features\RawatInap\Registrasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RegistrasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rawat_inap',
            'registrasi',
            [
                'id_rawat_inap'    => T::ID(100_000_000),
                'id_registrasi'    => T::FK_AUTO(),
                'jenis_bayar'      => T::FK_AUTO(),
                'kamar'            => T::TEXT(),
                'tarif_kamar'      => T::MONEY(),
                'diagnosa_awal'    => T::TEXT(),
                'diagnosa_akhir'   => T::TEXT()->nullable(),
                'tanggal_masuk'    => T::DATE(),
                'tanggal_keluar'   => T::DATE()->nullable(),
                'status_pulang'    => T::FK_AUTO()->nullable(),
                'jam_keluar'       => T::TIME()->nullable(),
                'lama_ranap'       => T::QTY(0, 365),
                'dokter_pj'        => T::FK_AUTO(),
                'total_biaya'      => T::MONEY(),
                'status_bayar'     => T::FK_AUTO(),
            ],
            'id_rawat_inap',
            ['id_registrasi'],
            [
                [
                    'id_registrasi',
                    \App\Features\Registrasi\Registrasi\RegistrasiDatabase::class,
                    'id_registrasi',
                ],
                [
                    'jenis_bayar',
                    \App\Features\RawatInap\JenisBayar\JenisBayarDatabase::class,
                    'id_jenis_bayar',
                ],
                [
                    'status_pulang',
                    \App\Features\RawatInap\StatusPulang\StatusPulangDatabase::class,
                    'id_status_pulang',
                ],
                [
                    'dokter_pj',
                    \App\Features\Role\Dokter\DokterDatabase::class,
                    'id_dokter',
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
