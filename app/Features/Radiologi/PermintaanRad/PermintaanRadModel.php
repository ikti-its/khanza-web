<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanRadModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadDatabase(),
            [
                'id_permintaan'        => V::DEFAULT(),
                'no_permintaan'        => V::DEFAULT(),
                'tgl_jam_permintaan'   => V::DEFAULT(),
                'informasi_tambahan'   => V::DEFAULT(),
                'indikasi_klinis'      => V::DEFAULT(),
            ],
            [
                'nomor_reg'            => ['nomor_rawat'],
                'kode_dokter_perujuk'  => [
                    'id_orang'  => ['nama'],
                ],
                'id_status_permintaan' => ['nama_status'],
                'id_item_rad'          => ['kode_periksa', 'nama_pemeriksaan', 'tarif_dasar'],
            ],
        );
    }
}
