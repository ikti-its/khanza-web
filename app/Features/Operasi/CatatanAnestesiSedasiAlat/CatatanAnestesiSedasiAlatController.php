<?php
declare(strict_types=1);

namespace App\Features\Operasi\CatatanAnestesiSedasiAlat;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class CatatanAnestesiSedasiAlatController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new CatatanAnestesiSedasiAlatModel(),
            [
                ['Operasi',                      'operasi'],
                ['Catatan Anestesi Sedasi Alat', 'catatan_anestesi_sedasi_alat'],
            ],
            'Catatan Anestesi Sedasi Alat',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_alat',             'ID Alat'],
                [HIDE, OPTIONAL, I::INDEX,  'id_catatan_anestesi', 'ID Catatan Anestesi'],
                [SHOW, REQUIRED, I::TEXT,   'nama_alat',           'Nama Alat'],
                [SHOW, REQUIRED, I::SELECT, 'is_digunakan',        'Digunakan'],
                [SHOW, REQUIRED, I::TEXT,   'keterangan',          'Keterangan'],
            ],
        );
    }
}
