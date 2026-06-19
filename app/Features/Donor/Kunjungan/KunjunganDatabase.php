<?php
declare(strict_types=1);

namespace App\Features\Donor\Kunjungan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class KunjunganDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'donor',
            'kunjungan',
            [
                'id_kunjungan'      => T::ID(100_000_000),
                'nomor_antrian'     => T::RECORD(3),
                'nomor_kunjungan'   => T::RECORD(20),
                'tanggal_kunjungan' => T::DTIME(),
                'id_pendonor'       => T::FK_AUTO(),
            ],
            'id_kunjungan',
            [
                'nomor_kunjungan',
                ['nomor_antrian', 'tanggal_kunjungan'],
            ],
            [
                [
                    'id_pendonor',
                    \App\Features\Role\Pendonor\PendonorDatabase::class,
                    'id_pendonor',
                ],
            ],
            false,
            'kunjungan.csv',
        );
    }
}
