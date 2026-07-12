<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostopPenunjang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ChecklistPostopPenunjangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopPenunjangModel(),
            [
                ['Operasi',                          'operasi'],
                ['Checklist Post Operasi Penunjang', 'checklist_postop_penunjang'],
            ],
            'Checklist Post Operasi Penunjang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_penunjang',      'ID Penunjang'],
                [HIDE, OPTIONAL, I::INDEX, 'id_checklist_post', 'ID Checklist Post'],
                [SHOW, REQUIRED, I::TEXT,  'jenis_penunjang',   'Jenis Penunjang'],
                [SHOW, REQUIRED, I::INDEX, 'id_ketersediaan',   'Ketersediaan'],
                [SHOW, REQUIRED, I::TEXT,  'keterangan',        'Keterangan'],
            ],
        );
    }
}
