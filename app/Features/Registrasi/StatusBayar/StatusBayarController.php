<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusBayar;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusBayarController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusBayarModel(),
            [
                ['Registrasi', 'registrasi'],
                ['Status Bayar', 'status_bayar'],
            ],
            'Status Bayar',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_bayar',   'ID Status Bayar'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_bayar', 'Nama Status Bayar'],
            ],
        );
    }
}
