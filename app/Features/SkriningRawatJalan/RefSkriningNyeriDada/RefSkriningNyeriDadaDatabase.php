<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningNyeriDada;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefSkriningNyeriDadaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'skrining_rj',
            'ref_skrining_nyeri_dada',
            [
                'id_nyeri_dada' => T::ID(10),
                'nyeri_dada'    => T::TEXT(),
            ],
            'id_nyeri_dada',
            [],
            [],
            true,
            'skrining_nyeri_dada.csv',
        );
    }
}
