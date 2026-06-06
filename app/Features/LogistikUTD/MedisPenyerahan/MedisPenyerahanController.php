<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisPenyerahan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class MedisPenyerahanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisPenyerahanModel(),
            [
                ['Logistik UTD',         'logistik_utd'],
                ['BHP Medis Penyerahan', 'bhp_medis_penyerahan'],
            ],
            'BHP Medis Penyerahan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_medis_penyerahan', 'ID Medis Penyerahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_penyerahan',       'ID Penyerahan'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',           'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',              'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',               'Harga'],
            ],
        );
    }
}
