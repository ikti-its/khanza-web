<?php
declare(strict_types=1);

namespace App\Features\LogistikUTD\MedisPenyerahan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class MedisPenyerahanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'logistik_utd',
            'medis_penyerahan',
            [
                'id_medis_penyerahan' => T::ID(100_000_000),
                'id_penyerahan'       => T::FK_AUTO(),
                'id_barang'           => T::FK_AUTO(),
                'jumlah'              => T::QTY(1, 999),
                'harga'               => T::MONEY(),
            ],
            'id_medis_penyerahan',
            [],
            [
                [
                    'id_penyerahan',
                    \App\Features\PelayananDarah\PenyerahanDarah\PenyerahanDarahDatabase::class,
                    'id_penyerahan',
                ],
                [
                    'id_barang',
                    \App\Features\InventoriMedis\DataBarang\DataBarangDatabase::class,
                    'id_barang',
                ],
            ],
        );
    }
}
