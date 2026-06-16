<?php
declare(strict_types=1);

namespace App\Features\Unit;

use App\Core\Route\RouteTemplate;

final class UnitRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Unit',
            [
                \App\Features\Unit\UnitController::class,
            ],
            'unit.svg',
        );
    }
}
