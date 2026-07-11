<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanPaskaOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class CatatanPaskaOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanPaskaOperasiDatabase(),
            [
                'id_catatan_paska'        => V::DEFAULT(),
                'waktu_penilaian'         => V::DEFAULT(),
                'instruksi_rawat'         => V::DEFAULT(),
                'instruksi_cairan'        => V::DEFAULT(),
                'instruksi_antibiotik'    => V::DEFAULT(),
                'instruksi_analgetik'     => V::DEFAULT(),
                'instruksi_medikamentosa' => V::DEFAULT(),
                'instruksi_diet'          => V::DEFAULT(),
                'instruksi_penunjang'     => V::DEFAULT(),
                'instruksi_transfusi'     => V::DEFAULT(),
                'instruksi_lainnya'       => V::DEFAULT(),
            ],
            [
                'id_jadwal'       => [
                    'tanggal',
                    'id_permintaan' => [
                        'nomor_reg',
                        'nomor_reg' => [
                            'id_pasien' => [
                                'id_orang' => ['nama'],
                            ],
                        ],
                    ],
                ],
                'id_dokter_bedah' => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
