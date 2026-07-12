<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostopDrain;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class ChecklistPostopDrainController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopDrainModel(),
            [
                ['Operasi',                      'operasi'],
                ['Checklist Post Operasi Drain', 'checklist_postop_drain'],
            ],
            'Checklist Post Operasi Drain',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_drain',          'ID Drain'],
                [HIDE, OPTIONAL, I::INDEX,  'id_checklist_post', 'ID Checklist Post'],
                [SHOW, REQUIRED, I::INDEX,  'id_ketersediaan',   'Ketersediaan'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',            'Jumlah'],
                [SHOW, REQUIRED, I::TEXT,   'letak',             'Letak'],
                [SHOW, REQUIRED, I::TEXT,   'warna',             'Warna'],
            ],
        );
    }
}
