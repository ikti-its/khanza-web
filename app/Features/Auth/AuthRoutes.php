<?php

declare(strict_types=1);

namespace App\Features\Auth;

use App\Core\Route\RouteTemplate;

final class AuthRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Auth',
            [
                \App\Features\Auth\Akun\AkunController::class,
                \App\Features\Auth\RefRole\RefRoleController::class => 'HIDE',
            ],
            'auth.svg',
        );
    }
}
