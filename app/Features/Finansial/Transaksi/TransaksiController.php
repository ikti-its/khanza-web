<?php
declare(strict_types=1);

namespace App\Features\Finansial\Transaksi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TransaksiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiModel(),
            [
                ['Finansial', 'finansial'],
                ['Transaksi', 'transaksi'],
            ],
            'Transaksi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'id_transaksi',    'ID'],
                [SHOW, REQUIRED, I::TEXT,  'rekening_sumber', 'Sumber'],
                [SHOW, REQUIRED, I::TEXT,  'rekening_tujuan', 'Tujuan'],
                [SHOW, REQUIRED, I::TEXT,  'nama_metode',     'Metode Pembayaran'],
                [SHOW, REQUIRED, I::DTIME, 'waktu_transaksi', 'Waktu'],
                [SHOW, REQUIRED, I::MONEY, 'nominal',         'Nominal'],
            ],
        );
    }
}
