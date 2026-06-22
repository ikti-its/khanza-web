<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\JenisBayar;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JenisBayarDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rekam_medis',
            'jenis_bayar',
            [
                'id_jenis_bayar'   => T::ID(5),
                'nama_jenis_bayar' => T::NAME(20),
            ],
            'id_jenis_bayar',
            [],
            [],
            true,
            'jenis_bayar.csv',
        );
    }
}
