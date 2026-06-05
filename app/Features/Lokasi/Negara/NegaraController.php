<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Negara;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class NegaraController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new NegaraModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Negara', 'negara'],
            ],
            'Negara',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX,  'id_negara',    'ID'],
                [SHOW, REQUIRED, I::TEXT,   'nama_negara',  'Negara'],
                [SHOW, REQUIRED, I::TEXT,   'kode_telepon', 'Kode Telepon'],
            ],
        );
    }
}
