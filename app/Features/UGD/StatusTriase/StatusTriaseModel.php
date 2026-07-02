<?php
declare(strict_types=1);

namespace App\Features\UGD\StatusTriase;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusTriaseModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusTriaseDatabase(),
            [
                'id_status_triase'   => V::DEFAULT(),
                'nama_status_triase' => V::DEFAULT(),
            ],
            [],
        );
    }
}
