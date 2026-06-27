<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\PemisahanKomponenDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PemisahanKomponenDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PemisahanKomponenDetailModel(),
            [
                ['Inventori Darah',           'inventori_darah'],
                ['Pemisahan Komponen Detail', 'pemisahan_komponen_detail'],
            ],
            'Pemisahan Komponen Detail',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                // A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pemisahan_detail', 'ID Pemisahan Detail'],
                [SHOW, REQUIRED, I::INDEX, 'id_pemisahan',        'ID Pemisahan'],
                [SHOW, REQUIRED, I::TEXT,  'no_kantong',          'Nomor Kantong'],
                [SHOW, REQUIRED, I::INDEX, 'id_komponen',         'ID Komponen'],
                [SHOW, REQUIRED, I::DATE,  'tanggal_kadaluarsa',  'Tanggal Kadaluarsa'],
            ],
        );
    }
}
