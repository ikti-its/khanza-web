<?php
declare(strict_types=1);

namespace App\Features\PenangananDonor;

use App\Core\Route\RouteTemplate;

final class PenangananDonorRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Penanganan Donor',
            [
                \App\Features\PenangananDonor\Pencekalan\PencekalanController::class,
                \App\Features\PenangananDonor\KasusReaktif\KasusReaktifController::class,
                \App\Features\PenangananDonor\JenisPencekalan\JenisPencekalanController::class => 'HIDE',
                \App\Features\PenangananDonor\StatusKasus\StatusKasusController::class => 'HIDE',
            ],
            'penanganan_donor.svg',
        );
    }
}
