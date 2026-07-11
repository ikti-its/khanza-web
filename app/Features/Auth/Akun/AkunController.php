<?php
declare(strict_types=1);

namespace App\Features\Auth\Akun;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class AkunController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AkunModel(),
            [
                ['Auth', 'auth'],
                ['Akun', 'akun'],
            ],
            'Akun',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id',       'ID Akun'],
                [SHOW, REQUIRED, I::TEXT,  'email',    'Email'],
                [SHOW, REQUIRED, I::TEXT,  'password', 'Password'],
                [SHOW, REQUIRED, I::TEXT,  'role',     'Role'],
            ],
        );
    }
}
