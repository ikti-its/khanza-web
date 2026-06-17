<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriaseSekunder;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DataTriaseSekunderModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseSekunderDatabase(),
            [
                'id_triase_sekunder' => V::DEFAULT(),
                'anamnesa_singkat'   => V::DEFAULT(),
                'catatan'            => V::DEFAULT(),
                'tanggal_triase'     => V::DEFAULT(),
            ],
            [
                'id_triase'        => [
                    'id_registrasi' => ['nomor_rawat']
                ],
                'id_plan_sekunder' => ['nama_plan_sekunder'],
                'id_petugas'       => [
                    'id_orang'      => ['nama']
                ],
            ],
        );
    }
}
