<?php
declare(strict_types=1);

namespace App\Features\RawatInap\JenisBayar;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JenisBayarDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rawat_inap',
            'jenis_bayar',
            [
                'id_jenis_bayar'   => T::ID(5),
                'nama_jenis_bayar' => T::NAME(20),
            ],
            'id_jenis_bayar',
            ['nama_jenis_bayar'],
            [],
            true,
            'jenis_bayar.csv',
        );
    }
}
