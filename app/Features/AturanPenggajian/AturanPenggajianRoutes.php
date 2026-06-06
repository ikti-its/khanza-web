<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian;

use App\Core\Route\RouteTemplate;

final class AturanPenggajianRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Aturan Penggajian',
            [
                \App\Features\AturanPenggajian\Golongan\GolonganController::class,
                \App\Features\AturanPenggajian\Jabatan\JabatanController::class,
                \App\Features\AturanPenggajian\UpahMinimumProvinsi\UpahMinimumProvinsiController::class,
                \App\Features\AturanPenggajian\UpahMinimumKotakab\UpahMinimumKotakabController::class,
                \App\Features\AturanPenggajian\BPJS\BPJSController::class,
                \App\Features\AturanPenggajian\Lembur\LemburController::class,
                \App\Features\AturanPenggajian\PajakPenghasilan\PajakPenghasilanController::class,
                \App\Features\AturanPenggajian\PenghasilanTidakKenaPajak\PenghasilanTidakKenaPajakController::class,
                \App\Features\AturanPenggajian\TunjanganHariRaya\TunjanganHariRayaController::class,
                \App\Features\AturanPenggajian\Pesangon\PesangonController::class,
                \App\Features\AturanPenggajian\UangPenghargaanMasaKerja\UangPenghargaanMasaKerjaController::class,
            ],
            'aturan_penggajian.svg',
        );
    }
}
