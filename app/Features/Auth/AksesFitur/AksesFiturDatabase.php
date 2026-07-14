<?php

declare(strict_types=1);

namespace App\Features\Auth\AksesFitur;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class AksesFiturDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'auth',
            'akses_fitur',
            [
                'id' => T::ID(10_000),
                'id_role' => T::FK_AUTO(),
                'feature_group' => T::FK_AUTO(),
                'boleh_baca' => T::BOOL(),
                'boleh_tulis' => T::BOOL(),
            ],
            'id',
            [
                ['id_role', 'feature_group'],
            ],
            [
                [
                    'id_role',
                    \App\Features\Auth\RefRole\RefRoleDatabase::class,
                    'id_role',
                ],
                [
                    'feature_group',
                    \App\Features\Auth\RefGrupFitur\RefGrupFiturDatabase::class,
                    'slug',
                ],
            ],
            true,
            'akses_fitur.csv',
        );
    }
}
