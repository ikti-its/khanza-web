<?php
declare(strict_types=1);

namespace App\Features\Registrasi\HubunganPj;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HubunganPjModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HubunganPjDatabase(),
            [
                'id_hubungan_pj'   => V::DEFAULT(),
                'nama_hubungan_pj' => V::DEFAULT(),
            ],
            [],
        );
    }
}
