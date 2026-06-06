<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenerimaanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDetailDatabase(),
            [
                'id_detail'     => V::DEFAULT(),
                'qty_diterima'  => V::DEFAULT(),
                'harga_satuan'  => V::DEFAULT(),
            ],
            [
                'id_penerimaan' => ['tanggal', 'status'],
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }
}
