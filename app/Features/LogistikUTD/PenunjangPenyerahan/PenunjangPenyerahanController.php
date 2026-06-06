<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangPenyerahan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenunjangPenyerahanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangPenyerahanModel(),
            [
                ['Logistik UTD',             'logistik_utd'],
                ['BHP Non Medis Penyerahan', 'bhp_non_medis_penyerahan'],
            ],
            'BHP Non Medis Penyerahan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_penyerahan', 'ID Penunjang Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_penyerahan',           'ID Penyerahan'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',               'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                  'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',                   'Harga'],
            ],
        );
    }
}
