<?php
declare(strict_types=1);

namespace App\Features\RawatInap\StatusPulang;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class StatusPulangModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StatusPulangDatabase(),
            [
                'id_status_pulang'   => V::DEFAULT(),
                'nama_status_pulang' => V::DEFAULT(),
            ],
            [],
        );
    }
}
