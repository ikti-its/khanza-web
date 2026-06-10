<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpname;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StokOpnameDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'inventori_non_medis',
            'stok_opname',
            [
                'id_opname'             => T::ID(10_000_000),
                'tanggal'               => T::DTIME(),
                'id_status_stok_opname' => T::FK_AUTO(),
                'id_petugas'            => T::FK_AUTO(),
                'catatan'               => T::NOTE()->nullable(),
            ],
            'id_opname',
            [],
            [
                [
                    'id_status_stok_opname',
                    \App\Features\InventoriNonMedis\Lookup\StatusStokOpname\StatusStokOpnameDatabase::class,
                    'id_status_stok_opname',
                ],
                [
                    'id_petugas',
                    \App\Features\Role\Petugas\PetugasDatabase::class,
                    'id_petugas',
                ],
            ],
            true,
            'stok_opname.csv',
        );
    }
}
