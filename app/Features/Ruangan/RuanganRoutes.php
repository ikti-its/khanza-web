<?php
declare(strict_types=1);

namespace App\Features\Ruangan;

use App\Core\Route\RouteTemplate;

final class RuanganRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Ruangan',
            [
                \App\Features\Ruangan\RuanganController::class,
            ],
            'ruangan.svg',
        );
    }
}
