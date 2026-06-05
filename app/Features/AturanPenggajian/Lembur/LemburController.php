<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Lembur;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class LemburController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new LemburModel(),
            [
                ['User', 'user'],
                ['Lembur', 'lembur'],
            ],
            'Lembur',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX,  'no_lembur',    'Nomor'],
                [SHOW, REQUIRED, I::TEXT,   'jenis_lembur', 'Jenis'],
                [SHOW, REQUIRED, I::NUMBER, 'jam_lembur',   'Jam'],
                [SHOW, REQUIRED, I::FLOAT,  'pengali_upah', 'Pengali'],
            ],
        );
    }
}
