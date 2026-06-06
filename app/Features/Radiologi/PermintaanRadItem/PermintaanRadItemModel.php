<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRadItem;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanRadItemModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadItemDatabase(),
            [
                'id_permintaan_item' => V::DEFAULT(),
            ],
            [
                'id_permintaan' => ['no_permintaan'],
                'id_item'       => ['kode_periksa', 'nama_pemeriksaan', 'tarif_dasar'],
            ],
        );
    }
}