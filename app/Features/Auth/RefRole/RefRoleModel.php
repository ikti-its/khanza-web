<?php
declare(strict_types=1);

namespace App\Features\Auth\RefRole;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class RefRoleModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefRoleDatabase(),
            [
                'id_role'   => V::DEFAULT(),
                'nama_role' => V::DEFAULT(),
            ],
            [],
        );
    }
}
