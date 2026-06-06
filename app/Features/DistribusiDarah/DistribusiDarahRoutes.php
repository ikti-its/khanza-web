<?php
declare(strict_types=1);

namespace App\Features\DistribusiDarah;

use App\Core\Route\RouteTemplate;

final class DistribusiDarahRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Pelayanan Darah',
            [
                \App\Features\DistribusiDarah\PermintaanDarah\PermintaanDarahController::class,
                \App\Features\DistribusiDarah\PermintaanDarahDetail\PermintaanDarahDetailController::class,
                \App\Features\DistribusiDarah\PenyerahanDarah\PenyerahanDarahController::class,
                \App\Features\DistribusiDarah\PenyerahanDarahDetail\PenyerahanDarahDetailController::class,
                \App\Features\DistribusiDarah\StatusPermintaan\StatusPermintaanController::class,
                \App\Features\DistribusiDarah\StatusPembayaran\StatusPembayaranController::class,
            ],
            'pelayanan_darah.svg',
        );
    }
}
