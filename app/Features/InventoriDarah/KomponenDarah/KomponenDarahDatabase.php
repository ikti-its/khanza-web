<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\KomponenDarah;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class KomponenDarahDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_darah',
            'komponen_darah',
            [
                'id_komponen'       => T::ID(10),
                'kode_komponen'     => T::CODE(3),
                'nama_komponen'     => T::NAME(50),
                'masa_berlaku_hari' => T::QTY(5, 365),
                'jasa_sarana'       => T::MONEY(),
                'paket_bhp'         => T::MONEY(),
                'kso'               => T::MONEY(),
                'manajemen'         => T::MONEY(),
                'pembatalan'        => T::MONEY(),
            ],
            'id_komponen',
            ['kode_komponen'],
            [],
            true,
            'komponen_darah.csv',
        );
    }
}
