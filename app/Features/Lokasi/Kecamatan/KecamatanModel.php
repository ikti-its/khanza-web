<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kecamatan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class KecamatanModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KecamatanDatabase(),
            [
                'id_kecamatan'   => V::DEFAULT(),
                'id_kec_lokal'   => V::DEFAULT(),
                'nama_kecamatan' => V::DEFAULT(),
            ],
            [
                'id_provinsi'   => ['nama_provinsi'],
                'id_kota_lokal' => ['nama_kota'],
            ],
        );
    }
}
