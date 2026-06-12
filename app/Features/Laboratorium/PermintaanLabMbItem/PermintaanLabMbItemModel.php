<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbItem;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabMbItemModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbItemDatabase(),
            [
                'id_permintaan_mb_item' => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'   => [],
                'id_item_pemeriksaan' => ['kode_periksa', 'nama_item', 'tarif'],
            ],
        );
    }
}
