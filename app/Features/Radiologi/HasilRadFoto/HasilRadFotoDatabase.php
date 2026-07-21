<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadFoto;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class HasilRadFotoDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'radiologi',
            'hasil_rad_foto',
            [
                'id_rad_foto'  => T::ID(100_000_000),
                'id_hasil_rad' => T::FK_AUTO(),
                'nama_file'    => T::NAME(500),
                'konten_file'  => T::FILE()->nullable(),
                'tgl_upload'   => T::DTIME(),
            ],
            'id_rad_foto',
            [],
            [
                [
                    ['id_hasil_rad'],
                    \App\Features\Radiologi\HasilRad\HasilRadDatabase::class,
                    ['id_hasil_rad'],
                ],
            ],
        );
    }
}
