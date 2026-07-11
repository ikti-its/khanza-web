<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kota;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KotaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KotaDatabase(),
            [
                'id_kota'       => V::DEFAULT(),
                'id_kota_lokal' => V::DEFAULT(),
                'nama_kota'     => V::DEFAULT(),
            ],
            [
                'id_provinsi' => ['nama_provinsi'],
            ],
        );
    }
}
