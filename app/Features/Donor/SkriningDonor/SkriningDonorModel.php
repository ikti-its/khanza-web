<?php
declare(strict_types=1);

namespace App\Features\Donor\SkriningDonor;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SkriningDonorModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningDonorDatabase(),
            [
                'id_skrining'      => V::DEFAULT(),
                'berat_badan'      => V::DEFAULT(),
                'sistolik'         => V::DEFAULT(),
                'diastolik'        => V::DEFAULT(),
                'nadi'             => V::DEFAULT(),
                'suhu_tubuh'       => V::DEFAULT(),
                'kadar_hemoglobin' => V::DEFAULT(),
            ],
            [
                'id_kunjungan'       => [
                    'nomor_kunjungan',
                    'id_pendonor' => [
                        'nomor_pendonor',
                        'id_orang' => ['nama']
                    ],
                ],
                'id_hasil_anamnesis' => ['nama_hasil'],
                'id_status_skrining' => ['nama_status_skrining'],
            ],
        );
    }
}
