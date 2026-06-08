<?php
declare(strict_types=1);

namespace App\Features\Pendidikan;

use App\Core\Route\RouteTemplate;

final class PendidikanRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Pendidikan',
            [
                \App\Features\Pendidikan\Gelar\GelarController::class,
                \App\Features\Pendidikan\JenisPendidikan\JenisPendidikanController::class,
                \App\Features\Pendidikan\JenjangPendidikan\JenjangPendidikanController::class,
                \App\Features\Pendidikan\Sekolah\SekolahController::class,
            ],
            'pendidikan.svg',
        );
    }
}
