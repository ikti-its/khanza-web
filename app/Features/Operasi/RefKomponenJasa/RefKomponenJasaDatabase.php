<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKomponenJasa;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefKomponenJasaDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'ref_komponen_jasa',
            [
                'id_komponen'   => T::ID(20),
                'nama_komponen' => T::TEXT(),
            ],
            'id_komponen',
            [],
            [],
            true,
            'komponen_jasa.csv',
        );
    }
}
