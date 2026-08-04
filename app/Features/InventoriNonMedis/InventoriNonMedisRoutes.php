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
                \App\Features\InventoriNonMedis\PermintaanBarangDetail\PermintaanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PersetujuanPermintaanBarang\PersetujuanPermintaanBarangController::class,
                \App\Features\InventoriNonMedis\PersetujuanPermintaanBarangDetail\PersetujuanPermintaanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangController::class,
                \App\Features\InventoriNonMedis\PengajuanBarangDetail\PengajuanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PersetujuanPengajuanBarang\PersetujuanPengajuanBarangController::class,
                \App\Features\InventoriNonMedis\PersetujuanPengajuanBarangDetail\PersetujuanPengajuanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PengadaanBarang\PengadaanBarangController::class,
                \App\Features\InventoriNonMedis\PengadaanBarangDetail\PengadaanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PenerimaanBarang\PenerimaanBarangController::class,
                \App\Features\InventoriNonMedis\PenerimaanBarangDetail\PenerimaanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\StokOpname\StokOpnameController::class,
                \App\Features\InventoriNonMedis\StokOpnameDetail\StokOpnameDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\TransaksiStok\TransaksiStokController::class,
                \App\Features\InventoriNonMedis\TransaksiStokDetail\TransaksiStokDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\Barang\BarangController::class,
                \App\Features\InventoriNonMedis\JenisBarang\JenisBarangController::class,
                \App\Features\InventoriNonMedis\Suplier\SuplierController::class,
                \App\Features\InventoriNonMedis\Satuan\SatuanController::class,
            ],
            'inventaris_non_medis.svg',
        );
    }
}
