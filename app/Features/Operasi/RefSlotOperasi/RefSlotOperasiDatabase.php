<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefSlotOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefSlotOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'ref_slot_operasi',
            [
                'id_slot'    => T::ID(48),
                'nama_slot'  => T::TEXT(),
                'waktu_slot' => T::TIME(),
            ],
            'id_slot',
            [],
            [],
            false,
            'slot_operasi.csv',
        );
    }
}
