<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadBhp;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilRadBhpModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadBhpDatabase(),
            [
                'id_rad_bhp'      => V::DEFAULT(),
                'jumlah_pakai'    => V::DEFAULT(),
            ],
            [
                'id_hasil_rad'    => [],
                'id_barang'       => [
                    'kode_barang',
                    'nama_barang',
                    'id_satuan' =>['nama_satuan']
                ],
            ],
        );
    }
}
