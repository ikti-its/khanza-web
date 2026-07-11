<?php
declare(strict_types=1);

namespace App\Features\Finansial;

use App\Core\Route\RouteTemplate;

final class FinansialRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Finansial',
            [
                \App\Features\Finansial\Bank\BankController::class,
                \App\Features\Finansial\PemilikBank\PemilikBankController::class,
                \App\Features\Finansial\PrinsipBank\PrinsipBankController::class,
                \App\Features\Finansial\MetodePembayaran\MetodePembayaranController::class,
                \App\Features\Finansial\Rekening\RekeningController::class,
                \App\Features\Finansial\Transaksi\TransaksiController::class,
            ],
            'finansial.svg',
        );
    }
}
