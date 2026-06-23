<?php
declare(strict_types=1);

namespace App\Features\Donor;

use App\Core\Route\RouteTemplate;

final class DonorRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Donor',
            [
                \App\Features\Donor\Kunjungan\KunjunganController::class,
                \App\Features\Donor\SkriningDonor\SkriningDonorController::class,
                \App\Features\Donor\PengambilanDarah\PengambilanDarahController::class,
                \App\Features\Donor\HasilAnamnesis\HasilAnamnesisController::class => 'HIDE',
                \App\Features\Donor\StatusSkrining\StatusSkriningController::class => 'HIDE',
                \App\Features\Donor\JenisBag\JenisBagController::class => 'HIDE',
                \App\Features\Donor\JenisDonor\JenisDonorController::class => 'HIDE',
                \App\Features\Donor\LokasiPengambilanDarah\LokasiPengambilanDarahController::class => 'HIDE',
                \App\Features\Donor\StatusPengambilan\StatusPengambilanController::class => 'HIDE',
            ],
            'donor.svg',
        );
    }
}
