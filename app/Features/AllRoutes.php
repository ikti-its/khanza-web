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
                \App\Features\Darah\DarahRoutes::class,
                \App\Features\Donor\DonorRoutes::class,
                \App\Features\InventoriDarah\InventoriDarahRoutes::class,
                \App\Features\UjiDarah\UjiDarahRoutes::class,
                \App\Features\DistribusiDarah\DistribusiDarahRoutes::class,
                \App\Features\LogistikUTD\LogistikUTDRoutes::class,
                \App\Features\SkriningRawatJalan\SkriningRawatJalanRoutes::class,
                \App\Features\Operasi\OperasiRoutes::class,
                \App\Features\Radiologi\RadiologiRoutes::class,
                \App\Features\Laboratorium\LaboratoriumRoutes::class,
                \App\Features\Lokasi\LokasiRoutes::class,
            ]
        );
    }
}