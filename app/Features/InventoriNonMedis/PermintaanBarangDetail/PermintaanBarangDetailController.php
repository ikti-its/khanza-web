<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanBarangDetailController extends ControllerTemplate
{
    protected ?string $parent_fk = 'id_permintaan';

    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Permintaan Barang',   'permintaan_barang'],
                ['Detail',              'detail'],
            ],
            'Detail Permintaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',        'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',    'Tanggal Permintaan'],
                [SHOW, OPTIONAL, I::SELECT, 'id_barang',        'Barang'],
                [SHOW, OPTIONAL, I::TEXT,   'nama_barang_baru', 'Nama Barang Baru'],
                [SHOW, REQUIRED, I::NUMBER, 'qty',              'Qty'],
                [SHOW, OPTIONAL, I::NUMBER, 'qty_disetujui',    'Qty Disetujui'],
                [SHOW, OPTIONAL, I::TEXT,   'catatan',          'Catatan'],
            ],
        );
    }

}
