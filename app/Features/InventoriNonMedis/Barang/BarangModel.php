<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Barang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class BarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new BarangDatabase(),
            [
                'id_barang'    => V::DEFAULT(),
                'kode_barang'  => V::DEFAULT(),
                'nama_barang'  => V::DEFAULT(),
                'stok'         => V::DEFAULT(),
                'stok_minimum' => V::DEFAULT(),
                'harga_satuan' => V::DEFAULT(),
            ],
            [
                'id_satuan'                 => ['nama_satuan'],
                'id_jenis_barang'           => ['nama_jenis_barang'],
            ],
        );
    }
}
