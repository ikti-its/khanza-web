<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasiObat;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class TagihanOperasiObatDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'tagihan_operasi_obat',
            [
                'id_detail'  => T::ID(300_000_000),
                'id_tagihan' => T::FK_AUTO(),
                'id_barang'  => T::FK_AUTO(),
                'jumlah'     => T::QTY(1, 10_000),
            ],
            'id_detail',
            [],
            [
                [
                    ['id_tagihan'],
                    \App\Features\Operasi\TagihanOperasi\TagihanOperasiDatabase::class,
                    ['id_tagihan'],
                ],
                [
                    ['id_barang'],
                    \App\Features\InventoriMedis\DataBarang\DataBarangDatabase::class,
                    ['id_barang'],
                ],
            ],
        );
    }
}
