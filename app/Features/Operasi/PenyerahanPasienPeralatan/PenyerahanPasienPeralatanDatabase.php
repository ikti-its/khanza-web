<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasienPeralatan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PenyerahanPasienPeralatanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'penyerahan_pasien_peralatan',
            [
                'id'            => T::ID(300_000_000),
                'id_penyerahan' => T::FK_AUTO(),
                'id_peralatan'  => T::FK_AUTO(),
                'keterangan'    => T::NOTE()->nullable(),
            ],
            'id',
            [],
            [
                [
                    ['id_penyerahan'],
                    \App\Features\Operasi\PenyerahanPasien\PenyerahanPasienDatabase::class,
                    ['id_penyerahan'],
                ],
                [
                    ['id_peralatan'],
                    \App\Features\Operasi\RefPeralatanTransfer\RefPeralatanTransferDatabase::class,
                    ['id_peralatan'],
                ],
            ],
        );
    }
}
