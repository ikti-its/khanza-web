<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\PenunjangRusakDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenunjangRusakDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenunjangRusakDetailModel(),
            [
                ['Logistik UTD',    'logistik_utd'],
                ['BHP Non Medis Rusak Detail', 'bhp_non_medis_rusak_detail'],
            ],
            'BHP Non Medis Rusak Detail',
            [
                A::READ,
                // A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penunjang_rusak_detail', 'ID Penunjang Rusak Detail'],
                [SHOW, REQUIRED, I::INDEX,  'id_penunjang_rusak',        'ID Penunjang Rusak'],
                [SHOW, REQUIRED, I::INDEX,  'id_barang',                 'ID Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',                    'Jumlah'],
                [SHOW, REQUIRED, I::MONEY,  'harga_beli',                'Harga Beli'],
            ],
        );
    }
}
