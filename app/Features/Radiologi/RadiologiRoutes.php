<?php
declare(strict_types=1);

namespace App\Features\Radiologi;

use App\Core\Route\RouteTemplate;

final class RadiologiRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Radiologi',
            [
                \App\Features\Radiologi\PermintaanRad\PermintaanRadController::class,
                \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemController::class,
                \App\Features\Radiologi\HasilRad\HasilRadController::class,
                \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanController::class,
                \App\Features\Radiologi\HasilRadBhp\HasilRadBhpController::class,
                \App\Features\Radiologi\RefItemRad\RefItemRadController::class,
                \App\Features\Radiologi\RefStatusPermintaanRad\RefStatusPermintaanRadController::class,
                \App\Features\Radiologi\RefTemplateRad\RefTemplateRadController::class,
            ],
            'radiologi.svg',
        );
    }
}

