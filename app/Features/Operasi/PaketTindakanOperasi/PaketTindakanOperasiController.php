<?php
declare(strict_types=1);

namespace App\Features\Operasi\PaketTindakanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PaketTindakanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PaketTindakanOperasiModel(),
            [
                ['Operasi',                'operasi'],
                ['Paket Tindakan Operasi', 'paket_tindakan_operasi'],
            ],
            'Paket Tindakan Operasi',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_paket',      'ID Paket'],
                [SHOW, REQUIRED, I::INDEX, 'id_tindakan',   'Tindakan Operasi'],
                [SHOW, REQUIRED, I::INDEX, 'id_komponen',   'Komponen Jasa'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_3', 'Tarif Kelas 3'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_2', 'Tarif Kelas 2'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_kelas_1', 'Tarif Kelas 1'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_vip',     'Tarif VIP'],
                [SHOW, REQUIRED, I::MONEY, 'tarif_vvip',    'Tarif VVIP'],
            ],
        );
    }
}
