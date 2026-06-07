<?php
declare(strict_types=1);

namespace App\Features\Poliklinik;

use App\Core\Route\RouteTemplate;

final class PoliklinikRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Poliklinik',
            [
                \App\Features\Poliklinik\PoliklinikController::class,
            ],
            'poliklinik.svg',
        );
    }
}
