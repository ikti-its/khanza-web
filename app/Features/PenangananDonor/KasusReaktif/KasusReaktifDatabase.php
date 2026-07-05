<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\KasusReaktif;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class KasusReaktifDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penanganan_donor',
            'kasus_reaktif',
            [
                'id_kasus'           => T::ID(5_000_000),
                'nomor_kasus'        => T::RECORD(15),
                'id_uji_saring'      => T::FK_AUTO(),
                'tanggal_ditetapkan' => T::DATE(),
                'id_status_kasus'    => T::FK_AUTO(),
            ],
            'id_kasus',
            ['nomor_kasus', 'id_uji_saring'],
            [
                [
                    'id_uji_saring',
                    \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringDatabase::class,
                    'id_uji_saring',
                ],
                [
                    'id_status_kasus',
                    \App\Features\PenangananDonor\StatusKasus\StatusKasusDatabase::class,
                    'id_status_kasus',
                ],
            ],
            false,
            'kasus_reaktif.csv',
        );
    }
}
