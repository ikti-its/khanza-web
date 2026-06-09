<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenerimaanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDetailModel(),
            [
                ['Inventori Non Medis',  'inventori_non_medis'],
                ['Penerimaan Barang',    'penerimaan_barang'],
                ['Detail',               'detail'],
            ],
            'Penerimaan Barang Detail',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',     'ID'],
                [HIDE, OPTIONAL, I::INDEX,  'id_penerimaan', 'ID Penerimaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_barang',     'Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'qty_diterima',  'Qty Diterima'],
                [SHOW, OPTIONAL, I::MONEY,  'harga_satuan',  'Harga Satuan'],
            ],
            parent_fk: 'id_penerimaan',
        );
    }
}
