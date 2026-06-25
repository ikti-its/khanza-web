<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasiObat;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TagihanOperasiObatModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TagihanOperasiObatDatabase(),
            [
                'id_detail'    => V::DEFAULT(),
                'jumlah'       => V::DEFAULT(),
            ],
            [
                'id_tagihan' => [],
                'id_barang'  => ['nama'],
            ],
        );
    }
}