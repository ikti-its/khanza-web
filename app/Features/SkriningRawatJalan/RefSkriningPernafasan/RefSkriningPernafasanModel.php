<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningPernafasan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSkriningPernafasanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningPernafasanDatabase(),
            [
                'id_pernafasan' => V::DEFAULT(),
                'pernafasan'    => V::DEFAULT(),
            ],
            [],
        );
    }
}
