<?php
declare(strict_types=1);

namespace App\Features\UjiDarah\HasilDiagnostikDetail;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilDiagnostikDetailDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'uji_darah',
            'hasil_diagnostik_detail',
            [
                'id_diagnostik_detail' => T::ID(10_000_000),
                'id_diagnostik'        => T::FK_AUTO(),
                'id_parameter_uji'     => T::FK_AUTO(),
                'id_nilai_diagnostik'  => T::FK_AUTO(),
            ],
            'id_diagnostik_detail',
            [['id_diagnostik', 'id_parameter_uji']],
            [
                [
                    'id_diagnostik',
                    \App\Features\UjiDarah\HasilDiagnostik\HasilDiagnostikDatabase::class,
                    'id_diagnostik',
                ],
                [
                    'id_parameter_uji',
                    \App\Features\UjiDarah\ParameterUji\ParameterUjiDatabase::class,
                    'id_parameter_uji',
                ],
                [
                    'id_nilai_diagnostik',
                    \App\Features\UjiDarah\NilaiDiagnostik\NilaiDiagnostikDatabase::class,
                    'id_nilai_diagnostik',
                ],
            ],
            false,
            'hasil_diagnostik_detail.csv',
        );
    }
}
