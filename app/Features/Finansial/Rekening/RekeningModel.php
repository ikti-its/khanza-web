<?php
declare(strict_types=1);

namespace App\Features\Finansial\Rekening;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RekeningModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RekeningDatabase(),
            [
                'id_rekening'    => V::DEFAULT(),
                'nama_akun'      => V::DEFAULT(),
                'nomor_rekening' => V::DEFAULT(),
            ],
            [
                'bank' => ['nama_bank'],
            ],
        );
    }
}
