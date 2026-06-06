<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSkriningSkalaNyeriModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningSkalaNyeriDatabase(),
            [
                'id_skala_nyeri' => V::DEFAULT(),
                'skala_nyeri'    => V::DEFAULT(),
            ],
            [],
        );
    }
}
