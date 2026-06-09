<?php
declare(strict_types=1);

namespace App\Features\Role\Dokter;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DokterController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DokterModel(),
            [
                ['Role',    'role'],
                ['Dokter',  'dokter'],
            ],
            'Dokter',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_dokter',   'ID Dokter'],
                [SHOW, REQUIRED, I::TEXT,  'kode_dokter', 'Kode Dokter'],
                [SHOW, REQUIRED, I::TEXT,  'spesialis',   'Spesialis'],
                [HIDE, OPTIONAL, I::INDEX, 'id_orang',    'ID Orang'],
            ],
        );
    }
}