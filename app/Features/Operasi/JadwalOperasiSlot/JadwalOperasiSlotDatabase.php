<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasiSlot;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class JadwalOperasiSlotDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'jadwal_operasi_slot',
            [
                'id_jadwal_slot' => T::ID(100_000_000),
                'id_jadwal'      => T::FK_AUTO(),
                'id_slot'        => T::FK_AUTO(),
            ],
            'id_jadwal_slot',
            [],
            [
                [
                    ['id_jadwal'],
                    \App\Features\Operasi\JadwalOperasi\JadwalOperasiDatabase::class,
                    ['id_jadwal'],
                ],
                [
                    ['id_slot'],
                    \App\Features\Operasi\RefSlotOperasi\RefSlotOperasiDatabase::class,
                    ['id_slot'],
                ],
            ],
        );
    }
}