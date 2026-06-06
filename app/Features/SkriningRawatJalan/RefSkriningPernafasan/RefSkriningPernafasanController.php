<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningPernafasan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefSkriningPernafasanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningPernafasanModel(),
            [
                ['Rawat Jalan',                   'rawat_jalan'],
                ['Referensi Skrining Pernafasan', 'ref_skrining_pernafasan'],
            ],
            'Referensi Skrining Pernafasan',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pernafasan', 'ID Pernafasan'],
                [SHOW, REQUIRED, I::TEXT,  'pernafasan',    'Pernafasan'],
            ],
        );
    }
}
