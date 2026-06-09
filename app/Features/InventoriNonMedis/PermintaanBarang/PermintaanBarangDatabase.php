<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanBarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'permintaan_barang',
            [
                'id_permintaan'               => T::ID(100_000),
                'no_permintaan'               => T::CODE(20),
                'tanggal'                     => T::DTIME(),
                'petugas'                     => T::FK_AUTO(),
                'master_ruangan'              => T::FK_AUTO(),
                'id_status_permintaan_barang' => T::FK_AUTO(),
                'no_keluar'                   => T::CODE(20)->nullable(),
                'petugas_gudang'              => T::FK_AUTO()->nullable(),
                'tanggal_disetujui'           => T::DTIME()->nullable(),
            ],
            'id_permintaan',
            [],
            [
                [
                    'petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
                [
                    'master_ruangan',
                    \App\Features\Ruangan\RuanganDatabase::class,
                    'id_ruangan',
                ],
                [
                    'id_status_permintaan_barang',
                    \App\Features\InventoriNonMedis\Lookup\StatusPermintaanBarang\StatusPermintaanBarangDatabase::class,
                    'id_status_permintaan_barang',
                ],
                [
                    'petugas_gudang',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            true,
            'permintaan_barang.csv',
        );
    }
}
