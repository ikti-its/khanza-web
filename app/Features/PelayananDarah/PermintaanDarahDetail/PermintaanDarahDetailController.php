<?php
declare(strict_types=1);

namespace App\Features\PelayananDarah\PermintaanDarahDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanDarahDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanDarahDetailModel(),
            [
                ['Pelayanan Darah',         'pelayanan_darah'],
                ['Permintaan Darah Detail', 'permintaan_darah_detail'],
            ],
            'Permintaan Darah Detail',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan_detail', 'ID Permintaan Detail'],
                [SHOW, REQUIRED, I::INDEX,  'id_permintaan',        'ID Permintaan'],
                [SHOW, REQUIRED, I::INDEX,  'id_komponen',          'ID Komponen'],
                [SHOW, REQUIRED, I::INDEX,  'id_golongan_darah',    'ID Golongan Darah'],
                [SHOW, REQUIRED, I::INDEX,  'id_rhesus',            'ID Rhesus'],
                [SHOW, REQUIRED, I::NUMBER, 'jumlah',               'Jumlah Unit'],
            ],
        );
    }
}
