<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor\StatusPencekalan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusPencekalanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'penanganan_donor',
            'status_pencekalan',
            [
                'id_status_pencekalan'   => T::ID(5),
                'nama_status_pencekalan' => T::NAME(20),
            ],
            'id_status_pencekalan',
            ['nama_status_pencekalan'],
            [],
            true,
            'status_pencekalan.csv',
        );
    }
}
