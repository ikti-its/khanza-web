<?php
declare(strict_types=1);

namespace App\Features\Person\Kewarganegaraan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KewarganegaraanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KewarganegaraanModel(),
            [
                ['Person',  'person'],
                ['Kewarganegaraan', 'kewarganegaraan'],
            ],
            'Kewarganegaraan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_kewarganegaraan', 'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_negara',        'Negara'],
            ],
        );
    }
}
