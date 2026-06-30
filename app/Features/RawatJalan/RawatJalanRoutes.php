<?php
declare(strict_types=1);

namespace App\Features\RawatJalan;

use App\Core\Route\RouteTemplate;

final class RawatJalanRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Rawat Jalan',
            [
                \App\Features\RawatJalan\PoliRawatJalan\PoliRawatJalanController::class,
            ],
            'rawat_jalan.svg',
        );
    }
}