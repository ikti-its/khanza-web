<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasiPaket;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class TagihanOperasiPaketModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TagihanOperasiPaketDatabase(),
            [
                'id_tagihan_paket' => V::DEFAULT(),
            ],
            [
                'id_tagihan' => [],
                'id_paket'   => ['id_komponen' => ['nama_komponen']],
            ],
        );
    }
}