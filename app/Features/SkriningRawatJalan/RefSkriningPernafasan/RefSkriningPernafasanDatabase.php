<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningPernafasan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefSkriningPernafasanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'skrining_rj',
            'ref_skrining_pernafasan',
            [
                'id_pernafasan' => T::ID(10),
                'pernafasan'    => T::TEXT(),
            ],
            'id_pernafasan',
            [],
            [],
            true,
            'skrining_pernafasan.csv',
        );
    }
}
