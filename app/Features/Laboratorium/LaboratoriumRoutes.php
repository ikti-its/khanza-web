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
                \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderController::class => 'HIDE',
                \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemController::class,
                \App\Features\Laboratorium\PermintaanLabPkParameter\PermintaanLabPkParameterController::class => 'HIDE',
                \App\Features\Laboratorium\PermintaanLabPa\PermintaanLabPaController::class,
                \App\Features\Laboratorium\PermintaanLabPaItem\PermintaanLabPaItemController::class => 'HIDE',
                \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemController::class,
                \App\Features\Laboratorium\PermintaanLabMbParameter\PermintaanLabMbParameterController::class => 'HIDE',
                \App\Features\Laboratorium\HasilLabPk\HasilLabPkController::class,
                \App\Features\Laboratorium\HasilLabPa\HasilLabPaController::class,
                \App\Features\Laboratorium\HasilLabMb\HasilLabMbController::class,
                \App\Features\Laboratorium\RefItemPemeriksaanLab\RefItemPemeriksaanLabController::class,
                \App\Features\Laboratorium\RefParameterPemeriksaanLab\RefParameterPemeriksaanLabController::class,  
                \App\Features\Laboratorium\RefKategoriLab\RefKategoriLabController::class,
                \App\Features\Laboratorium\RefKategoriUsiaLab\RefKategoriUsiaLabController::class,
                \App\Features\Laboratorium\RefStatusPermintaan\RefStatusPermintaanController::class,
            ],
            'laboratorium.svg',
        );
    }
}

