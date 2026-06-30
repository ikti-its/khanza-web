<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusPoli;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusPoliDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'registrasi',
            'status_poli',
            [
                'id_status_poli'   => T::ID(5),
                'nama_status_poli' => T::NAME(20),
            ],
            'id_status_poli',
            [],
            [],
            true,
            'status_poli.csv',
        );
    }
}
