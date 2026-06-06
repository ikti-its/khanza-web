<?php
declare(strict_types=1);

namespace App\Features;

use App\Core\Route\RouteGroup;

final class AllRoutes extends RouteGroup
{
    public function __construct(){
        parent::__construct(
            [
                \App\Features\AturanPenggajian\AturanPenggajianRoutes::class,
                \App\Features\Finansial\FinansialRoutes::class,
                \App\Features\InventoriNonMedis\InventoriNonMedisRoutes::class,
                \App\Features\SkriningRawatJalan\SkriningRawatJalanRoutes::class,
                \App\Features\Radiologi\RadiologiRoutes::class,
                \App\Features\Lokasi\LokasiRoutes::class,
            ]
        );
    }
}