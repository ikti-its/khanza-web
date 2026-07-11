<?php
declare(strict_types=1);

namespace App\Features\Operasi\RefKomponenJasa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RefKomponenJasaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RefKomponenJasaModel(),
            [
                ['Operasi',                 'operasi'],
                ['Referensi Komponen Jasa', 'ref_komponen_jasa'],
            ],
            'Referensi Komponen Jasa',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                // A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_komponen',   'ID Komponen'],
                [SHOW, REQUIRED, I::TEXT,  'nama_komponen', 'Nama Komponen'],
            ],
        );
    }
}
