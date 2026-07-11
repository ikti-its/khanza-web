<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostop;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPostopModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopDatabase(),
            [
                'id_checklist_post'    => V::DEFAULT(),
                'waktu_checklist'      => V::DEFAULT(),
                'jenis_cairan_infus'   => V::DEFAULT(),
                'waktu_pasang_kateter' => V::DEFAULT(),
                'jumlah_urine_cc'      => V::DEFAULT(),
                'catatan_luka_operasi' => V::DEFAULT(),
            ],
            [
                'id_jadwal'            => [
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
                'id_tindakan'          => ['nama_tindakan'],
                'id_sn_cn'             => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_bedah'      => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_anestesi'   => [
                    'id_orang' => ['nama'],
                ],
                'id_kesadaran_pascaop' => ['nama_kesadaran'],
                'id_jaringan_pa_vc'    => ['nama_ketersediaan'],
                'id_kateter_urine'     => ['nama_ketersediaan'],
                'id_warna_urine'       => ['nama_warna'],
                'id_petugas_anestesi'  => [
                    'id_orang' => ['nama'],
                ],
                'id_petugas_ok'        => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
