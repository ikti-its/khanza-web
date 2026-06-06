<?php
declare(strict_types=1);

namespace App\Features\Darah;

use App\Core\Route\RouteTemplate;

final class DarahRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Darah',
            [
                \App\Features\Darah\GolonganDarah\GolonganDarahController::class,
                \App\Features\Darah\KomponenDarah\KomponenDarahController::class,
                \App\Features\Darah\Rhesus\RhesusController::class,
            ],
            'darah.svg',
        );
    }
}
