<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusRegistrasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusRegistrasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusRegistrasiModel(),
            [
                ['Registrasi',        'registrasi'],
                ['Status Registrasi', 'status_registrasi'],
            ],
            'Status Registrasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_registrasi',   'ID Status Registrasi'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_registrasi', 'Status Registrasi'],
            ],
        );
    }
}
