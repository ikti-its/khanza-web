<?php
declare(strict_types=1);

namespace App\Features\RawatInap\StatusBayar;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusBayarDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rawat_inap',
            'status_bayar',
            [
                'id_status_bayar'   => T::ID(5),
                'nama_status_bayar' => T::NAME(20),
            ],
            'id_status_bayar',
            ['nama_status_bayar'],
            [],
            true,
            'status_bayar.csv',
        );
    }
}
