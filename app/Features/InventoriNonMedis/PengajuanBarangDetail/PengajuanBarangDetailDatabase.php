<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarangDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengajuanBarangDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'pengajuan_barang_detail',
            [
                'id_detail'        => T::ID(500_000),
                'id_pengajuan'     => T::FK_AUTO(),
                'id_barang'        => T::FK_AUTO()->nullable(),
                'nama_barang_baru' => T::NAME(100)->nullable(),
                'qty'              => T::QTY(0, 100_000),
                'qty_disetujui'    => T::QTY(0, 100_000)->nullable(),
                'harga'            => T::MONEY()->nullable(),
                'subtotal'         => T::MONEY()->nullable(),
            ],
            'id_detail',
            [],
            [
                [
                    'id_pengajuan',
                    \App\Features\InventoriNonMedis\PengajuanBarang\PengajuanBarangDatabase::class,
                    'id_pengajuan',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    'id_barang',
                ],
            ],
            true,
            'pengajuan_barang_detail.csv',
        );
    }
}
