<?php

declare(strict_types=1);

namespace App\Features\Auth\AksesFitur;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class AksesFiturController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AksesFiturModel(),
            [
                ['Auth',        'auth'],
                ['Akses Fitur', 'akses_fitur'],
            ],
            'Akses Fitur',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, REQUIRED, I::INDEX,  'id',            'ID'],
                [SHOW, REQUIRED, I::SELECT, 'id_role',       'Role'],
                [SHOW, REQUIRED, I::SELECT, 'feature_group', 'Grup Fitur'],
                [SHOW, REQUIRED, I::BOOL,   'boleh_baca',    'Boleh Baca'],
                [SHOW, REQUIRED, I::BOOL,   'boleh_tulis',   'Boleh Tulis'],
            ],
        );
    }
}
