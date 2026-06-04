<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\RefItemPemeriksaanLab;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefItemPemeriksaanLabModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefItemPemeriksaanLabDatabase(),
            [
                'id_item_lab'  => V::DEFAULT(),
                'kode_periksa' => V::DEFAULT(),
                'nama_item'    => V::DEFAULT(),
                'tarif'        => V::DEFAULT(),
            ],
            [
                'id_kategori' => ['nama_kategori'],
            ],
        );
    }
}
