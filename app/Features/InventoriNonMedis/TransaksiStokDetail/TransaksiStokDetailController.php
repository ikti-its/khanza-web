<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStokDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TransaksiStokDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiStokDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Transaksi Stok',      'transaksi_stok'],
                ['Detail',             'detail'],
            ],
            'Transaksi Stok Detail',
            [
                A::READ,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_detail',    'ID'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_transaksi', 'ID Transaksi'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_barang',    'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'kode_barang',  'Kode'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_satuan',  'Satuan'],
                [SHOW,       REQUIRED, I::NUMBER, 'qty',          'Qty'],
            ],
            parent_fk: 'id_transaksi',
        );
    }
}
