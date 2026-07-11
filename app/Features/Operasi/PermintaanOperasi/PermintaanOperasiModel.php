<?php
declare(strict_types=1);

namespace App\Features\Operasi\PermintaanOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanOperasiDatabase(),
            [
                'id_permintaan' => V::DEFAULT(),
                'tanggal_minta' => V::DEFAULT(),
                'is_cito'       => V::DEFAULT(),
            ],
            [
                'nomor_reg'   => [
                    'nomor_reg',
                    'id_pasien' => [
                        'nomor_rm',
                        'id_orang' => ['nama'],
                    ],
                ],
                'id_dokter'   => ['id_orang' => ['nama']],
                'id_tindakan' => ['nama_tindakan'],
            ],
        );
    }
}
