<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PengajuanBarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'pengajuan_barang',
            [
                'id_pengajuan'               => T::ID(100_000),
                'no_pengajuan'               => T::CODE(20)->nullable(),
                'id_permintaan'              => T::FK_AUTO()->nullable(),
                'tanggal'                    => T::DTIME(),
                'petugas_gudang'             => T::FK_AUTO(),
                'id_status_pengajuan_barang' => T::FK_AUTO(),
                'atasan_logistik'            => T::FK_AUTO()->nullable(),
                'tanggal_diproses'           => T::DTIME()->nullable(),
                'total_harga'               => T::MONEY()->nullable(),
            ],
            'id_pengajuan',
            [],
            [
                [
                    'id_permintaan',
                    \App\Features\InventoriNonMedis\PermintaanBarang\PermintaanBarangDatabase::class,
                    'id_permintaan',
                ],
                [
                    'petugas_gudang',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'id_status_pengajuan_barang',
                    \App\Features\InventoriNonMedis\Lookup\StatusPengajuanBarang\StatusPengajuanBarangDatabase::class,
                    'id_status_pengajuan_barang',
                ],
                [
                    'atasan_logistik',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            true,
            'pengajuan_barang.csv',
        );
    }
}
