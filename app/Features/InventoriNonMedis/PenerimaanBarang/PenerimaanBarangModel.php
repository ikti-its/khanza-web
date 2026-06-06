<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenerimaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDatabase(),
            [
                'id_penerimaan' => V::DEFAULT(),
                'tanggal'       => V::DEFAULT(),
                'status'        => V::DEFAULT(),
                'catatan'       => V::DEFAULT(),
            ],
            [
                'id_pengadaan' => [
                    'tanggal',
                    'id_status_pengadaan_barang' => ['nama_status_pengadaan_barang'],
                ],
            ],
        );
    }
}
