<?php
declare(strict_types=1);

namespace App\Features\Laboratorium\HasilLabMbParameter;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilLabMbParameterDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'laboratorium',
            'hasil_lab_mb_parameter',
            [
                'id_hasil_mb_parameter' => T::ID(100_000_000),
                'id_hasil_mb'           => T::FK_AUTO(),
                'id_parameter'          => T::FK_AUTO(),
                'nilai_hasil'           => T::TEXT(),
                'keterangan_hasil'      => T::NOTE()->nullable(),
            ],
            'id_hasil_mb_parameter',
            [],
            [
                [
                    ['id_hasil_mb'],
                    \App\Features\Laboratorium\HasilLabMb\HasilLabMbDatabase::class,
                    ['id_hasil_mb'],
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