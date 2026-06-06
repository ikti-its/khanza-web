<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\StatusPermintaan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StatusPermintaanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPermintaanModel(),
            [
                ['Pelayanan Darah',   'pelayanan_darah'],
                ['Status Permintaan', 'status_permintaan'],
            ],
            'Status Permintaan',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_status_permintaan',   'ID Status Permintaan'],
                [SHOW, REQUIRED, I::TEXT,  'nama_status_permintaan', 'Nama Status Permintaan'],
            ],
        );
    }
}
