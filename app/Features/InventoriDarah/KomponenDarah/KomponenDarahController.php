<?php
declare(strict_types=1);

namespace App\Features\InventoriDarah\KomponenDarah;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class KomponenDarahController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KomponenDarahModel(),
            [
                ['Inventori Darah',  'inventori_darah'],
                ['Komponen Darah',   'komponen_darah'],
            ],
            'Komponen Darah',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_komponen',       'ID Komponen'],
                [SHOW, REQUIRED, I::TEXT,   'kode_komponen',     'Kode'],
                [SHOW, REQUIRED, I::TEXT,   'nama_komponen',     'Nama Komponen'],
                [SHOW, REQUIRED, I::NUMBER, 'masa_berlaku_hari', 'Masa Berlaku (Hari)'],
                [SHOW, REQUIRED, I::MONEY, 'jasa_sarana',        'Jasa Sarana'],
                [SHOW, REQUIRED, I::MONEY, 'paket_bhp',          'Paket BHP'],
                [SHOW, REQUIRED, I::MONEY, 'kso',                'KSO'],
                [SHOW, REQUIRED, I::MONEY, 'manajemen',          'Manajemen'],
                [SHOW, REQUIRED, I::MONEY, 'pembatalan',         'Pembatalan'],
            ],
        );
    }
}
