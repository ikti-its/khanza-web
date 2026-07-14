<?php

declare(strict_types=1);

namespace App\Features\Auth\RefGrupFitur;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefGrupFiturDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'auth',
            'ref_grup_fitur',
            [
                // Slug harus sama dengan hasil RouteGroup::create_path_from_name()
                // atas nama grup di *Routes.php (mis. 'Triase UGD' -> 'triase-ugd').
                'slug' => T::CODE(50),
                'nama_grup' => T::NAME(100),
            ],
            'slug',
            [
                'nama_grup',
            ],
            [],
            true,
            'ref_grup_fitur.csv',
        );
    }
}
