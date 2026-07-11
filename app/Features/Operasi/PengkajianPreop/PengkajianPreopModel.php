<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreop;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengkajianPreopModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreopDatabase(),
            [
                'id_pengkajian_pre'      => V::DEFAULT(),
                'waktu_pengkajian'       => V::DEFAULT(),
                'ringkasan_klinik'       => V::DEFAULT(),
                'pemeriksaan_fisik'      => V::DEFAULT(),
                'pemeriksaan_diagnostik' => V::DEFAULT(),
                'diagnosa_pre_operasi'   => V::DEFAULT(),
                'rencana_tindakan'       => V::DEFAULT(),
                'persiapan_khusus'       => V::DEFAULT(),
                'terapi_pre_operasi'     => V::DEFAULT(),
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
