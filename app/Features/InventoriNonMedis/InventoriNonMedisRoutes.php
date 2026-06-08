<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis;

use App\Core\Route\RouteTemplate;

final class InventoriNonMedisRoutes extends RouteTemplate
{
    public function __construct()
    {
        parent::__construct(
            'Inventori Non-Medis',
            [
                \App\Features\InventoriNonMedis\PermintaanBarang\PermintaanBarangController::class,
                \App\Features\InventoriNonMedis\PermintaanBarangDetail\PermintaanBarangDetailController::class,
                \App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangController::class,
                \App\Features\InventoriNonMedis\PengajuanBarangDetail\PengajuanBarangDetailController::class,
                \App\Features\InventoriNonMedis\PengadaanBarang\PengadaanBarangController::class,
                \App\Features\InventoriNonMedis\PengadaanBarangDetail\PengadaanBarangDetailController::class,
                \App\Features\InventoriNonMedis\PenerimaanBarang\PenerimaanBarangController::class,
                \App\Features\InventoriNonMedis\PenerimaanBarangDetail\PenerimaanBarangDetailController::class,
                \App\Features\InventoriNonMedis\StokOpname\StokOpnameController::class,
                \App\Features\InventoriNonMedis\StokOpnameDetail\StokOpnameDetailController::class,
                \App\Features\InventoriNonMedis\TransaksiStok\TransaksiStokController::class,
                \App\Features\InventoriNonMedis\Barang\BarangController::class,
                \App\Features\InventoriNonMedis\JenisBarang\JenisBarangController::class,
                \App\Features\InventoriNonMedis\Suplier\SuplierController::class,
                \App\Features\InventoriNonMedis\Satuan\SatuanController::class,
                \App\Features\InventoriNonMedis\Unit\UnitController::class
            ],
            'inventaris_non_medis.svg',
        );
    }
}

