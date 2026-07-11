<?php
declare(strict_types=1);

namespace App\Features\Pendidikan\Sekolah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class SekolahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new SekolahModel(),
            [
                ['Pendidikan', 'pendidikan'],
                ['Sekolah',    'sekolah'],
            ],
            'Sekolah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_sekolah',     'ID'],
                [SHOW, REQUIRED, I::TEXT,  'nama_sekolah',   'Nama Sekolah'],
                [SHOW, REQUIRED, I::TEXT,  'nama_jenis',     'Jenis Pendidikan'],
                [SHOW, REQUIRED, I::TEXT,  'alamat_lengkap', 'Alamat'],
            ],
        );
    }
}
