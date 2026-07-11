<?php
declare(strict_types=1);

namespace App\Features\Unit;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class UnitModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new UnitDatabase(),
            [
                'id_unit'               => V::DEFAULT(),
                'kode_unit'             => V::DEFAULT(),
                'nama_unit'             => V::DEFAULT(),
                'biaya_registrasi_baru' => V::DEFAULT(),
                'biaya_registrasi_lama' => V::DEFAULT(),
            ],
            [],
        );
    }
}
