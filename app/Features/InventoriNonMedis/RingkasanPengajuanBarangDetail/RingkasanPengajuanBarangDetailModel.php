<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPengajuanBarangDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use App\Features\InventoriNonMedis\PengajuanBarangDetail\PengajuanBarangDetailDatabase;

final class RingkasanPengajuanBarangDetailModel extends ModelTemplate
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
                'id_pengajuan' => ['tanggal', 'no_pengajuan'],
                'id_barang'    => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],
            ],
        );
    }
}
