<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusPoli;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusPoliModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPoliDatabase(),
            [
                'id_status_poli'   => V::DEFAULT(),
                'nama_status_poli' => V::DEFAULT(),
            ],
            [],
        );
    }
}
