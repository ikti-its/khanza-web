<?php
declare(strict_types=1);

namespace App\Features\Finansial\PrinsipBank;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PrinsipBankController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PrinsipBankModel(),
            [
                ['Finansial',    'finansial'],
                ['Prinsip Bank', 'prinsip_bank'],
            ],
            'Prinsip Bank',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_prinsip',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_prinsip', 'Prinsip'],
            ],
        );
    }
}
