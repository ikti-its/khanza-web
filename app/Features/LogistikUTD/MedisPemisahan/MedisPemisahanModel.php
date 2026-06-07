<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisPemisahan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class MedisPemisahanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisPemisahanDatabase(),
            [
                'id_medis_pemisahan' => V::DEFAULT(),
                'jumlah'             => V::DEFAULT(),
                'harga'              => V::DEFAULT(),
            ],
            [
                'id_pemisahan' => ['tanggal_pemisahan'],
                'id_barang'    => ['kode_barang', 'nama'],
            ],
        );
    }
}
