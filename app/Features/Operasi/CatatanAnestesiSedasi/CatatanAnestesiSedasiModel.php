<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class CatatanAnestesiSedasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiDatabase(),
            [
                'id_catatan_anestesi'  => V::DEFAULT(),
                'waktu_catatan'        => V::DEFAULT(),
                'diagnosa_pra_bedah'   => V::DEFAULT(),
                'diagnosa_paska_bedah' => V::DEFAULT(),
                'jam_pengkajian'       => V::DEFAULT(),
                'sistolik'             => V::DEFAULT(),
                'diastolik'            => V::DEFAULT(),
                'nadi'                 => V::DEFAULT(),
                'respiratory_rate'     => V::DEFAULT(),
                'suhu'                 => V::DEFAULT(),
                'saturasi_o2'          => V::DEFAULT(),
                'tinggi_badan_cm'      => V::DEFAULT(),
                'berat_badan_kg'       => V::DEFAULT(),
                'hemoglobin'           => V::DEFAULT(),
                'hematokrit'           => V::DEFAULT(),
                'leukosit'             => V::DEFAULT(),
                'trombosit'            => V::DEFAULT(),
                'bleeding_time_bt'     => V::DEFAULT(),
                'clotting_time_ct'     => V::DEFAULT(),
                'gula_darah_sewaktu'   => V::DEFAULT(),
                'klinis_lain_lain'     => V::DEFAULT(),
                'is_alergi'            => V::DEFAULT(),
                'ket_alergi'           => V::DEFAULT(),
                'penyulit_pra'         => V::DEFAULT(),
                'is_lanjut_tindakan'   => V::DEFAULT(),
                'ket_sedasi'           => V::DEFAULT(),
                'is_epidural'          => V::DEFAULT(),
                'is_spinal'            => V::DEFAULT(),
                'is_anestesi_umum'     => V::DEFAULT(),
                'ket_anestesi_umum'    => V::DEFAULT(),
                'is_blok_perifer'      => V::DEFAULT(),
                'ket_blok_perifer'     => V::DEFAULT(),
                'is_batal_tindakan'    => V::DEFAULT(),
                'alasan_batal'         => V::DEFAULT(),
            ],
            [
                'id_jadwal'           => [
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
                'id_tindakan'         => ['nama_tindakan'],
                'id_dokter_anestesi'  => [
                    'id_orang' => ['nama'],
                ],
                'id_dokter_bedah'     => [
                    'id_orang' => ['nama'],
                ],
                'id_perawat_anestesi' => [
                    'id_orang' => ['nama'],
                ],
                'id_perawat_bedah'    => [
                    'id_orang' => ['nama'],
                ],
                'id_kesadaran'        => ['nama_kesadaran'],
                'id_golongan_darah'   => ['nama_golongan_darah'],
                'id_rhesus'           => ['kode_rhesus'],
                'id_asa'              => ['nama_asa'],
                'id_jenis_sedasi'     => ['nama_sedasi'],
            ],
        );
    }
}
