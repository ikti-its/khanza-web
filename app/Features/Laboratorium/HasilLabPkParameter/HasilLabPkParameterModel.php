<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPkParameter;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilLabPkParameterModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilLabPkParameterDatabase(),
            [
                'id_hasil_pk_parameter' => V::DEFAULT(),
                'nilai_hasil'           => V::DEFAULT(),
                'keterangan_hasil'      => V::DEFAULT(),
            ],
            [
                'id_hasil_pk'  => ['id_permintaan_lab', 'id_permintaan_pk_item'],
                'id_parameter' => ['nama_parameter', 'satuan', 'nilai_rujukan'],
            ],
        );
    }
}
