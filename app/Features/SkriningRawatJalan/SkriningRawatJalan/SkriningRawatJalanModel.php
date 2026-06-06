<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\SkriningRawatJalan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SkriningRawatJalanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SkriningRawatJalanDatabase(),
            [
                'id_skrining'      => V::DEFAULT(),
                'tgl_skrining'     => V::DEFAULT(),
                'jam_skrining'     => V::DEFAULT(),
                'is_geriatri'      => V::DEFAULT(),
                'is_risiko_jatuh'  => V::DEFAULT(),
                ],
            [
                'no_rm'          => ['nomor_rm'],
                'id_kesadaran'   => ['kesadaran'],
                'id_pernafasan'  => ['pernafasan'],
                'id_skala_nyeri' => ['skala_nyeri'],
                'id_nyeri_dada'  => ['nyeri_dada'],
                'id_batuk'       => ['kategori_batuk'],
                'id_keputusan'   => ['skrining_keputusan'],
                'id_petugas'     => [
                    'id_orang'   => ['nama'],
                ],
            ],
        );
    }
}
