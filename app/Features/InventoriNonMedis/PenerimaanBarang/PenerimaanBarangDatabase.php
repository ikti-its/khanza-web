<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenerimaanBarangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'penerimaan_barang',
            [
                'id_penerimaan'              => T::ID(10_000),
                'id_pengadaan'               => T::FK_AUTO(),
                'tanggal'                    => T::DTIME(),
                'status'                     => T::RECORD(12),
                'catatan'                    => T::NOTE()->nullable(),
                'id_status_penerimaan_barang' => T::FK_AUTO(),
                'petugas'                    => T::FK_AUTO()->nullable(),
                'no_penerimaan'              => T::CODE(20)->nullable(),
                'no_masuk'                   => T::CODE(20)->nullable(),
            ],
            'id_penerimaan',
            [],
            [
                [
                    'id_pengadaan',
                    \App\Features\InventoriNonMedis\PengadaanBarang\PengadaanBarangDatabase::class,
                    'id_pengadaan',
                ],
                [
                    'id_status_penerimaan_barang',
                    \App\Features\InventoriNonMedis\Lookup\StatusPenerimaanBarang\StatusPenerimaanBarangDatabase::class,
                    'id_status_penerimaan_barang',
                ],
                [
                    'petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            true,
            'penerimaan_barang.csv',
        );
    }
}
