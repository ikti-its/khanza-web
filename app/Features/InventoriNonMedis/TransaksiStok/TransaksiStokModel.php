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
                'id_transaksi' => V::DEFAULT(),
                'tanggal'      => V::DEFAULT(),
                'keterangan'   => V::DEFAULT(),
            ],
            [
                'id_tipe_transaksi_stok' => ['nama_tipe_transaksi_stok'],
                'id_permintaan'          => ['no_permintaan', 'no_keluar'],
                'id_penerimaan'          => ['no_masuk'],
            ],
        );
    }
}
