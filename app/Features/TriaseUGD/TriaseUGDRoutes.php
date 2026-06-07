<?php
declare(strict_types=1);

namespace App\Features\TriaseUGD;

use App\Core\Route\RouteTemplate;

final class TriaseUGDRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Triase UGD',
            [
                \App\Features\TriaseUGD\DataTriase\DataTriaseController::class,
                \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailController::class,
                \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerController::class,
                \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderController::class,
                \App\Features\TriaseUGD\TriaseMacamKasus\TriaseMacamKasusController::class,
                \App\Features\TriaseUGD\TriasePemeriksaan\TriasePemeriksaanController::class,
                \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaController::class,
                \App\Features\TriaseUGD\CaraMasuk\CaraMasukController::class,
                \App\Features\TriaseUGD\AlatTransportasi\AlatTransportasiController::class,
                \App\Features\TriaseUGD\AlasanKedatangan\AlasanKedatanganController::class,
                \App\Features\TriaseUGD\KebutuhanKhusus\KebutuhanKhususController::class,
                \App\Features\TriaseUGD\PlanPrimer\PlanPrimerController::class,
                \App\Features\TriaseUGD\PlanSekunder\PlanSekunderController::class,
                \App\Features\TriaseUGD\TingkatSkala\TingkatSkalaController::class,
            ],
            'triase_ugd.svg',
        );
    }
}
