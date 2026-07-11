<?php
declare(strict_types=1);

namespace App\Features\AturanPenggajian\Jabatan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class JabatanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new JabatanModel(),
            [
                ['User',    'user'],
                ['Jabatan', 'jabatan'],
            ],
            'Jabatan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [SHOW, REQUIRED, I::INDEX, 'no_jabatan',    'Nomor Jabatan'],
                [SHOW, REQUIRED, I::NAME,  'jenis_jabatan', 'Jenis Jabatan'],
                [SHOW, REQUIRED, I::NAME,  'nama_jabatan',  'Nama Jabatan'],
                [SHOW, REQUIRED, I::TEXT,  'jenjang',       'Jenjang'],
                [SHOW, REQUIRED, I::MONEY, 'tunjangan',     'Tunjangan'],
            ],
        );
    }
}
