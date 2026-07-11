<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPaItem;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabPaItemModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPaItemDatabase(),
            [
                'id_permintaan_pk_item' => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'   => ['no_permintaan'],
                'id_item_pemeriksaan' => ['kode_periksa', 'nama_item', 'tarif'],
            ],
        );
    }
}
