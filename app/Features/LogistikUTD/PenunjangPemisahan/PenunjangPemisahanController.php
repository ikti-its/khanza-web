<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangPemisahan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenunjangPemisahanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangPemisahanModel(),
            [
                ['Logistik UTD',            'logistik_utd'],
                ['BHP Non Medis Pemisahan', 'bhp_non_medis_pemisahan'],
            ],
            'BHP Non Medis Pemisahan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_pemisahan', 'ID Penunjang Pemisahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_pemisahan',           'ID Pemisahan'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',              'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                 'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',                  'Harga'],
            ],
        );
    }
}
