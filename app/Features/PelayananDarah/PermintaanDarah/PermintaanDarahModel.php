<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanDarahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahDatabase(),
            [
                'id_permintaan'      => V::DEFAULT(),
                'no_permintaan'      => V::DEFAULT(),
                'tanggal_permintaan' => V::DEFAULT(),
            ],
            [
                'id_registrasi'        => [
                    'nomor_rawat',
                    'id_pasien' => [
                        'nomor_rm',
                        'id_orang' => ['nama']
                    ],
                ],
                'id_dokter_pengirim'   => [
                    'id_orang' => ['nama']
                ],
                'id_status_permintaan' => ['nama_status_permintaan'],
            ],
        );
    }
}
