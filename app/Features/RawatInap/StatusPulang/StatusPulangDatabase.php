<?php
declare(strict_types=1);

namespace App\Features\RawatInap\StatusPulang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusPulangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rawat_inap',
            'status_pulang',
            [
                'id_status_pulang'   => T::ID(5),
                'nama_status_pulang' => T::NAME(20),
            ],
            'id_status_pulang',
            ['nama_status_pulang'],
            [],
            true,
            'status_pulang.csv',
        );
    }
}
