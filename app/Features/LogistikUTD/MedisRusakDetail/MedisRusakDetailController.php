<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisRusakDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class MedisRusakDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MedisRusakDetailModel(),
            [
                ['Logistik UTD',    'logistik_utd'],
                ['BHP Medis Rusak Detail', 'bhp_medis_rusak_detail'],
            ],
            'BHP Medis Rusak Detail',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_medis_rusak_detail', 'ID Medis Rusak Detail'],
                [SHOW, OPTIONAL, I::INDEX,  'id_medis_rusak',        'ID Medis Rusak'],
                [SHOW, OPTIONAL, I::INDEX,  'id_barang',             'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga_beli',            'Harga Beli'],
            ],
        );
    }
}
