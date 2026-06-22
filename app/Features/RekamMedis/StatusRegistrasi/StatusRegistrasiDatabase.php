<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\StatusRegistrasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class StatusRegistrasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'rekam_medis',
            'status_registrasi',
            [
                'id_status_registrasi'   => T::ID(5),
                'nama_status_registrasi' => T::NAME(20),
            ],
            'id_status_registrasi',
            [],
            [],
            true,
            'status_registrasi.csv',
        );
    }
}
