<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusak;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenunjangRusakController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangRusakModel(),
            [
                ['Logistik UTD',        'logistik_utd'],
                ['BHP Non Medis Rusak', 'bhp_non_medis_rusak'],
            ],
            'BHP Non Medis Rusak',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_rusak', 'ID Penunjang Rusak'],
                [SHOW, REQUIRED, I::INDEX,  'id_petugas',         'ID Petugas'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal_rusak',      'Tanggal Rusak'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',         'Keterangan'],
            ],
        );
    }
}
