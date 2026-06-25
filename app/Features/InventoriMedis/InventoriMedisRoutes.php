<?php
declare(strict_types=1);

namespace App\Features\InventoriMedis;

use App\Core\Route\RouteTemplate;

final class InventoriMedisRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Inventori Medis',
            [
                \App\Features\InventoriMedis\DataBarang\DataBarangController::class,
            ],
            'inventaris_medis.svg',
        );
    }
}
