<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStokDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TransaksiStokDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiStokDetailDatabase(),
            [
                'id_detail'    => V::DEFAULT(),
                'qty'          => V::DEFAULT(),
                'harga_satuan' => V::DEFAULT(),
                'stok_sebelum' => V::DEFAULT(),
                'stok_sesudah' => V::DEFAULT(),
            ],
            [
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }
}
