<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadFoto;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class HasilRadFotoModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadFotoDatabase(),
            [
                'id_rad_foto' => V::DEFAULT(),
                'nama_file'   => V::DEFAULT(),
                'konten_file' => V::DEFAULT(),
                'tgl_upload'  => V::DEFAULT(),
            ],
            [
                'id_hasil_rad' => [],
            ],
        );
    }
}
