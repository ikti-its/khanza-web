<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\StokDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StokDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokDarahModel(),
            [
                ['Inventaris Darah', 'inventaris_darah'],
                ['Stok Darah',       'stok_darah'],
            ],
            'Stok Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_stok_darah',       'ID Stok Darah'],
                [SHOW, REQUIRED, I::TEXT,   'no_kantong',          'Nomor Kantong'],
                [SHOW, REQUIRED, I::SELECT, 'id_komponen',         'Komponen'],
                [SHOW, REQUIRED, I::SELECT, 'id_golongan_darah',   'Golongan Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_rhesus',           'Rhesus'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_pengambilan', 'Tanggal Pengambilan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_kadaluarsa',  'Tanggal Kadaluarsa'],
                [SHOW, REQUIRED, I::SELECT, 'id_sumber_darah',     'Sumber Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_stok',      'Status Stok'],
            ],
        );
    }
}
