<?php
declare(strict_types=1);

namespace App\Features\Role\RiwayatTanggalDonor;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RiwayatTanggalDonorDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'role',
            'riwayat_tanggal_donor',
            [
                'id_riwayat'    => T::ID(300_000_000),
                'id_pendonor'   => T::FK_AUTO(),
                'tanggal_donor' => T::DATE()->nullable(),
                'start_valid'   => T::DTIME(),
                'end_valid'     => T::DTIME()->nullable(),
            ],
            'id_riwayat',
            [],
            [
                [
                    'id_pendonor',
                    \App\Features\Role\Pendonor\PendonorDatabase::class,
                    'id_pendonor',
                ],
            ],
            false,
            'riwayat_tanggal_donor.csv',
        );
    }
}
