<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Pesangon;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PesangonController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PesangonModel(),
            [
                ['User',     'user'],
                ['Pesangon', 'pesangon'],
            ],
            'Pesangon',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX,  'no_pesangon',  'Nomor Pesangon'],
                [SHOW, REQUIRED, I::NUMBER, 'masa_kerja',   'Masa Kerja (tahun)'],
                [SHOW, REQUIRED, I::FLOAT,  'pengali_upah', 'Pengali Upah'],
            ],
        );
    }
}
