<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\KomponenDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KomponenDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KomponenDarahDatabase(),
            [
                'id_komponen'       => V::DEFAULT(),
                'kode_komponen'     => V::DEFAULT(),
                'nama_komponen'     => V::DEFAULT(),
                'masa_berlaku_hari' => V::DEFAULT(),
                'jasa_sarana'       => V::DEFAULT(),
                'paket_bhp'         => V::DEFAULT(),
                'kso'               => V::DEFAULT(),
                'manajemen'         => V::DEFAULT(),
                'pembatalan'        => V::DEFAULT(),
            ],
            [],
        );
    }
}
