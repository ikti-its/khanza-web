<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasien;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PenyerahanPasienModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanPasienDatabase(),
            [
                'id_penyerahan'            => V::DEFAULT(),
                'waktu_masuk_asal'         => V::DEFAULT(),
                'waktu_pindah'             => V::DEFAULT(),
                'ket_indikasi'             => V::DEFAULT(),
                'diagnosa_utama'           => V::DEFAULT(),
                'diagnosa_sekunder'        => V::DEFAULT(),
                'prosedur_dilakukan'       => V::DEFAULT(),
                'obat_diberikan'           => V::DEFAULT(),
                'pemeriksaan_penunjang'    => V::DEFAULT(),
                'is_disetujui'             => V::DEFAULT(),
                'nama_pemberi_persetujuan' => V::DEFAULT(),
                'asal_sistolik'            => V::DEFAULT(),
                'asal_diastolik'           => V::DEFAULT(),
                'asal_nadi'                => V::DEFAULT(),
                'asal_respiratory_rate'    => V::DEFAULT(),
                'asal_suhu'                => V::DEFAULT(),
                'asal_keluhan'             => V::DEFAULT(),
                'tiba_sistolik'            => V::DEFAULT(),
                'tiba_diastolik'           => V::DEFAULT(),
                'tiba_nadi'                => V::DEFAULT(),
                'tiba_respiratory_rate'    => V::DEFAULT(),
                'tiba_suhu'                => V::DEFAULT(),
                'tiba_keluhan'             => V::DEFAULT(),
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
                'id_indikasi'            => ['nama_indikasi'],
                'id_ruang_asal'          => ['nama_ruangan'],
                'id_ruang_selanjutnya'   => ['nama_ruangan'],
                'id_metode'              => ['nama_metode'],
                'id_hubungan'            => ['nama_hubungan'],
                'asal_id_keadaan'        => ['nama_keadaan'],
                'tiba_id_keadaan'        => ['nama_keadaan'],
                'id_perawat_menyerahkan' => [
                    'id_orang' => ['nama'],
                ],
                'id_perawat_menerima'    => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
