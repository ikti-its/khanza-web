<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasiPenunjang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPreOperasiPenunjangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPreOperasiPenunjangDatabase(),
            [
                'id_penunjang'       => V::DEFAULT(),
                'keterangan'         => V::DEFAULT(),
            ],
            [
                'id_checklist'       => [],
                'id_jenis_penunjang' => ['nama_jenis'],
                'id_ketersediaan'    => ['nama_ketersediaan'],
            ],
        );
    }
}
