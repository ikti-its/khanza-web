<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPermintaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use App\Features\InventoriNonMedis\PermintaanBarang\PermintaanBarangDatabase;

final class RingkasanPermintaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangDatabase(),
            [
                'id_permintaan' => V::DEFAULT(),
                'no_permintaan' => V::DEFAULT(),
                'tanggal'       => V::DEFAULT(),
            ],
            [
                'petugas'                     => ['id_orang' => ['nama']],
                'master_ruangan'              => ['nama_ruangan'],
                'id_status_permintaan_barang' => ['nama_status_permintaan_barang'],
            ],
        );
    }
}
