<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasiAlat;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class CatatanAnestesiSedasiAlatModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiAlatDatabase(),
            [
                'id_alat_catatan' => V::DEFAULT(),
                'is_digunakan'    => V::DEFAULT(),
                'keterangan'      => V::DEFAULT(),
            ],
            [
                'id_catatan_anestesi' => [],
                'id_alat'             => ['nama_alat'],
            ],
        );
    }
}
