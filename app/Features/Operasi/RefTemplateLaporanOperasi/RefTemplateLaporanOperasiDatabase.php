<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefTemplateLaporanOperasi;

use App\Core\Database\Template\DatabaseTemplate;
use App\Core\Database\Template\SemanticType as T;

final class RefTemplateLaporanOperasiDatabase extends DatabaseTemplate
{
    public function __construct()
    {
        parent::__construct(
            'operasi',
            'ref_template_laporan_operasi',
            [
                'id_template'   => T::ID(10),
                'nama_template' => T::TEXT(),
                'isi_template'  => T::TEXT(),
            ],
            'id_template',
            [],
            [],
            true,
            'template_laporan_operasi.csv',
        );
    }
}
