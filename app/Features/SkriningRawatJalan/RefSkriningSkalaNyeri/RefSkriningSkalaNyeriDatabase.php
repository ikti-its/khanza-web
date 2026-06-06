<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefSkriningSkalaNyeriDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'skrining_rawat_jalan',
            'ref_skrining_skala_nyeri',
            [
                'id_skala_nyeri' => T::ID(10),
                'skala_nyeri'    => T::TEXT(),
            ],
            'id_skala_nyeri',
            [],
            [],
            true,
            'skrining_skala_nyeri.csv',
        );
    }
}
