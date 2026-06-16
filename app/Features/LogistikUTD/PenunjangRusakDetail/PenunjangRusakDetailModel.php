<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusakDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenunjangRusakDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangRusakDetailDatabase(),
            [
                'id_penunjang_rusak_detail' => V::DEFAULT(),
                'jumlah'                    => V::DEFAULT(),
                'harga_beli'                => V::DEFAULT(),
            ],
            [
                'id_penunjang_rusak' => ['tanggal_rusak', 'keterangan'],
                'id_barang'          => ['kode_barang', 'nama_barang'],
            ],
        );
    }
}
