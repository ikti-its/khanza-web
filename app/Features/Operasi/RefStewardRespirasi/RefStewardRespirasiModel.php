<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefStewardRespirasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefStewardRespirasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefStewardRespirasiDatabase(),
            [
                'id_respirasi' => V::DEFAULT(),
                'nama_skala'   => V::DEFAULT(),
                'nilai'        => V::DEFAULT(),
            ],
            [],
        );
    }
}
