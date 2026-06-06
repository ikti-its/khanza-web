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
                \App\Features\Donor\HasilAnamnesis\HasilAnamnesisController::class,
                \App\Features\Donor\JenisBag\JenisBagController::class,
                \App\Features\Donor\JenisDonor\JenisDonorController::class,
                \App\Features\Donor\LokasiPengambilanDarah\LokasiPengambilanDarahController::class,
                \App\Features\Donor\StatusPengambilan\StatusPengambilanController::class,
            ],
            'donor.svg',
        );
    }
}
