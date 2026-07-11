<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriasePrimer;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DataTriasePrimerModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriasePrimerDatabase(),
            [
                'id_triase_primer' => V::DEFAULT(),
                'keluhan_utama'    => V::DEFAULT(),
                'catatan'          => V::DEFAULT(),
                'tanggal_triase'   => V::DEFAULT(),
            ],
            [
                'id_triase'           => [
                    'id_registrasi' => ['nomor_rawat'],
                ],
                'id_kebutuhan_khusus' => ['nama_kebutuhan'],
                'id_plan_primer'      => ['nama_plan_primer'],
                'id_petugas'          => [
                    'id_orang' => ['nama'],
                ],
            ],
        );
    }
}
