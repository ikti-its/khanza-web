<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilDiagnostik;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilDiagnostikModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilDiagnostikDatabase(),
            [
                'id_diagnostik'     => V::DEFAULT(),
                'tanggal_hasil'     => V::DEFAULT(),
                'fasyankes_rujukan' => V::DEFAULT(),
                'dokter_pemeriksa'  => V::DEFAULT(),
            ],
            [
                'id_kasus' => ['nomor_kasus'],
            ],
        );
    }
}
