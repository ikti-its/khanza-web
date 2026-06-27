<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\SumberDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SumberDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SumberDarahModel(),
            [
                ['Inventori Darah',  'inventori_darah'],
                ['Sumber Darah',     'sumber_darah'],
            ],
            'Sumber Darah',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_sumber_darah',   'ID Sumber Darah'],
                [SHOW, REQUIRED, I::TEXT,  'nama_sumber_darah', 'Nama Sumber Darah'],
            ],
        );
    }
}
