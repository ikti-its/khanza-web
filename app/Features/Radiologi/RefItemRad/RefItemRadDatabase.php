<?php
declare(strict_types=1);

namespace App\Features\Radiologi\RefItemRad;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefItemRadDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'radiologi',
            'ref_item_rad',
            [
                'id_item'          => T::ID(100_000),
                'kode_periksa'     => T::CODE(6),
                'nama_pemeriksaan' => T::TEXT(),
                'tarif_dasar'      => T::MONEY(),
                'tarif_baca'       => T::MONEY()->nullable(),
            ],
            'id_item',
            ['kode_periksa'],
            [],
            false,
            'item_rad.csv',
        );
    }
}
