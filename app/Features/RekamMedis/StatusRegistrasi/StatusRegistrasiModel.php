<?php
declare(strict_types=1);

namespace App\Features\RekamMedis\StatusRegistrasi;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusRegistrasiModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusRegistrasiDatabase(),
            [
                'id_status_registrasi'   => V::DEFAULT(),
                'nama_status_registrasi' => V::DEFAULT(),
            ],
            [],
        );
    }
}
