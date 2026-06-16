<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPreOperasiPenunjang;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class ChecklistPreOperasiPenunjangDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'checklist_pre_operasi_penunjang',
            [
                'id_penunjang'       => T::ID(300_000_000),
                'id_checklist'       => T::FK_AUTO(),
                'id_jenis_penunjang' => T::FK_AUTO(),
                'id_ketersediaan'    => T::FK_AUTO(),
                'keterangan'         => T::NOTE()->nullable(),
            ],
            'id_penunjang',
            [],
            [
                [
                    ['id_checklist'],
                    \App\Features\Operasi\ChecklistPreOperasi\ChecklistPreOperasiDatabase::class,
                    ['id_checklist'],
                ],
                [
                    ['id_jenis_penunjang'],
                    \App\Features\Operasi\RefJenisPenunjang\RefJenisPenunjangDatabase::class,
                    ['id_jenis_penunjang'],
                ],
                [
                    ['id_ketersediaan'],
                    \App\Features\Operasi\RefKetersediaanStatus\RefKetersediaanStatusDatabase::class,
                    ['id_ketersediaan_status'],
                ],
            ],
        );
    }
}
