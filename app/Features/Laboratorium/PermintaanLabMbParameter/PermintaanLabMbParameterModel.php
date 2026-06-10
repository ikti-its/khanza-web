<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbParameter;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabMbParameterModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbParameterDatabase(),
            [
                'id_mb_parameter'       => V::DEFAULT(),
            ],
            [
                'id_permintaan_mb_item' => [
                    'id_permintaan_lab',
                    'id_item_pemeriksaan'
                ],
                'id_parameter'          => [
                    'nama_parameter',
                    'satuan',
                    'nilai_rujukan',
                    'biaya_item'
                ],
            ],
        );
    }
}