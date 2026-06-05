<?php
declare(strict_types=1);

namespace App\Features\Kontak\JenisTelepon;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JenisTeleponController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JenisTeleponModel(),
            [
                ['Kontak', 'kontak'],
                ['Jenis Telepon', 'jenis-telepon'],
            ],
            'Jenis Telepon',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX,  'id_jenis',   'ID'],
                [SHOW, REQUIRED, I::TEXT,   'nama_jenis', 'Jenis Telepon'],
            ],
        );
    }
}
