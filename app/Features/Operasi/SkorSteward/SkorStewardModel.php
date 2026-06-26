<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorSteward;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SkorStewardModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkorStewardDatabase(),
            [
                'id_skor_steward'    => V::DEFAULT(),
                'waktu_penilaian'    => V::DEFAULT(),
                // 'total_skor'            => V::DEFAULT(),
                'is_boleh_pindah' => V::DEFAULT(),
                'catatan_keluar'  => V::DEFAULT(),
                'instruksi_rr'    => V::DEFAULT(),
            ],
            [
                'id_jadwal' => [
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
                'id_petugas'         => [
                    'id_orang'  => ['nama']
                ],
                'id_dokter_anestesi' => [
                    'id_orang'  => ['nama']
                ],
                'skor_kesadaran'     => ['nama_skala', 'nilai'],
                'skor_respirasi'     => ['nama_skala', 'nilai'],
                'skor_motorik'       => ['nama_skala', 'nilai'],
            ],
        );
    }
}
