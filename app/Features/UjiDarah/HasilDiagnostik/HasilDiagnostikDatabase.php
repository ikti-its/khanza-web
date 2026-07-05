<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilDiagnostik;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilDiagnostikDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'uji_darah',
            'hasil_diagnostik',
            [
                'id_diagnostik'     => T::ID(5_000_000),
                'id_kasus'          => T::FK_AUTO(),
                'tanggal_hasil'     => T::DATE(),
                'fasyankes_rujukan' => T::NAME(100),
                'dokter_pemeriksa'  => T::NAME(50),
            ],
            'id_diagnostik',
            ['id_kasus'],
            [
                [
                    'id_kasus',
                    \App\Features\PenangananDonor\KasusReaktif\KasusReaktifDatabase::class,
                    'id_kasus',
                ],
            ],
            false,
            'hasil_diagnostik.csv',
        );
    }
}
