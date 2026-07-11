<?php
declare(strict_types=1);

namespace App\Features\Finansial\MetodePembayaran;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class MetodePembayaranController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new MetodePembayaranModel(),
            [
                ['Finansial',         'finansial'],
                ['Metode Pembayaran', 'metode-pembayaran'],
            ],
            'Metode Pembayaran',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_metode',   'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_metode', 'Metode'],
                [SHOW, REQUIRED, I::MONEY, 'biaya',       'Biaya'],
            ],
        );
    }
}
