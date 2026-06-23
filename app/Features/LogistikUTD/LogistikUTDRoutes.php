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
                \App\Features\LogistikUTD\MedisDonor\MedisDonorController::class => 'HIDE',
                \App\Features\LogistikUTD\PenunjangDonor\PenunjangDonorController::class => 'HIDE',
                \App\Features\LogistikUTD\MedisPemisahan\MedisPemisahanController::class => 'HIDE',
                \App\Features\LogistikUTD\PenunjangPemisahan\PenunjangPemisahanController::class => 'HIDE',
                \App\Features\LogistikUTD\MedisPenyerahan\MedisPenyerahanController::class => 'HIDE',
                \App\Features\LogistikUTD\PenunjangPenyerahan\PenunjangPenyerahanController::class => 'HIDE',
                \App\Features\LogistikUTD\MedisRusak\MedisRusakController::class,
                \App\Features\LogistikUTD\MedisRusakDetail\MedisRusakDetailController::class => 'HIDE',
                \App\Features\LogistikUTD\PenunjangRusak\PenunjangRusakController::class,
                \App\Features\LogistikUTD\PenunjangRusakDetail\PenunjangRusakDetailController::class => 'HIDE',
                \App\Features\LogistikUTD\PengambilanMedis\PengambilanMedisController::class,
                \App\Features\LogistikUTD\PengambilanPenunjang\PengambilanPenunjangController::class,
            ],
            'logistik_utd.svg',
        );
    }
}
