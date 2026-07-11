<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengajuanBarangDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDetailDatabase(),
            [
                'id_detail'        => V::DEFAULT(),
                'nama_barang_baru' => V::DEFAULT(),
                'qty'              => V::DEFAULT(),
                'qty_disetujui'    => V::DEFAULT(),
                'harga'            => V::DEFAULT(),
                'subtotal'         => V::DEFAULT(),
            ],
            [
                'id_pengajuan' => [
                    'tanggal',
                    'id_status_pengajuan_barang' => ['nama_status_pengajuan_barang'],
                ],
                'id_barang'    => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }
}
