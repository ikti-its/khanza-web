<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD;

use App\Core\Route\RouteTemplate;

final class LogistikUTDRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Logistik UTD',
            [
                \App\Features\LogistikUTD\MedisDonor\MedisDonorController::class,
                \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorController::class,
                \App\Features\LogistikUTD\MedisPemisahan\MedisPemisahanController::class,
                \App\Features\LogistikUTD\PenunjangPemisahan\PenunjangPemisahanController::class,
                \App\Features\LogistikUTD\MedisPenyerahan\MedisPenyerahanController::class,
                \App\Features\LogistikUTD\PenunjangPenyerahan\PenunjangPenyerahanController::class,
                \App\Features\LogistikUTD\MedisRusak\MedisRusakController::class,
                \App\Features\LogistikUTD\PenunjangRusak\PenunjangRusakController::class,
                \App\Features\LogistikUTD\PengambilanMedis\PengambilanMedisController::class,
                \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangController::class,
            ],
            'logistik_utd.svg',
        );
    }
}
