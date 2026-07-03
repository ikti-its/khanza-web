<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasiTim;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class JadwalOperasiTimModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiTimDatabase(),
            [
                'id_jadwal_tim' => V::DEFAULT(),
            ],
            [
                'id_jadwal' => [],
                'id_dokter' => ['nama'],
            ],
        );
    }
}