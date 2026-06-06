<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningKeputusan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSkriningKeputusanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningKeputusanDatabase(),
            [
                'id_keputusan'       => V::DEFAULT(),
                'skrining_keputusan' => V::DEFAULT(),
            ],
            [],
        );
    }
}
