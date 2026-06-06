<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningNyeriDada;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefSkriningNyeriDadaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningNyeriDadaModel(),
            [
                ['Skrining Rawat Jalan',          'skrining_rawat_jalan'],
                ['Referensi Skrining Nyeri Dada', 'ref_skrining_nyeri_dada'],
            ],
            'Referensi Skrining Nyeri Dada',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_nyeri_dada', 'ID Nyeri Dada'],
                [SHOW, REQUIRED, I::TEXT,  'nyeri_dada',    'Nyeri Dada'],
            ],
        );
    }
}
