<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusRawat;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusRawatModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusRawatDatabase(),
            [
                'id_status_rawat'   => V::DEFAULT(),
                'nama_status_rawat' => V::DEFAULT(),
            ],
            [],
        );
    }
}
