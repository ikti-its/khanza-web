<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengadaanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDatabase(),
            [
                'id_pengadaan'               => V::DEFAULT(),
                'no_pengadaan'               => V::DEFAULT(),
                'tanggal'                    => V::DEFAULT(),
                'catatan'                    => V::DEFAULT(),
                'total_harga'               => V::DEFAULT(),
            ],
            [
                'id_pengajuan'               => ['no_pengajuan'],
                'id_suplier'                 => ['nama_suplier'],
                'id_status_pengadaan_barang' => ['nama_status_pengadaan_barang'],
            ],
        );
    }
}
