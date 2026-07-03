<?php
declare(strict_types=1);

namespace App\Features\Operasi\JadwalOperasiSlot;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class JadwalOperasiSlotModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JadwalOperasiSlotDatabase(),
            [
                'id_jadwal_slot' => V::DEFAULT(),
            ],
            [
                'id_jadwal' => [],
                'id_slot'   => ['nama_slot', 'waktu_slot'],
            ],
        );
    }
}