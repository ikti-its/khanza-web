<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\StatusPermintaan;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusPermintaanDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'pelayanan_darah',
            'status_permintaan',
            [
                'id_status_permintaan'   => T::ID(5),
                'nama_status_permintaan' => T::NAME(20),
            ],
            'id_status_permintaan',
            ['nama_status_permintaan'],
            [],
            true,
            'status_permintaan.csv',
        );
    }
}
