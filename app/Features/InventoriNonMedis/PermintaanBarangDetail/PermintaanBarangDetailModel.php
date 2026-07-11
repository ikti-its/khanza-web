<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangDetailDatabase(),
            [
                'id_detail'        => V::DEFAULT(),
                'nama_barang_baru' => V::DEFAULT(),
                'qty'              => V::DEFAULT(),
                'qty_disetujui'    => V::DEFAULT(),
                'catatan'          => V::DEFAULT(),
            ],
            [
                'id_permintaan' => [
                    'tanggal',
                ],
                'id_barang'     => [
                    'kode_barang',
                    'nama_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }
}
