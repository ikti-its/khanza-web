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
                \App\Features\TriaseUGD\DataTriaseDetail\DataTriaseDetailController::class     => 'HIDE',
                \App\Features\TriaseUGD\DataTriasePrimer\DataTriasePrimerController::class     => 'HIDE',
                \App\Features\TriaseUGD\DataTriaseSekunder\DataTriaseSekunderController::class => 'HIDE',
                \App\Features\TriaseUGD\TriaseMacamKasus\TriaseMacamKasusController::class,
                \App\Features\TriaseUGD\TriasePemeriksaan\TriasePemeriksaanController::class,
                \App\Features\TriaseUGD\TriaseSkala\TriaseSkalaController::class,
                \App\Features\TriaseUGD\CaraMasuk\CaraMasukController::class               => 'HIDE',
                \App\Features\TriaseUGD\AlatTransportasi\AlatTransportasiController::class => 'HIDE',
                \App\Features\TriaseUGD\AlasanKedatangan\AlasanKedatanganController::class => 'HIDE',
                \App\Features\TriaseUGD\KebutuhanKhusus\KebutuhanKhususController::class   => 'HIDE',
                \App\Features\TriaseUGD\PlanPrimer\PlanPrimerController::class             => 'HIDE',
                \App\Features\TriaseUGD\PlanSekunder\PlanSekunderController::class         => 'HIDE',
                \App\Features\TriaseUGD\TingkatSkala\TingkatSkalaController::class         => 'HIDE',
            ],
            'triase_ugd.svg',
        );
    }
}
