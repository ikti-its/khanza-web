<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StatusStok;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusStokController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusStokModel(),
            [
                ['Inventori Darah', 'inventori_darah'],
                ['Status Stok',     'status_stok'],
            ],
            'Status Stok',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_stok',   'ID Status Stok'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_stok', 'Nama Status Stok'],
            ],
        );
    }
}
