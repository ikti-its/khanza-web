<?php
declare(strict_types=1);

namespace App\Features\Operasi\PaketTindakanOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PaketTindakanOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PaketTindakanOperasiDatabase(),
            [
                'id_paket'      => V::DEFAULT(),
                'tarif_kelas_3' => V::DEFAULT(),
                'tarif_kelas_2' => V::DEFAULT(),
                'tarif_kelas_1' => V::DEFAULT(),
                'tarif_vip'     => V::DEFAULT(),
                'tarif_vvip'    => V::DEFAULT(),
            ],
            [
                'id_tindakan' => ['nama_tindakan'],
                'id_komponen' => ['nama_komponen'],
            ],
        );
    }
}
