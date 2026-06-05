<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Alamat;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class AlamatModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AlamatDatabase(),
            [
                'id_alamat'      => V::DEFAULT(),
                'rw'             => V::DEFAULT(),
                'rt'             => V::DEFAULT(),
                'alamat_lengkap' => V::DEFAULT(),
            ],
            [
                'id_provinsi'   => ['nama_provinsi'], 
                'id_kota_lokal' => ['nama_kota'],
                'id_kec_lokal'  => ['nama_kecamatan'],
                'id_desa_lokal' => ['nama_desa'],
            ],
        );
    }
}
