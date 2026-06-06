<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefSkriningSkalaNyeriController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningSkalaNyeriModel(),
            [
                ['Rawat Jalan',                    'rawat_jalan'],
                ['Referensi Skrining Skala Nyeri', 'ref_skrining_skala_nyeri'],
            ],
            'Referensi Skrining Skala Nyeri',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_skala_nyeri', 'ID Skala Nyeri'],
                [SHOW, REQUIRED, I::TEXT,  'skala_nyeri',    'Skala Nyeri'],
            ],
        );
    }
}
