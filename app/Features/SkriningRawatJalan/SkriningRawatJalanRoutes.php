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
                \App\Features\SkriningRawatJalan\RefSkriningBatuk\RefSkriningBatukController::class           => 'HIDE',
                \App\Features\SkriningRawatJalan\RefSkriningKeputusan\RefSkriningKeputusanController::class   => 'HIDE',
                \App\Features\SkriningRawatJalan\RefSkriningKesadaran\RefSkriningKesadaranController::class   => 'HIDE',
                \App\Features\SkriningRawatJalan\RefSkriningNyeriDada\RefSkriningNyeriDadaController::class   => 'HIDE',
                \App\Features\SkriningRawatJalan\RefSkriningPernafasan\RefSkriningPernafasanController::class => 'HIDE',
                \App\Features\SkriningRawatJalan\RefSkriningSkalaNyeri\RefSkriningSkalaNyeriController::class => 'HIDE',
            ],
            'skrining_rawat_jalan.svg',
        );
    }
}
