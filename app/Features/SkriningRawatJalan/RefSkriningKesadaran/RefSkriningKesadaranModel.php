<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningKesadaran;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSkriningKesadaranModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningKesadaranDatabase(),
            [
                'id_kesadaran' => V::DEFAULT(),
                'kesadaran'    => V::DEFAULT(),
            ],
            [],
        );
    }
}
