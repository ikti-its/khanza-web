<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Desa;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class DesaModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DesaDatabase(),
            [
                'id_desa'       => V::DEFAULT(),
                'id_desa_lokal' => V::DEFAULT(),
                'nama_desa'     => V::DEFAULT(),
            ],
            [
                'id_provinsi'   => ['nama_provinsi'],
                'id_kota_lokal' => ['nama_kota'],
                'id_kec_lokal'  => ['nama_kecamatan'],
            ],
        );
    }
}
