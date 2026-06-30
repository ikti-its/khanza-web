<?php
declare(strict_types=1);

namespace App\Features\Radiologi\PermintaanRadItem;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanRadItemDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'radiologi',
            'permintaan_rad_item',
            [
                'id_permintaan_item' => T::ID(100_000_000),
                'id_permintaan'      => T::FK_AUTO(),
                'id_item'            => T::FK_AUTO(),
                'is_baca_saja'       => T::BOOL(),
            ],
            'id_permintaan_item',
            [],
            [
                [
                    ['id_permintaan'],
                    \App\Features\Radiologi\PermintaanRad\PermintaanRadDatabase::class,
                    ['id_permintaan'],
                ],
                [
                    ['id_item'],
                    \App\Features\Radiologi\RefItemRad\RefItemRadDatabase::class,
                    ['id_item'],
                ],
            ],
        );
    }
}