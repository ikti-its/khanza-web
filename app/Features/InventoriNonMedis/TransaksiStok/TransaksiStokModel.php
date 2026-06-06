<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStok;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TransaksiStokModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiStokDatabase(),
            [
                'id_transaksi'           => V::DEFAULT(),
                'qty'                    => V::DEFAULT(),
                'tanggal'                => V::DEFAULT(),
                'catatan'                => V::DEFAULT(),
            ],
            [
                'id_barang' => [
                    'nama_barang',
                    'kode_barang',
                    'id_satuan' => ['nama_satuan'],
                ],

                'id_tipe_transaksi_stok' => [
                    'nama_tipe_transaksi_stok',
                ],

                'id_penerimaan' => [
                    'tanggal',
                ],

                'id_permintaan' => [
                    'tanggal',
                ],

                'id_opname' => [
                    'tanggal',
                ],
            ],
        );
    }
}
