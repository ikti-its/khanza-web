<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKategoriOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefKategoriOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefKategoriOperasiModel(),
            [
                ['Operasi',                    'operasi'],
                ['Referensi Kategori Operasi', 'ref_kategori_operasi'],
            ],
            'Referensi Kategori Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_kategori',   'ID Kategori'],
                [SHOW, REQUIRED, I::TEXT,  'nama_kategori', 'Nama Kategori'],
            ],
        );
    }
}
