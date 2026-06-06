<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisDonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class MedisDonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisDonorModel(),
            [
                ['Logistik UTD',    'logistik_utd'],
                ['BHP Medis Donor', 'bhp_medis_donor'],
            ],
            'BHP Medis Donor',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_medis_donor',       'ID Medis Donor'],
                [SHOW, REQUIRED, I::INDEX,  'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',            'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',               'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',                'Harga'],
            ],
        );
    }
}
