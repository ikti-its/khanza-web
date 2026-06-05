<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Pulau;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PulauController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PulauModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Pulau', 'pulau'],
            ],
            'Pulau',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_pulau',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_pulau', 'Pulau'],
            ],
        );
    }
}
