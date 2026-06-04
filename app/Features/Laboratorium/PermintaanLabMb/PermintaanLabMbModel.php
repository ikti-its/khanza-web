<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMb;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabMbModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabMbDatabase(),
            [
                'id_permintaan_mb'         => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'        => [],
                'id_item_pemeriksaan'      => ['kode_periksa', 'nama_item', 'tarif'],
                'id_parameter_pemeriksaan' => ['nama_parameter', 'satuan', 'nilai_rujukan', 'biaya_item'],
            ],
        );
    }
}
