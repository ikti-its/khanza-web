<?php
declare(strict_types=1);

namespace App\Features\Auth\RefRole;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefRoleDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'auth',
            'ref_role',
            [
                'id_role'   => T::ID(6000),
                'nama_role' => T::NAME(50),
            ],
            'id_role',
            [
                'nama_role',
            ],
            [],
            true,
            'ref_role.csv',
        );
    }
}
