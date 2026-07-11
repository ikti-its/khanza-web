<?php
declare(strict_types=1);

namespace App\Features\Operasi\TagihanOperasiTindakan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class TagihanOperasiTindakanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'tagihan_operasi_tindakan',
            [
                'id_tagihan_tindakan' => T::ID(300_000_000),
                'id_tagihan'          => T::FK_AUTO(),
                'id_paket'            => T::FK_AUTO(),
            ],
            'id_tagihan_tindakan',
            [],
            [
                [
                    ['id_tagihan'],
                    \App\Features\Operasi\TagihanOperasi\TagihanOperasiDatabase::class,
                    ['id_tagihan'],
                ],
                [
                    ['id_paket'],
                    \App\Features\Operasi\PaketTindakanOperasi\PaketTindakanOperasiDatabase::class,
                    ['id_paket'],
                ],
            ],
        );
    }
}
