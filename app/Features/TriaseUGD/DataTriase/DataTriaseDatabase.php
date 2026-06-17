<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriase;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class DataTriaseDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'triase_ugd',
            'data_triase',
            [
                'id_triase'             => T::ID(100_000_000),
                'id_registrasi'         => T::FK_AUTO(),
                'tanggal_kunjungan'     => T::DTIME(),
                'id_cara_masuk'         => T::FK_AUTO(),
                'id_alat_transportasi'  => T::FK_AUTO(),
                'id_alasan_kedatangan'  => T::FK_AUTO(),
                'keterangan_kedatangan' => T::NOTE(),
                'id_macam_kasus'        => T::FK_AUTO(),
                'sistolik'              => T::VITAL(70, 250),
                'diastolik'             => T::VITAL(40, 150),
                'nadi'                  => T::VITAL(30, 200),
                'pernapasan'            => T::VITAL(10, 60),
                'suhu'                  => T::TEMP(),
                'saturasi_o2'           => T::VITAL(50, 100),
                'nyeri'                 => T::VITAL(0, 10),
            ],
            'id_triase',
            [],
            [
                [
                    'id_registrasi',
                    \App\Features\UGD\Registrasi\RegistrasiDatabase::class,
                    'id_registrasi',
                ],
                [
                    'id_cara_masuk',
                    \App\Features\TriaseUGD\CaraMasuk\CaraMasukDatabase::class,
                    'id_cara',
                ],
                [
                    'id_alat_transportasi',
                    \App\Features\TriaseUGD\AlatTransportasi\AlatTransportasiDatabase::class,
                    'id_transportasi',
                ],
                [
                    'id_alasan_kedatangan',
                    \App\Features\TriaseUGD\AlasanKedatangan\AlasanKedatanganDatabase::class,
                    'id_alasan',
                ],
                [
                    'id_macam_kasus',
                    \App\Features\TriaseUGD\TriaseMacamKasus\TriaseMacamKasusDatabase::class,
                    'id_macam_kasus',
                ],
            ],
            false,
            'data_triase.csv',
        );
    }
}
