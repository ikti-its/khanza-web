<?php
declare(strict_types=1);

namespace App\Features\Donor\StatusSkrining;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusSkriningController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusSkriningModel(),
            [
                ['Donor',           'donor'],
                ['Status Skrining', 'status_skrining'],
            ],
            'Status Skrining',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_skrining',   'ID Status Skrining'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_skrining', 'Nama Status Skrining'],
            ],
        );
    }
}
