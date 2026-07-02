<?php
declare(strict_types=1);

namespace App\Features\UGD\StatusTriase;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusTriaseDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'ugd',
            'status_triase',
            [
                'id_status_triase'   => T::ID(5),
                'nama_status_triase' => T::NAME(20),
            ],
            'id_status_triase',
            ['nama_status_triase'],
            [],
            true,
            'status_triase.csv',
        );
    }
}
