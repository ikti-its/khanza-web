<?php

declare(strict_types=1);

namespace App\Features\Auth\RefGrupFitur;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefGrupFiturModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefGrupFiturDatabase(),
            [
                'slug' => V::DEFAULT(),
                'nama_grup' => V::DEFAULT(),
            ],
            [],
        );
    }
}
