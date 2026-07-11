<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD\DataTriasePrimer;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DataTriasePrimerController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DataTriasePrimerModel(),
            [
                ['Triase UGD',         'triase_ugd'],
                ['Data Triase Primer', 'data_triase_primer'],
            ],
            'Data Triase Primer',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_triase_primer',    'ID Triase Primer'],
                [SHOW, REQUIRED, I::INDEX,  'id_triase',           'ID Triase'],
                [SHOW, REQUIRED, I::TEXT,   'keluhan_utama',       'Keluhan Utama'],
                [SHOW, REQUIRED, I::SELECT, 'id_kebutuhan_khusus', 'Kebutuhan Khusus'],
                [SHOW, REQUIRED, I::TEXT,   'catatan',             'Catatan'],
                [SHOW, REQUIRED, I::SELECT, 'id_plan_primer',      'Plan Primer'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_triase',      'Tanggal Triase'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',          'Petugas'],
            ],
        );
    }
}
