<?php
declare(strict_types=1);

namespace App\Features\Poliklinik;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class PoliklinikModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PoliklinikDatabase(),
            [
                'id_poliklinik'         => V::DEFAULT(),
                'kode_poliklinik'       => V::DEFAULT(),
                'nama_poliklinik'       => V::DEFAULT(),
                'biaya_registrasi_baru' => V::DEFAULT(),
                'biaya_registrasi_lama' => V::DEFAULT(),
            ],
            [],
        );
    }
}