<?php
declare(strict_types=1);

namespace App\Features\SkriningRawatJalan;

use App\Core\Route\RouteTemplate;

final class SkriningRawatJalanRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Skrining Rawat Jalan',
            [
                \App\Features\SkriningRawatJalan\SkriningRawatJalan\SkriningRawatJalanController::class,
                \App\Features\SkriningRawatJalan\RefSkriningBatuk\RefSkriningBatukController::class,
                \App\Features\SkriningRawatJalan\RefSkriningKeputusan\RefSkriningKeputusanController::class,
                \App\Features\SkriningRawatJalan\RefSkriningKesadaran\RefSkriningKesadaranController::class,
                \App\Features\SkriningRawatJalan\RefSkriningNyeriDada\RefSkriningNyeriDadaController::class,
                \App\Features\SkriningRawatJalan\RefSkriningPernafasan\RefSkriningPernafasanController::class,
                \App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri\RefSkriningSkalaNyeriController::class,
            ],
            'skrining_rawat_jalan.svg',
        );
    }
}

