<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengadaanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDetailDatabase(),
            [
                'id_detail'    => V::DEFAULT(),
                'qty'          => V::DEFAULT(),
                'harga_satuan' => V::DEFAULT(),
                'subtotal'     => V::DEFAULT(),
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
