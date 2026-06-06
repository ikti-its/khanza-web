<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangDonor;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenunjangDonorController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangDonorModel(),
            [
                ['Logistik UTD',        'logistik_utd'],
                ['BHP Non Medis Donor', 'bhp_non_medis_donor'],
            ],
            'BHP Non Medis Donor',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_donor',   'ID Penunjang Donor'],
                [SHOW, REQUIRED, I::INDEX,  'id_pengambilan_darah', 'ID Pengambilan Darah'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',            'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',               'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',                'Harga'],
            ],
        );
    }
}
