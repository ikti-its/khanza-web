<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefSlotOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefSlotOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSlotOperasiDatabase(),
            [
                'id_slot'    => V::DEFAULT(),
                'nama_slot'  => V::DEFAULT(),
                'waktu_slot' => V::DEFAULT(),
            ],
            [],
        );
    }
}
