<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengajuanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDatabase(),
            [
                'id_pengajuan'               => V::DEFAULT(),
                'tanggal'                    => V::DEFAULT(),
                'catatan'                    => V::DEFAULT(),
                'catatan_atasan'             => V::DEFAULT(),
            ],
            [
                'id_permintaan' => [
                    'id_tipe_permintaan_barang' => ['nama_tipe_permintaan_barang'],
                ],
                'id_status_pengajuan_barang' => ['nama_status_pengajuan_barang'],
            ],
        );
    }
}
