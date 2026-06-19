<?php
declare(strict_types=1);

namespace App\Features\Donor\StatusSkrining;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusSkriningDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'donor',
            'status_skrining',
            [
                'id_status_skrining'   => T::ID(5),
                'nama_status_skrining' => T::NAME(20),
            ],
            'id_status_skrining',
            ['nama_status_skrining'],
            [],
            true,
            'status_skrining.csv',
        );
    }
}
