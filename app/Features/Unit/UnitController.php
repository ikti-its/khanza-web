<?php
declare(strict_types=1);

namespace App\Features\Unit;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class UnitController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new UnitModel(),
            [
                ['Unit', 'unit'],
            ],
            'Unit',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_unit',         'ID Unit'],
                [SHOW, REQUIRED, I::TEXT,  'kode_unit',       'Kode Unit'],
                [SHOW, REQUIRED, I::TEXT,  'nama_unit',       'Nama Unit'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_baru', 'Biaya Registrasi Baru'],
                [SHOW, REQUIRED, I::MONEY, 'biaya_registrasi_lama', 'Biaya Registrasi Lama'],
            ],
        );
    }
}