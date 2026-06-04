<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostopDrain;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPostopDrainModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopDrainDatabase(),
            [
                'id_drain'          => V::DEFAULT(),
                'jumlah'            => V::DEFAULT(),
                'letak'             => V::DEFAULT(),
                'warna'             => V::DEFAULT(),
            ],
            [
                'id_checklist_post' => [],
                'id_ketersediaan'   => ['nama_ketersediaan'],
            ],
        );
    }
}
