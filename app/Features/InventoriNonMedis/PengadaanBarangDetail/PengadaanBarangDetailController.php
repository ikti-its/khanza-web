<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengadaanBarangDetailController extends ControllerTemplate
{
    protected ?string $parent_fk = 'id_pengadaan';

    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengadaan Barang',    'pengadaan_barang'],
                ['Detail',              'detail'],
            ],
            'Detail Pengadaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',    'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX,  'id_pengadaan', 'ID Pengadaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_barang',    'Barang'],
                [SHOW, REQUIRED, I::NUMBER, 'qty',          'Qty'],
                [SHOW, OPTIONAL, I::MONEY,  'harga_satuan', 'Harga Satuan'],
            ],
        );
    }

}
