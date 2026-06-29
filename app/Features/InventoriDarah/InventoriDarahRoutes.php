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
                \App\Features\InventoriDarah\KomponenDarah\KomponenDarahController::class,
                \App\Features\InventoriDarah\PemisahanKomponen\PemisahanKomponenController::class,
                \App\Features\InventoriDarah\PemisahanKomponenDetail\PemisahanKomponenDetailController::class => 'HIDE',
                \App\Features\InventoriDarah\StokDarah\StokDarahController::class,
                \App\Features\InventoriDarah\StatusStok\StatusStokController::class => 'HIDE',
                \App\Features\InventoriDarah\SumberDarah\SumberDarahController::class => 'HIDE',
            ],
            'inventaris_darah.svg',
        );
    }
}
