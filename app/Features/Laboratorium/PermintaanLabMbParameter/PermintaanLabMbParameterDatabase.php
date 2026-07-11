<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMbParameter;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanLabMbParameterDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'permintaan_lab_mb_parameter',
            [
                'id_mb_parameter'       => T::ID(100_000),
                'id_permintaan_mb_item' => T::FK_AUTO(),
                'id_parameter'          => T::FK_AUTO(),
            ],
            'id_mb_parameter',
            [],
            [
                [
                    ['id_permintaan_mb_item'],
                    \App\Features\Laboratorium\PermintaanLabMbItem\PermintaanLabMbItemDatabase::class,
                    ['id_permintaan_mb_item'],
                ],
                [
                    ['id_parameter'],
                    \App\Features\Laboratorium\RefParameterPemeriksaanLab\RefParameterPemeriksaanLabDatabase::class,
                    ['id_parameter'],
                ],
            ],
        );
    }
}
