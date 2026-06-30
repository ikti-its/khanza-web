<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusPoli;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusPoliController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPoliModel(),
            [
                ['Registrasi',          'registrasi'],
                ['Status Poli', 'status_poli'],
            ],
            'Status Poli',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_poli',   'ID Status Poli'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_poli', 'Status Poli'],
            ],
        );
    }
}
