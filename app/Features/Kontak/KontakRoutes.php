<?php
declare(strict_types=1);

namespace App\Features\Kontak;

use App\Core\Route\RouteTemplate;

final class KontakRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Kontak',
            [
                \App\Features\Kontak\Email\EmailController::class,
                \App\Features\Kontak\JenisTelepon\JenisTeleponController::class,
                \App\Features\Kontak\Provider\ProviderController::class,
                \App\Features\Kontak\Telepon\TeleponController::class,
            ],
            'kontak.svg',
        );
    }
}
