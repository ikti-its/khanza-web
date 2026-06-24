<?php
declare(strict_types=1);

namespace App\Features\Operasi\PenyerahanPasienPeralatan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenyerahanPasienPeralatanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenyerahanPasienPeralatanModel(),
            [
                ['Operasi',                     'operasi'],
                ['Penyerahan Pasien Peralatan', 'penyerahan_pasien_peralatan'],
            ],
            'Penyerahan Pasien Peralatan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id',            'ID'],
                [HIDE, OPTIONAL, I::INDEX, 'id_penyerahan', 'ID Penyerahan'],
                [SHOW, REQUIRED, I::INDEX, 'id_peralatan',  'Peralatan'],
                [SHOW, OPTIONAL, I::TEXT,  'keterangan',    'Keterangan'],
            ],
        );
    }
}
