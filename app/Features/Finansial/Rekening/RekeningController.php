<?php
declare(strict_types=1);

namespace App\Features\Finansial\Rekening;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RekeningController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RekeningModel(),
            [
                ['Finansial', 'finansial'],
                ['Rekening',  'rekening'],
            ],
            'Rekening',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_rekening',    'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_akun',      'Akun'],
                [SHOW, REQUIRED, I::TEXT,  'nama_bank',      'Bank'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_rekening', 'Nomor Rekening'],
            ],
        );
    }
}
