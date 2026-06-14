<?php
declare(strict_types=1);

namespace App\Features\Ruangan;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RuanganModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RuanganDatabase(),
            [
                'id_ruangan'      => V::DEFAULT(),
                'kode_ruangan'    => V::DEFAULT(),
                'nama_ruangan'    => V::DEFAULT(),
                'jenis_instalasi' => V::DEFAULT(),
            ],
            []
        );
    }
}