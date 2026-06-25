<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKategoriOperasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefKategoriOperasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefKategoriOperasiDatabase(),
            [
                'id_kategori'   => V::DEFAULT(),
                'nama_kategori' => V::DEFAULT(),
            ],
            [],
        );
    }
}
