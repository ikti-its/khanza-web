<?php
declare(strict_types=1);

namespace App\Features\RawatInap;

use App\Core\Route\RouteTemplate;

final class RawatInapRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Rawat Inap',
            [
                \App\Features\RawatInap\Registrasi\RegistrasiController::class,
            ],
            'rawat_inap.svg',
        );
    }
}
