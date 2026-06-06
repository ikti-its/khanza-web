<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningKeputusan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefSkriningKeputusanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningKeputusanModel(),
            [
                ['Skrining Rawat Jalan',         'skrining_rawat_jalan'],
                ['Referensi Skrining Keputusan', 'ref_skrining_keputusan'],
            ],
            'Referensi Skrining Keputusan',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_keputusan',       'ID Keputusan'],
                [SHOW, REQUIRED, I::TEXT,  'skrining_keputusan', 'Skrining Keputusan'],
            ],
        );
    }
}
