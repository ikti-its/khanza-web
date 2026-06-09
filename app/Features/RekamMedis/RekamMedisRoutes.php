<?php
declare(strict_types=1);

namespace App\Features\RekamMedis;

use App\Core\Route\RouteTemplate;

final class RekamMedisRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Rekam Medis',
            [
                \App\Features\RekamMedis\Registrasi\RegistrasiController::class,
            ],
            'rekam_medis.svg',
        );
    }
}

