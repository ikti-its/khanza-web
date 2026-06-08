<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpname;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StokOpnameController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokOpnameModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Stok Opname',         'stok_opname'],
            ],
            'Stok Opname',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_opname',             'ID Opname'],
                [SHOW, REQUIRED, I::DATE,   'tanggal',               'Tanggal'],
                [SHOW, OPTIONAL, I::SELECT, 'id_status_stok_opname', 'Status'],
                [SHOW, OPTIONAL, I::TEXT,   'catatan',               'Catatan'],
                [SHOW, OPTIONAL, I::TEXT,   'catatan_atasan',        'Catatan Atasan'],
            ],
            child_path: '/inventori-non-medis/detail-stok-opname',
            child_fk: 'id_opname',
        );
    }
}
