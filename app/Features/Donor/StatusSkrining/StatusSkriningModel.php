<?php
declare(strict_types=1);

namespace App\Features\Donor\StatusSkrining;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusSkriningModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusSkriningDatabase(),
            [
                'id_status_skrining'   => V::DEFAULT(),
                'nama_status_skrining' => V::DEFAULT(),
            ],
            [],
        );
    }
}
