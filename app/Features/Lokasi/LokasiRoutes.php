<?php
declare(strict_types=1);

namespace App\Features\Lokasi;

use App\Core\Route\RouteTemplate;

final class LokasiRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Lokasi',
            [
                \App\Features\Lokasi\Negara\NegaraController::class,
                \App\Features\Lokasi\Pulau\PulauController::class,
                \App\Features\Lokasi\Provinsi\ProvinsiController::class,
                \App\Features\Lokasi\Kota\KotaController::class,
                \App\Features\Lokasi\Kecamatan\KecamatanController::class,
                \App\Features\Lokasi\Desa\DesaController::class,
                \App\Features\Lokasi\Alamat\AlamatController::class,
            ],
            'lokasi.svg',
        );
    }
}
