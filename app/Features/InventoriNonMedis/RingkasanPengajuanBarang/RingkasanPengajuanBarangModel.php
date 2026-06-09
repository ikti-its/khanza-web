<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPengajuanBarang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;
use App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangDatabase;

final class RingkasanPengajuanBarangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDatabase(),
            [
                'id_pengajuan'      => V::DEFAULT(),
                'no_pengajuan'      => V::DEFAULT(),
                'tanggal'           => V::DEFAULT(),
                'tanggal_disetujui' => V::DEFAULT(),
            ],
            [
                'atasan_logistik'            => ['id_orang' => ['nama']],
                'id_status_pengajuan_barang' => ['nama_status_pengajuan_barang'],
            ],
        );
    }
}
