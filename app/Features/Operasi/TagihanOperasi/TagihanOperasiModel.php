<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TagihanOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TagihanOperasiDatabase(),
            [
                'id_tagihan'      => V::DEFAULT(),
                'jenis_anestesi'  => V::DEFAULT(),
                'tanggal_mulai'   => V::DEFAULT(),
                'tanggal_selesai' => V::DEFAULT(),
                'total_tagihan'   => V::DEFAULT(),
                'diagnosis_pre'   => V::DEFAULT(),
                'diagnosis_post'  => V::DEFAULT(),
                'jaringan'        => V::DEFAULT(),
                'laporan'         => V::DEFAULT(),
                'is_pa'           => V::DEFAULT(),
            ],
            [
                'id_jadwal'          => [
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
                'id_template'        => ['nama_template'],
                'id_kategori'        => ['nama_kategori'],
                'id_operator_1'      => ['id_orang' => ['nama']],
                'id_operator_2'      => ['id_orang' => ['nama']],
                'id_operator_3'      => ['id_orang' => ['nama']],
                'id_dokter_anestesi' => ['id_orang' => ['nama']],
                'id_dokter_anak'     => ['id_orang' => ['nama']],
                'id_dokter_pj_anak'  => ['id_orang' => ['nama']],
                'id_dokter_umum'     => ['id_orang' => ['nama']],
                'id_ast_operator_1'  => ['id_orang' => ['nama']],
                'id_ast_operator_2'  => ['id_orang' => ['nama']],
                'id_ast_operator_3'  => ['id_orang' => ['nama']],
                'id_bidan_1'         => ['id_orang' => ['nama']],
                'id_bidan_2'         => ['id_orang' => ['nama']],
                'id_bidan_3'         => ['id_orang' => ['nama']],
                'id_perawat_luar'    => ['id_orang' => ['nama']],
                'id_instrumen'       => ['id_orang' => ['nama']],
                'id_ast_anestesi_1'  => ['id_orang' => ['nama']],
                'id_ast_anestesi_2'  => ['id_orang' => ['nama']],
                'id_perawat_resus'   => ['id_orang' => ['nama']],
                'id_onloop_1'        => ['id_orang' => ['nama']],
                'id_onloop_2'        => ['id_orang' => ['nama']],
                'id_onloop_3'        => ['id_orang' => ['nama']],
                'id_onloop_4'        => ['id_orang' => ['nama']],
                'id_onloop_5'        => ['id_orang' => ['nama']],
            ],
        );
    }
}
