<?php
declare(strict_types=1);

namespace App\Features\Operasi\TimeOutSebelumInsisi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TimeOutSebelumInsisiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TimeOutSebelumInsisiDatabase(),
            [
                'id_timeout'              => V::DEFAULT(),
                'waktu_timeout'           => V::DEFAULT(),
                'is_identitas_sesuai'     => V::DEFAULT(),
                'is_tindakan_sesuai'      => V::DEFAULT(),
                'is_area_insisi_sesuai'   => V::DEFAULT(),
                'perkiraan_waktu_jam'     => V::DEFAULT(),
                'is_antibiotik'           => V::DEFAULT(),
                'nama_antibiotik'         => V::DEFAULT(),
                'waktu_antibiotik'        => V::DEFAULT(),
                'antisipasi_hilang_darah' => V::DEFAULT(),
                'keterangan_hal_khusus'   => V::DEFAULT(),
                'tanggal_steril'          => V::DEFAULT(),
                'is_steril_dikonfirmasi'  => V::DEFAULT(),
                'is_verifikasi_preop'     => V::DEFAULT(),
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
                'id_tindakan'          => [],
                'id_sn_cn'             => [
                    'id_orang'  => ['nama']
                ],
                'id_dokter_bedah'      => [
                    'id_orang'  => ['nama']
                ],
                'id_dokter_anestesi'   => [
                    'id_orang'  => ['nama']
                ],
                'id_penandaan_area'    => ['nama_ketersediaan'],
                'id_hal_khusus'        => ['nama_ketersediaan'],
                'id_perawat_ok'        => [
                    'id_orang'  => ['nama']
                ],
            ],
        );
    }
}
