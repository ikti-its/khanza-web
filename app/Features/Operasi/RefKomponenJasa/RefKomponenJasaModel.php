<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKomponenJasa;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefKomponenJasaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefKomponenJasaDatabase(),
            [
                'id_komponen'   => V::DEFAULT(),
                'nama_komponen' => V::DEFAULT(),
            ],
            [],
        );
    }
}
