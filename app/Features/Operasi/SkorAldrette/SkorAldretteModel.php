<?php
declare(strict_types=1);

namespace App\Features\Operasi\SkorAldrette;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SkorAldretteModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkorAldretteDatabase(),
            [
                'id_skor_aldrette'   => V::DEFAULT(),
                'waktu_penilaian'    => V::DEFAULT(),
                // 'total_skor'         => V::DEFAULT(),
                'is_boleh_pindah' => V::DEFAULT(),
                'catatan_keluar'  => V::DEFAULT(),
                'instruksi_rr'    => V::DEFAULT(),
            ],
            [
                'nomor_reg'          => ['nomor_rawat'],
                'id_petugas'         => [],
                'id_dokter_anestesi' => [],
                'skor_aktivitas'     => ['nama_skala', 'nilai'],
                'skor_respirasi'     => ['nama_skala', 'nilai'],
                'skor_tekanan_darah' => ['nama_skala', 'nilai'],
                'skor_kesadaran'     => ['nama_skala', 'nilai'],
                'skor_warna_kulit'   => ['nama_skala', 'nilai'],
            ],
        );
    }
}
