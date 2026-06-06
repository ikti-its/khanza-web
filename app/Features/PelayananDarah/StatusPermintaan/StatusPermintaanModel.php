<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\StatusPermintaan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusPermintaanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPermintaanDatabase(),
            [
                'id_status_permintaan'   => V::DEFAULT(),
                'nama_status_permintaan' => V::DEFAULT(),
            ],
            [],
        );
    }
}
