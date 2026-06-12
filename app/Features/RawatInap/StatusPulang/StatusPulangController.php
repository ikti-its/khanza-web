<?php
declare(strict_types=1);

namespace App\Features\RawatInap\StatusPulang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusPulangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPulangModel(),
            [
                ['Rawat Inap',    'rawat_inap'],
                ['Status Pulang', 'status_pulang'],
            ],
            'Status Pulang',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_pulang',   'ID Status Pulang'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_pulang', 'Nama Status Pulang'],
            ],
        );
    }
}
