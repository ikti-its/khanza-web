<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisRusak;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class MedisRusakModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisRusakDatabase(),
            [
                'id_medis_rusak' => V::DEFAULT(),
                'tanggal_rusak'  => V::DEFAULT(),
                'keterangan'     => V::DEFAULT(),
            ],
            [
                'id_petugas' => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
