<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabMb;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanLabMbDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'permintaan_lab_mb',
            [
                'id_permintaan_mb'         => T::ID(100_000),
                'id_permintaan_lab'        => T::FK_AUTO(),
                'id_item_pemeriksaan'      => T::FK_AUTO(),
                'id_parameter_pemeriksaan' => T::FK_AUTO(),
            ],
            'id_permintaan_mb',
            [],
            [
                [
                    ['id_permintaan_lab'],
                    \App\Features\Laboratorium\PermintaanLabHeader\PermintaanLabHeaderDatabase::class,
                    ['id_permintaan'],
                ],
                [
                    ['id_item_pemeriksaan'],
                    \App\Features\Laboratorium\RefItemPemeriksaanLab\RefItemPemeriksaanLabDatabase::class,
                    ['id_item_lab'],
                ],
                [
                    ['id_parameter_pemeriksaan'],
                    \App\Features\Laboratorium\RefParameterPemeriksaanLab\RefParameterPemeriksaanLabDatabase::class,
                    ['id_parameter'],
                ],
            ],
        );
    }
}
