<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpnameDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class StokOpnameDetailController extends ControllerTemplate
{
    protected ?string $parent_fk = 'id_opname';

    public function __construct()
    {
        parent::__construct(
            new StokOpnameDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Stok Opname',         'stok_opname'],
                ['Detail',              'detail'],
            ],
            'Detail Stok Opname',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',   'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX,  'id_opname',   'ID Opname'],
                [SHOW, REQUIRED, I::SELECT, 'id_barang',   'Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'stok_sistem', 'Stok Sistem'],
                [SHOW, REQUIRED, I::NUMBER, 'stok_fisik',  'Stok Fisik'],
                [SHOW, OPTIONAL, I::NUMBER, 'selisih',     'Selisih'],
                [SHOW, OPTIONAL, I::TEXT,   'keterangan',  'Keterangan'],
            ],
        );
    }
}
