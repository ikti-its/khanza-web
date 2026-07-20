<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefTemplateLaporanOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefTemplateLaporanOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefTemplateLaporanOperasiDatabase(),
            [
                'id_template'   => V::DEFAULT(),
                'nama_template' => V::DEFAULT(),
                'isi_template'  => V::DEFAULT(),
            ],
            [],
        );
    }
}
