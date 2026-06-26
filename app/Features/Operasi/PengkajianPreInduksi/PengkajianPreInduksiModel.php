<?php
declare(strict_types=1);

namespace App\Features\Operasi\PengkajianPreInduksi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PengkajianPreInduksiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengkajianPreInduksiDatabase(),
            [
                'id_pengkajian'             => V::DEFAULT(),
                'waktu_pengkajian'          => V::DEFAULT(),
                'sistolik'                  => V::DEFAULT(),
                'diastolik'                 => V::DEFAULT(),
                'nadi'                      => V::DEFAULT(),
                'respiratory_rate'          => V::DEFAULT(),
                'suhu'                      => V::DEFAULT(),
                'elektrokardiogram'         => V::DEFAULT(),
                'vital_lain_lain'           => V::DEFAULT(),
                'is_sesuai_asesmen'         => V::DEFAULT(),
                'perencanaan'               => V::DEFAULT(),
                'infus_perifer'             => V::DEFAULT(),
                'kateter_vena_sentral_cvc'  => V::DEFAULT(),
                'ket_premedikasi'           => V::DEFAULT(),
                'ket_induksi'               => V::DEFAULT(),
                'is_intubasi_sesudah_tidur' => V::DEFAULT(),
                'is_intubasi_oral'          => V::DEFAULT(),
                'is_intubasi_tracheostomi'  => V::DEFAULT(),
                'intubasi_keterangan'       => V::DEFAULT(),
                'sulit_ventilasi'           => V::DEFAULT(),
                'sulit_intubasi'            => V::DEFAULT(),
                'ventilasi_keterangan'      => V::DEFAULT(),
                'teknik_regional_jenis'     => V::DEFAULT(),
                'teknik_regional_lokasi'    => V::DEFAULT(),
                'teknik_regional_jarum'     => V::DEFAULT(),
                'is_kateter'                => V::DEFAULT(),
                'kateter_fiksasi_cm'        => V::DEFAULT(),
                'obat_obatan'               => V::DEFAULT(),
                'komplikasi'                => V::DEFAULT(),
                'hasil'                     => V::DEFAULT(),
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
                'id_dokter_anestesi' => [
                    'id_orang'  => ['nama']
                ],
                'id_posisi'      => ['nama_posisi'],
                'id_premedikasi' => ['nama_premedikasi'],
                'id_induksi'     => ['nama_induksi'],
            ],
        );
    }
}
