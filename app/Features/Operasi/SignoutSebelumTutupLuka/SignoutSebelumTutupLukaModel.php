<?php
declare(strict_types=1);

namespace App\Features\Operasi\SignoutSebelumTutupLuka;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SignoutSebelumTutupLukaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SignoutSebelumTutupLukaDatabase(),
            [
                'id_signout'              => V::DEFAULT(),
                'waktu_signout'           => V::DEFAULT(),
                'is_nama_tindakan_sesuai' => V::DEFAULT(),
                'is_kasa_lengkap'         => V::DEFAULT(),
                'is_instrumen_lengkap'    => V::DEFAULT(),
                'is_alat_tajam_lengkap'   => V::DEFAULT(),
                'is_konfirmasi_bedah'     => V::DEFAULT(),
                'is_konfirmasi_anestesi'  => V::DEFAULT(),
                'is_konfirmasi_perawat'   => V::DEFAULT(),
                'catatan_pemulihan'       => V::DEFAULT(),
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
                'id_tindakan'          => [],
                'id_sn_cn'             => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_bedah'      => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_anestesi'   => [
                    'id_orang' => ['nama'],
                ],
                'id_label_spesimen'    => ['nama_status'],
                'id_formulir_spesimen' => ['nama_status'],
                'id_perawat_ok'        => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
