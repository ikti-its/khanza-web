<?php
declare(strict_types=1);

namespace App\Features\UjiDarah;

use App\Core\Route\RouteTemplate;

final class UjiDarahRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Uji Darah',
            [
                \App\Features\UjiDarah\HasilUjiSaring\HasilUjiSaringController::class,
                \App\Features\UjiDarah\MetodeUji\MetodeUjiController::class,
            ],
            'uji_darah.svg',
        );
    }
}
