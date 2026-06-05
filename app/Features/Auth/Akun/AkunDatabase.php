<?php
declare(strict_types=1);

namespace App\Features\Auth\Akun;
use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class AkunDatabase extends DatabaseTemplate
{
    public function __construct(){
        parent::__construct(
            'auth',
            'akun',
            [
                'id'       => T::ID(1_000_000),
                'email'    => T::TEXT(),
                'password' => T::TEXT(),
                'role'     => T::QTY(1,6000),
            ],
            'id',
            [
                'email'
            ],
            [],
            true,
            'auth.csv'
        );
    }
}
