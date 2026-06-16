<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisRusakDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class MedisRusakDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisRusakDetailDatabase(),
            [
                'id_medis_rusak_detail' => V::DEFAULT(),
                'jumlah'                => V::DEFAULT(),
                'harga_beli'            => V::DEFAULT(),
            ],
            [
                'id_medis_rusak' => ['tanggal_rusak', 'keterangan'],
                'id_barang'      => ['kode_barang', 'nama'],
            ],
        );
    }
}
