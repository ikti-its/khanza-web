<?php
declare(strict_types=1);

namespace App\Features\Role\Pendonor;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PendonorDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'role',
            'pendonor',
            [
                'id_pendonor'            => T::ID(300_000_000),
                'id_orang'               => T::FK_AUTO(),
                'nomor_pendonor'         => T::RECORD(20),
                'id_rhesus'              => T::FK_AUTO()->nullable(),
                'nomor_telepon'          => T::TEXT(),
                'tanggal_donor_terakhir' => T::DATE()->nullable(),
            ],
            'id_pendonor',
            ['id_orang', 'nomor_pendonor'],
            [
                [
                    'id_orang',
                    \App\Features\Person\Orang\OrangDatabase::class,
                    'id_orang',
                ],
                [
                    'id_rhesus',
                    \App\Features\Darah\Rhesus\RhesusDatabase::class,
                    'id_rhesus',
                ],
            ],
            false,
            'pendonor.csv',
        );
    }
}
