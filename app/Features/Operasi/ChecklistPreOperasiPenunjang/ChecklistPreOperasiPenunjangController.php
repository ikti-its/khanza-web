<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasiPenunjang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ChecklistPreOperasiPenunjangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPreOperasiPenunjangModel(),
            [
                ['Operasi',                         'operasi'],
                ['Checklist Pre Operasi Penunjang', 'checklist_pre_operasi_penunjang'],
            ],
            'Checklist Pre Operasi Penunjang',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_penunjang',       'ID Penunjang'],
                [HIDE, REQUIRED, I::INDEX, 'id_checklist',       'ID Checklist'],
                [HIDE, REQUIRED, I::INDEX, 'id_jenis_penunjang', 'Jenis Penunjang'],
                [SHOW, REQUIRED, I::INDEX, 'id_ketersediaan',    'Ketersediaan'],
                [SHOW, REQUIRED, I::TEXT,  'keterangan',         'Keterangan'],
            ],
        );
    }
}
