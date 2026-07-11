<?php
declare(strict_types=1);

namespace App\Features\Finansial\PemilikBank;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PemilikBankController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemilikBankModel(),
            [
                ['Finansial',    'finansial'],
                ['Pemilik Bank', 'pemilik_bank'],
            ],
            'Pemilik Bank',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_pemilik',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_pemilik', 'Pemilik'],
            ],
        );
    }
}
