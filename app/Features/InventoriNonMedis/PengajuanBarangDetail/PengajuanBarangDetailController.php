<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengajuanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengajuan Barang',    'pengajuan_barang'],
                ['Detail',              'detail'],
            ],
            'Detail Pengajuan Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',        'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX,  'id_pengajuan',     'ID Pengajuan'],
                [SHOW, OPTIONAL, I::SELECT, 'id_barang',        'Barang'],
                [SHOW, OPTIONAL, I::TEXT,   'nama_barang_baru', 'Nama Barang Baru'],
                [SHOW, REQUIRED, I::NUMBER, 'qty',              'Qty'],
                [SHOW, OPTIONAL, I::MONEY,  'harga_estimasi',   'Harga Estimasi'],
            ],
            $parent_fk = 'id_pengajuan',
        );
    }

}
