<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusRawat;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusRawatController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusRawatModel(),
            [
                ['Registrasi',          'registrasi'],
                ['Status Rawat', 'status_rawat'],
            ],
            'Status Rawat',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_rawat',   'ID Status Rawat'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_rawat', 'Status Rawat'],
            ],
        );
    }
}
