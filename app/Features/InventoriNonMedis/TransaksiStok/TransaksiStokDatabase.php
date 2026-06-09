<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStok;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class TransaksiStokDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'transaksi_stok',
            [
                'id_transaksi'           => T::ID(1_000_000),
                'id_tipe_transaksi_stok' => T::FK_AUTO(),
                'tanggal'                => T::DTIME(),
                'id_penerimaan'          => T::FK_AUTO()->nullable(),
                'id_permintaan'          => T::FK_AUTO()->nullable(),
                'id_opname'              => T::FK_AUTO()->nullable(),
                'keterangan'             => T::NOTE()->nullable(),
            ],
            'id_transaksi',
            [],
            [
                [
                    'id_tipe_transaksi_stok',
                    \App\Features\InventoriNonMedis\Lookup\TipeTransaksiStok\TipeTransaksiStokDatabase::class,
                    'id_tipe_transaksi_stok',
                ],
                [
                    'id_penerimaan',
                    \App\Features\InventoriNonMedis\PenerimaanBarang\PenerimaanBarangDatabase::class,
                    'id_penerimaan',
                ],
                [
                    'id_permintaan',
                    \App\Features\InventoriNonMedis\PermintaanBarang\PermintaanBarangDatabase::class,
                    'id_permintaan',
                ],
                [
                    'id_opname',
                    \App\Features\InventoriNonMedis\StokOpname\StokOpnameDatabase::class,
                    'id_opname',
                ],
            ],
            true,
            'transaksi_stok.csv',
        );
    }
}
