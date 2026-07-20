<?php
declare(strict_types=1);

namespace App\Features\Donor\PengambilanDarah;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengambilanDarahDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'donor',
            'pengambilan_darah',
            [
                'id_pengambilan_darah'  => T::ID(100_000_000),
                'nomor_pengambilan'     => T::RECORD(20),
                'id_kunjungan'          => T::FK_AUTO(),
                'tanggal_pengambilan'   => T::DTIME(),
                'id_shift'              => T::FK_AUTO(),
                'no_bag'                => T::CODE(14),
                'id_jenis_bag'          => T::FK_AUTO(),
                'id_jenis_donor'        => T::FK_AUTO(),
                'id_lokasi_pengambilan' => T::FK_AUTO(),
                'id_petugas'            => T::FK_AUTO(),
                'id_status_pengambilan' => T::FK_AUTO()->nullable(),
            ],
            'id_pengambilan_darah',
            ['nomor_pengambilan', 'no_bag'],
            [
                [
                    'id_kunjungan',
                    \App\Features\Donor\Kunjungan\KunjunganDatabase::class,
                    'id_kunjungan',
                ],
                [
                    'id_shift',
                    \App\Features\Operasional\Shift\ShiftDatabase::class,
                    'id_shift',
                ],
                [
                    'id_jenis_bag',
                    \App\Features\Donor\JenisBag\JenisBagDatabase::class,
                    'id_jenis_bag',
                ],
                [
                    'id_jenis_donor',
                    \App\Features\Donor\JenisDonor\JenisDonorDatabase::class,
                    'id_jenis_donor',
                ],
                [
                    'id_lokasi_pengambilan',
                    \App\Features\Donor\LokasiPengambilanDarah\LokasiPengambilanDarahDatabase::class,
                    'id_lokasi_pengambilan',
                ],
                [
                    'id_petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'id_status_pengambilan',
                    \App\Features\Donor\StatusPengambilan\StatusPengambilanDatabase::class,
                    'id_status_pengambilan',
                ],
            ],
            false,
            'pengambilan_darah.csv',
        );
    }
}
