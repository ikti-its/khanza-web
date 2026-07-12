<?php
declare(strict_types=1);

namespace App\Features\Radiologi\HasilRadBhp;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class HasilRadBhpController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new HasilRadBhpModel(),
            [
                ['Radiologi',           'radiologi'],
                ['Hasil Radiologi BHP', 'hasil_rad_bhp'],
            ],
            'Hasil Radiologi BHP',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_rad_bhp',   'ID Radiologi BHP'],
                [HIDE, REQUIRED, I::INDEX,  'id_hasil_rad', 'Hasil Radiologi'],
                [SHOW, REQUIRED, I::INDEX,  'id_barang',    'BHP Radiologi'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah_pakai', 'Jumlah Pakai'],
            ],
        );
    }
}
