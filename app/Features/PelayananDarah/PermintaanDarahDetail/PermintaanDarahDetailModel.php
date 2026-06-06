<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarahDetail;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PermintaanDarahDetailModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahDetailDatabase(),
            [
                'id_permintaan_detail' => V::DEFAULT(),
                'jumlah'               => V::DEFAULT(),
            ],
            [
                'id_permintaan'     => ['no_permintaan', 'tanggal_permintaan'],
                'id_komponen'       => ['nama_komponen'],
                'id_golongan_darah' => ['nama_golongan_darah'],
                'id_rhesus'         => ['kode_rhesus'],
            ],
        );
    }
}
