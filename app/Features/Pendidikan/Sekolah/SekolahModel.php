<?php
declare(strict_types=1);

namespace App\Features\Pendidikan\Sekolah;

use App\Core\Model\ModelTemplate;
use App\Core\Model\ValidationType as V;

final class SekolahModel extends ModelTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SekolahDatabase(),
            [
                'id_sekolah'   => V::DEFAULT(),
                'nama_sekolah' => V::DEFAULT(),
            ],
            [
                'id_jenis'  => ['nama_jenis'],
                'alamat_id' => ['alamat_lengkap'],
            ],
        );
    }
}
