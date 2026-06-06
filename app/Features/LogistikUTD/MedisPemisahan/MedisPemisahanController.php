<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisPemisahan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class MedisPemisahanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisPemisahanModel(),
            [
                ['Logistik UTD',        'logistik_utd'],
                ['BHP Medis Pemisahan', 'bhp_medis_pemisahan'],
            ],
            'BHP Medis Pemisahan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_medis_pemisahan', 'ID Medis Pemisahan'],
                [SHOW, REQUIRED, I::INDEX,  'id_pemisahan',       'ID Pemisahan'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',          'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',             'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga',              'Harga'],
            ],
        );
    }
}
