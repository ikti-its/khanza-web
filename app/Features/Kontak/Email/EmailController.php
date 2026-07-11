<?php
declare(strict_types=1);

namespace App\Features\Kontak\Email;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class EmailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new EmailModel(),
            [
                ['Kontak', 'kontak'],
                ['Email', 'email'],
            ],
            'Email',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_email',     'ID Email'],
                [SHOW, REQUIRED, I::TEXT,  'alamat_email', 'Email'],
                [SHOW, REQUIRED, I::TEXT,  'nama',         'Nama Orang'],
            ],
        );
    }
}
