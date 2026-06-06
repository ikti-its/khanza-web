<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningNyeriDada;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSkriningNyeriDadaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningNyeriDadaDatabase(),
            [
                'id_nyeri_dada' => V::DEFAULT(),
                'nyeri_dada'    => V::DEFAULT(),
            ],
            [],
        );
    }
}
