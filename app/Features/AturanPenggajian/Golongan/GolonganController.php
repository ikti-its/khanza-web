<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Golongan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class GolonganController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new GolonganModel(),
            [
                ['User',     'user'],
                ['Golongan', 'golongan'],
            ],
            'Golongan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'no_golongan',   'Nomor Golongan'],
                [SHOW, REQUIRED, I::TEXT,  'kode_golongan', 'Kode'],
                [SHOW, REQUIRED, I::NAME,  'nama_golongan', 'Golongan'],
                [SHOW, REQUIRED, I::MONEY, 'nama_jenjang',  'Jenjang Pendidikan'],
                [SHOW, REQUIRED, I::MONEY, 'gaji_pokok',    'Gaji Pokok'],
            ],
        );
    }
}
