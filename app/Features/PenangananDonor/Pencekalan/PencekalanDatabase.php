<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\Pencekalan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PencekalanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penanganan_donor',
            'pencekalan',
            [
                'id_pencekalan'        => T::ID(5_000_000),
                'id_kunjungan'         => T::FK_AUTO(),
                'id_jenis_pencekalan'  => T::FK_AUTO(),
                'tanggal_mulai'        => T::DATE(),
                'tanggal_selesai'      => T::DATE()->nullable(),
                'id_shift'             => T::FK_AUTO(),
                'id_petugas'           => T::FK_AUTO(),
                'keterangan'           => T::NOTE(),
                'id_status_pencekalan' => T::FK_AUTO(),
            ],
            'id_pencekalan',
            ['id_kunjungan'],
            [
                [
                    'id_kunjungan',
                    \App\Features\Donor\Kunjungan\KunjunganDatabase::class,
                    'id_kunjungan',
                ],
                [
                    'id_jenis_pencekalan',
                    \App\Features\PenangananDonor\JenisPencekalan\JenisPencekalanDatabase::class,
                    'id_jenis_pencekalan',
                ],
                [
                    'id_shift',
                    \App\Features\Operasional\Shift\ShiftDatabase::class,
                    'id_shift',
                ],
                [
                    'id_petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'id_status_pencekalan',
                    \App\Features\PenangananDonor\StatusPencekalan\StatusPencekalanDatabase::class,
                    'id_status_pencekalan',
                ],
            ],
            false,
            'pencekalan.csv',
        );
    }
}
