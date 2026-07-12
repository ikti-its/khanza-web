<?php
declare(strict_types=1);

namespace App\Features\Operasi\TimeOutSebelumInsisiPenunjang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TimeOutSebelumInsisiPenunjangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TimeOutSebelumInsisiPenunjangModel(),
            [
                ['Operasi',                           'operasi'],
                ['Time Out Sebelum Insisi Penunjang', 'time_out_sebelum_insisi_penunjang'],
            ],
            'Time Out Sebelum Insisi Penunjang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_penunjang',    'ID Penunjang'],
                [HIDE, OPTIONAL, I::INDEX, 'id_timeout',      'ID Time Out'],
                [SHOW, REQUIRED, I::TEXT,  'jenis_penunjang', 'Jenis Penunjang'],
                [SHOW, REQUIRED, I::INDEX, 'id_status',       'Status'],
            ],
        );
    }
}
