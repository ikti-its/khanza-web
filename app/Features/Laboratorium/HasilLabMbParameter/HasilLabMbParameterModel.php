<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMbParameter;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilLabMbParameterModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabMbParameterDatabase(),
            [
                'id_hasil_mb_parameter' => V::DEFAULT(),
                'nilai_hasil'           => V::DEFAULT(),
                'keterangan_hasil'      => V::DEFAULT(),
            ],
            [
                'id_hasil_mb'  => ['id_permintaan_lab', 'id_permintaan_mb_item'],
                'id_parameter' => ['nama_parameter', 'satuan', 'nilai_rujukan'],
            ],
        );
    }
}
