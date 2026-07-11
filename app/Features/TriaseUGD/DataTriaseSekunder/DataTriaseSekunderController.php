<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriaseSekunder;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DataTriaseSekunderController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriaseSekunderModel(),
            [
                ['Triase UGD',           'triase_ugd'],
                ['Data Triase Sekunder', 'data_triase_sekunder'],
            ],
            'Data Triase Sekunder',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_triase_sekunder', 'ID Triase Sekunder'],
                [SHOW, REQUIRED, I::INDEX,  'id_triase',          'ID Triase'],
                [HIDE, REQUIRED, I::TEXT,   'anamnesa_singkat',   'Anamnesa Singkat'],
                [HIDE, REQUIRED, I::TEXT,   'catatan',            'Catatan'],
                [SHOW, REQUIRED, I::SELECT, 'id_plan_sekunder',   'Plan Sekunder'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_triase',     'Tanggal Triase'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',         'ID Petugas'],
            ],
        );
    }
}
