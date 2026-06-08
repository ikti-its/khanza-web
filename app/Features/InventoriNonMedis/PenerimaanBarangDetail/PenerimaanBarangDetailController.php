<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenerimaanBarangDetailController extends ControllerTemplate
{
    protected ?string $parent_fk = 'id_penerimaan';

    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDetailModel(),
            [
                ['Inventori Non Medis',      'inventori_non_medis'],
                ['Penerimaan Barang Detail', 'penerimaan_barang_detail'],
            ],
            'Penerimaan Barang Detail',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',     'ID'],
                [SHOW, REQUIRED, I::SELECT, 'id_penerimaan', 'No. Penerimaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_barang',     'Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'qty_diterima',  'Qty Diterima'],
                [SHOW, OPTIONAL, I::MONEY,  'harga_satuan',  'Harga Satuan'],
            ],
        );
    }

}
