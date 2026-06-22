<?php
declare(strict_types=1);

namespace App\Features\UGD\JenisBayar;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class JenisBayarModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisBayarDatabase(),
            [
                'id_jenis_bayar'   => V::DEFAULT(),
                'nama_jenis_bayar' => V::DEFAULT(),
            ],
            [],
        );
    }
}
