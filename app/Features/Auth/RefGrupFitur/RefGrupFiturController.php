<?php

declare(strict_types=1);

namespace App\Features\Auth\RefGrupFitur;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefGrupFiturController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefGrupFiturModel(),
            [
                ['Auth',                 'auth'],
                ['Referensi Grup Fitur', 'ref_grup_fitur'],
            ],
            'Referensi Grup Fitur',
            [
                // Daftar grup fitur mengikuti *Routes.php yang terdaftar di
                // AllRoutes.php, jadi perubahan lewat UI tidak akan berpengaruh.
                A::READ,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'slug',      'Slug'],
                [SHOW, REQUIRED, I::TEXT,  'nama_grup', 'Nama Grup'],
            ],
        );
    }
}
