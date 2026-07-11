<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasiTindakan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TagihanOperasiTindakanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TagihanOperasiTindakanDatabase(),
            [
                'id_tagihan_tindakan' => V::DEFAULT(),
            ],
            [
                'id_tagihan' => [],
                'id_paket'   => [],
            ],
        );
    }
}
