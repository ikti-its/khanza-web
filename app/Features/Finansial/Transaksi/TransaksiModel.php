<?php
declare(strict_types=1);

namespace App\Features\Finansial\Transaksi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TransaksiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiDatabase(),
            [
                'id_transaksi'      => V::DEFAULT(),
                'waktu_transaksi'   => V::DEFAULT(),
                'nominal'           => V::DEFAULT(),
            ],
            [
                'rekening_sumber'   => ['nama_akun'],
                'rekening_tujuan'   => ['nama_akun'],
                'metode_pembayaran' => ['nama_metode'],
            ],
        );
    }
}
