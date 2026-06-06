<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah;

use App\Core\Route\RouteTemplate;

final class InventoriDarahRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Inventori Darah',
            [
                \App\Features\InventoriDarah\PemisahanKomponen\PemisahanKomponenController::class,
                \App\Features\InventoriDarah\PemisahanKomponenDetail\PemisahanKomponenDetailController::class,
                \App\Features\InventoriDarah\StokDarah\StokDarahController::class,
                \App\Features\InventoriDarah\StatusStok\StatusStokController::class,
                \App\Features\InventoriDarah\SumberDarah\SumberDarahController::class,
            ],
            'inventaris_darah.svg',
        );
    }
}
