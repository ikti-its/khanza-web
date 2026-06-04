<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRad;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilRadModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadDatabase(),
            [
                'id_hasil_rad'        => V::DEFAULT(),
                'tgl_jam_hasil'       => V::DEFAULT(),
                'catatan'             => V::DEFAULT(),
            ],
            [
                'id_permintaan_rad'   => [],
                'nomor_reg'           => ['nomor_rawat'],
                'kode_dokter_pj'      => [],
                'id_petugas_rad'      => [],
                'kode_dokter_perujuk' => [],
            ],
        );
    }
}
