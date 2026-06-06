<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan\RefSkriningBatuk;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefSkriningBatukController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefSkriningBatukModel(),
            [
                ['Skrining Rawat Jalan',     'skrining_rawat_jalan'],
                ['Referensi Skrining Batuk', 'ref_skrining_batuk'],
            ],
            'Referensi Skrining Batuk',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_batuk',       'ID Batuk'],
                [SHOW, REQUIRED, I::TEXT,  'kategori_batuk', 'Kategori Batuk'],
            ],
        );
    }
}
