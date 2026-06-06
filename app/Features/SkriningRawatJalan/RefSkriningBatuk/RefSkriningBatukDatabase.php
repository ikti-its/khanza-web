<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningBatuk;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefSkriningBatukDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'skrining_rawat_jalan',
            'ref_skrining_batuk',
            [
                'id_batuk'       => T::ID(5),
                'kategori_batuk' => T::TEXT(),
            ],
            'id_batuk',
            [],
            [],
            true,
            'skrining_batuk.csv',
        );
    }
}
