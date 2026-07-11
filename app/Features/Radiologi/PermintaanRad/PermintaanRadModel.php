<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRad;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanRadModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanRadDatabase(),
            [
                'id_permintaan'      => V::DEFAULT(),
                'no_permintaan'      => V::DEFAULT(),
                'tgl_jam_permintaan' => V::DEFAULT(),
                'informasi_tambahan' => V::DEFAULT(),
                'indikasi_klinis'    => V::DEFAULT(),
                'tgl_jam_sampel'     => V::DEFAULT(),
            ],
            [
                'nomor_reg'            => [
                    'nomor_reg',
                    'id_pasien' => [
                        'nomor_rm',
                        'id_orang' => ['nama', 'tanggal_lahir'],
                    ],
                ],
                'id_status_permintaan' => ['nama_status'],
            ],
        );
    }
}
