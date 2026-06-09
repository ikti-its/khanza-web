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
                \App\Features\InventoriNonMedis\PermintaanBarangDetail\PermintaanBarangDetailController::class           => 'HIDE',
                \App\Features\InventoriNonMedis\RingkasanPermintaanBarang\RingkasanPermintaanBarangController::class,
                \App\Features\InventoriNonMedis\RingkasanPermintaanBarangDetail\RingkasanPermintaanBarangDetailController::class => 'HIDE',
                \App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangController::class,
                \App\Features\InventoriNonMedis\PengajuanBarangDetail\PengajuanBarangDetailController::class             => 'HIDE',
                \App\Features\InventoriNonMedis\RingkasanPengajuanBarang\RingkasanPengajuanBarangController::class,
                \App\Features\InventoriNonMedis\RingkasanPengajuanBarangDetail\RingkasanPengajuanBarangDetailController::class => 'HIDE',
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
                \App\Features\InventoriNonMedis\Unit\UnitController::class
            ],
            'inventaris_non_medis.svg',
        );
    }
}

