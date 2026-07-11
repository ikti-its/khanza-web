<?php
declare(strict_types=1);

namespace App\Features\Registrasi;

use App\Core\Route\RouteTemplate;

final class RegistrasiRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Registrasi',
            [
                \App\Features\Registrasi\Registrasi\RegistrasiController::class,
            ],
            'registrasi.svg',
        );
    }
}
