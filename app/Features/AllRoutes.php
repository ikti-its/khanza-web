<?php
declare(strict_types=1);

namespace App\Features;

use App\Core\Route\RouteGroup;

final class AllRoutes extends RouteGroup
{
    public function __construct(){
        parent::__construct(
            [
                \App\Features\Lokasi\LokasiRoutes::class,
            ]
        );
    }
}