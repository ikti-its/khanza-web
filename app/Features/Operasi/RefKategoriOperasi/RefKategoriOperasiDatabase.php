<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKategoriOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefKategoriOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'ref_kategori_operasi',
            [
                'id_kategori'   => T::ID(10),
                'nama_kategori' => T::TEXT(),
            ],
            'id_kategori',
            [],
            [],
            true,
            'kategori_operasi.csv',
        );
    }
}
