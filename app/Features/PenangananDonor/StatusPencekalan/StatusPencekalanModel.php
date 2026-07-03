<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\StatusPencekalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusPencekalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPencekalanDatabase(),
            [
                'id_status_pencekalan'   => V::DEFAULT(),
                'nama_status_pencekalan' => V::DEFAULT(),
            ],
            [],
        );
    }
}