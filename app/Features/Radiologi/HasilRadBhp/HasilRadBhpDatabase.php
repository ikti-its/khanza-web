<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadBhp;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilRadBhpDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'radiologi',
            'hasil_rad_bhp',
            [
                'id_rad_bhp'      => T::ID(100_000_000),
                'id_hasil_rad'    => T::FK_AUTO(),
                'id_barang'       => T::FK_AUTO(),
                'jumlah_pakai'    => T::QTY(0, 1_000_000)->nullable(),
            ],
            'id_rad_bhp',
            [],
            [
                [
                    ['id_hasil_rad'],
                    \App\Features\Radiologi\HasilRad\HasilRadDatabase::class,
                    ['id_hasil_rad'],
                ],
                [
                    ['id_barang'],
                    \App\Features\InventoriNonMedis\Barang\BarangDatabase::class,
                    ['id_barang'],
                ],
            ],
        );
    }
}
