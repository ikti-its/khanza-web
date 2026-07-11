<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class ChecklistPreOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPreOperasiDatabase(),
            [
                'id_checklist'        => V::DEFAULT(),
                'waktu_checklist'     => V::DEFAULT(),
                'is_identitas_sesuai' => V::DEFAULT(),
                'is_ijin_bedah'       => V::DEFAULT(),
                'is_ijin_anestesi'    => V::DEFAULT(),
                'ket_persiapan_darah' => V::DEFAULT(),
            ],
            [
                'id_jadwal'              => [
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
                'id_tindakan'            => [],
                'id_sn_cn'               => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_bedah'        => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_anestesi'     => [
                    'id_orang' => ['nama'],
                ],
                'id_keadaan_umum'        => ['nama_keadaan'],
                'id_penandaan_area'      => ['nama_ketersediaan'],
                'id_ijin_transfusi'      => ['nama_ketersediaan'],
                'id_persiapan_darah'     => ['nama_ketersediaan'],
                'id_perlengkapan_khusus' => ['nama_ketersediaan'],
                'id_petugas_ruangan'     => [
                    'id_orang' => ['nama'],
                ],
                'id_petugas_ok'          => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
