<?php
declare(strict_types=1);

namespace App\Features\Registrasi\StatusRawat;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusRawatDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'registrasi',
            'status_rawat',
            [
                'id_status_rawat'   => T::ID(5),
                'nama_status_rawat' => T::NAME(20),
            ],
            'id_status_rawat',
            [],
            [],
            true,
            'status_rawat.csv',
        );
    }
}
