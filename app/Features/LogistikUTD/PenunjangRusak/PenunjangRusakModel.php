<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusak;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenunjangRusakModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangRusakDatabase(),
            [
                'id_penunjang_rusak' => V::DEFAULT(),
                'tanggal_rusak'      => V::DEFAULT(),
                'keterangan'         => V::DEFAULT(),
            ],
            [
                'id_petugas' => [
                    'id_orang' => ['nama']
                ],
            ],
        );
    }
}
