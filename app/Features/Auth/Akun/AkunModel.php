<?php
declare(strict_types=1);

namespace App\Features\Auth\Akun;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class AkunModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AkunDatabase(),
            [
                'id'       => V::DEFAULT(),
                'email'    => V::DEFAULT(),
                'password' => V::DEFAULT(),
                'role'     => V::DEFAULT(),
            ],
            [],
        );
    }
}
