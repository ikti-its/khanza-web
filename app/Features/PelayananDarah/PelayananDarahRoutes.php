<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah;

use App\Core\Route\RouteTemplate;

final class PelayananDarahRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Pelayanan Darah',
            [
                \App\Features\PelayananDarah\PermintaanDarah\PermintaanDarahController::class,
                \App\Features\PelayananDarah\PermintaanDarahDetail\PermintaanDarahDetailController::class => 'HIDE',
                \App\Features\PelayananDarah\PenyerahanDarah\PenyerahanDarahController::class,
                \App\Features\PelayananDarah\PenyerahanDarahDetail\PenyerahanDarahDetailController::class => 'HIDE',
                \App\Features\PelayananDarah\StatusPermintaan\StatusPermintaanController::class => 'HIDE',
                \App\Features\PelayananDarah\StatusPembayaran\StatusPembayaranController::class => 'HIDE',
            ],
            'pelayanan_darah.svg',
        );
    }
}
