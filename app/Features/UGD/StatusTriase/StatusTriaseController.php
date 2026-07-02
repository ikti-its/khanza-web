<?php
declare(strict_types=1);

namespace App\Features\UGD\StatusTriase;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusTriaseController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusTriaseModel(),
            [
                ['UGD',           'ugd'],
                ['Status Triase', 'status_triase'],
            ],
            'Status Triase',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_triase',   'ID Status Triase'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_triase', 'Nama Status Triase'],
            ],
        );
    }
}
