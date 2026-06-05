<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\JenisBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class JenisBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisBarangDatabase(),
            [
                'id_jenis_barang'          => V::DEFAULT(),
                'kode_jenis_barang' => V::DEFAULT(),
                'nama_jenis_barang' => V::DEFAULT(),
            ],
            [],
        );
    }
}
