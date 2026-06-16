<?php
declare(strict_types=1);

namespace App\Features\UGD;

use App\Core\Route\RouteTemplate;

final class UGDRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'UGD',
            [
                \App\Features\UGD\Registrasi\RegistrasiController::class,
            ],
            'ugd.svg',
        );
    }
}
