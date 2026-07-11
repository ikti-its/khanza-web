<?php
declare(strict_types=1);

namespace App\Features\Pendidikan\Gelar;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class GelarController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new GelarModel(),
            [
                ['Pendidikan', 'pendidikan'],
                ['Gelar',      'gelar'],
            ],
            'Gelar',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_gelar',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_gelar', 'Gelar'],
            ],
        );
    }
}
