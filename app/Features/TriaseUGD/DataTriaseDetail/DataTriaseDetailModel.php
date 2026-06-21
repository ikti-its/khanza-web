<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriaseDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DataTriaseDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseDetailDatabase(),
            [
                'id_triase_detail' => V::DEFAULT(),
            ],
            [
                'id_triase' => [
                    'id_registrasi' => ['nomor_rawat']
                ],
                'id_skala'  => ['pengkajian'],
            ],
        );
    }
}
