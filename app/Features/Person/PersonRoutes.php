<?php
declare(strict_types=1);

namespace App\Features\Person;

use App\Core\Route\RouteTemplate;

final class PersonRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Person',
            [
                \App\Features\Person\Agama\AgamaController::class,
                \App\Features\Person\JenisKelamin\JenisKelaminController::class,
                \App\Features\Person\Kewarganegaraan\KewarganegaraanController::class,
                \App\Features\Person\Orang\OrangController::class,
                \App\Features\Person\Pernikahan\PernikahanController::class,
            ],
            'person.svg',
        );
    }
}
