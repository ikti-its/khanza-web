<?php
declare(strict_types=1);

namespace App\Features\UGD\StatusBayar;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusBayarModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusBayarDatabase(),
            [
                'id_status_bayar'   => V::DEFAULT(),
                'nama_status_bayar' => V::DEFAULT(),
            ],
            [],
        );
    }
}
