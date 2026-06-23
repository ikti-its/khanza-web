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
                \App\Features\Radiologi\PermintaanRadItem\PermintaanRadItemController::class => 'HIDE',
                \App\Features\Radiologi\HasilRad\HasilRadController::class,
                \App\Features\Radiologi\HasilRadTindakan\HasilRadTindakanController::class => 'HIDE',
                \App\Features\Radiologi\HasilRadBhp\HasilRadBhpController::class => 'HIDE',
                \App\Features\Radiologi\HasilRadFoto\HasilRadFotoController::class => 'HIDE',
                \App\Features\Radiologi\RefItemRad\RefItemRadController::class => 'HIDE',
                \App\Features\Radiologi\RefStatusPermintaanRad\RefStatusPermintaanRadController::class => 'HIDE',
                \App\Features\Radiologi\RefTemplateRad\RefTemplateRadController::class => 'HIDE',
            ],
            'radiologi.svg',
        );
    }
}

