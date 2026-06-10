<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\PermintaanLabPkParameter;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class PermintaanLabPkParameterDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'permintaan_lab_pk_parameter',
            [
                'id_pk_parameter'       => T::ID(100_000),
                'id_permintaan_pk_item' => T::FK_AUTO(),
                'id_parameter'          => T::FK_AUTO(),
            ],
            'id_pk_parameter',
            [],
            [
                [
                    ['id_permintaan_pk_item'],
                    \App\Features\Laboratorium\PermintaanLabPkItem\PermintaanLabPkItemDatabase::class,
                    ['id_permintaan_pk_item'],
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