<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\JenisPencekalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use App\Features\PenangananDonor\JenisPencekalan\JenisPencekalanDatabase;

final class JenisPencekalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisPencekalanDatabase(),
            [
                'id_jenis_pencekalan'   => V::DEFAULT(),
                'nama_jenis_pencekalan' => V::DEFAULT(),
            ],
            [],
        );
    }
}
