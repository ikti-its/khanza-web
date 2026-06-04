<?php
declare(strict_types=1);

namespace App\Features\Operasi\SigninSebelumAnestesi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SigninSebelumAnestesiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SigninSebelumAnestesiDatabase(),
            [
                'id_signin'                 => V::DEFAULT(),
                'waktu_signin'              => V::DEFAULT(),
                'sn_cn'                     => V::DEFAULT(),
                'tindakan'                  => V::DEFAULT(),
                'is_identitas_sesuai'       => V::DEFAULT(),
                'alergi'                    => V::DEFAULT(),
                'is_resiko_aspirasi'        => V::DEFAULT(),
                'rencana_aspirasi'          => V::DEFAULT(),
                'is_resiko_hilang_darah'    => V::DEFAULT(),
                'jalur_iv_line'             => V::DEFAULT(),
                'rencana_hilang_darah'      => V::DEFAULT(),
                'rencana_kesiapan_anestesi' => V::DEFAULT(),
            ],
            [
                'nomor_reg'            => ['nomor_rawat'],
                'kode_dokter_bedah'    => [],
                'kode_dokter_anestesi' => [],
                'id_penandaan_area'    => ['nama_ketersediaan'],
                'id_kesiapan_anestesi' => ['nama_kesiapan'],
                'id_perawat_ok'        => [],
            ],
        );
    }
}
