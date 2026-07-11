<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostopPenunjang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPostopPenunjangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopPenunjangDatabase(),
            [
                'id_penunjang' => V::DEFAULT(),
                'keterangan'   => V::DEFAULT(),
            ],
            [
                'id_checklist_post'  => [],
                'id_jenis_penunjang' => ['nama_jenis'],
                'id_ketersediaan'    => ['nama_ketersediaan'],
            ],
        );
    }
}
