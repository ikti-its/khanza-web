<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPkParameter;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabPkParameterModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPkParameterDatabase(),
            [
                'id_pk_parameter' => V::DEFAULT(),
            ],
            [
                'id_permintaan_pk_item' => [
                    'id_permintaan_lab',
                    'id_item_pemeriksaan',
                ],
                'id_parameter'          => [
                    'nama_parameter',
                    'satuan',
                    'nilai_rujukan',
                    'biaya_item',
                ],
            ],
        );
    }
}
