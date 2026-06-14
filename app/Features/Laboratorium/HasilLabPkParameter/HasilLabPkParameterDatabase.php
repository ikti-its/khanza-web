<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabPkParameter;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilLabPkParameterDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'hasil_lab_pk_parameter',
            [
                'id_hasil_pk_parameter' => T::ID(100_000_000),
                'id_hasil_pk'           => T::FK_AUTO(),
                'id_parameter'          => T::FK_AUTO(),
                'nilai_hasil'           => T::TEXT(),
                'keterangan_hasil'      => T::NOTE()->nullable(),
            ],
            'id_hasil_pk_parameter',
            [],
            [
                [
                    ['id_hasil_pk'],
                    \App\Features\Laboratorium\HasilLabPk\HasilLabPkDatabase::class,
                    ['id_hasil_pk'],
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