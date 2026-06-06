<?php
declare(strict_types=1);

namespace App\Features\Laboratorium;

use App\Core\Route\RouteTemplate;

final class LaboratoriumRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Laboratorium',
            [
                \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderController::class,
                \App\Features\Laboratorium\PermintaanLabPk\PermintaanLabPkController::class,
                \App\Features\Laboratorium\PermintaanLabPa\PermintaanLabPaController::class,
                \App\Features\Laboratorium\PermintaanLabMb\PermintaanLabMbController::class,
                \App\Features\Laboratorium\HasilLabPk\HasilLabPkController::class,
                \App\Features\Laboratorium\HasilLabPa\HasilLabPaController::class,
                \App\Features\Laboratorium\HasilLabMb\HasilLabMbController::class,
                \App\Features\Laboratorium\RefItemPemeriksaanLab\RefItemPemeriksaanLabController::class,
                \App\Features\Laboratorium\RefKategoriLab\RefKategoriLabController::class,
                \App\Features\Laboratorium\RefKategoriUsiaLab\RefKategoriUsiaLabController::class,
                \App\Features\Laboratorium\RefParameterPemeriksaanLab\RefParameterPemeriksaanLabController::class,  
                \App\Features\Laboratorium\RefStatusPermintaan\RefStatusPermintaanController::class,
            ],
            'laboratorium.svg',
        );
    }
}

