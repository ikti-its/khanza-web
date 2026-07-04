<?php
declare(strict_types=1);

namespace App\Features\Role;

use App\Core\Route\RouteTemplate;

final class RoleRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Role',
            [
                \App\Features\Role\Dokter\DokterController::class,
                \App\Features\Role\Pasien\PasienController::class,
                \App\Features\Role\Pendonor\PendonorController::class,
                \App\Features\Role\RiwayatTanggalDonor\RiwayatTanggalDonorController::class => 'HIDE',
                \App\Features\Role\Petugas\PetugasController::class,
            ],
            'role.svg',
        );
    }
}
