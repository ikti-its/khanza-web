<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPa;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanLabPaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanLabPaDatabase(),
            [
                'id_permintaan_pa'            => V::DEFAULT(),
                'tgl_pengambilan_bahan'       => V::DEFAULT(),
                'metode_diperoleh'            => V::DEFAULT(),
                'lokasi_jaringan'             => V::DEFAULT(),
                'bahan_pengawet'              => V::DEFAULT(),
                'riwayat_lokasi_lab'          => V::DEFAULT(),
                'riwayat_tgl_sebelumnya'      => V::DEFAULT(),
                'riwayat_no_pa_sebelumnya'    => V::DEFAULT(),
                'riwayat_diagnosa_sebelumnya' => V::DEFAULT(),
            ],
            [
                'id_permintaan_lab'   => [],
                'id_item_pemeriksaan' => ['kode_periksa', 'nama_item', 'tarif'],
            ],
        );
    }
}
