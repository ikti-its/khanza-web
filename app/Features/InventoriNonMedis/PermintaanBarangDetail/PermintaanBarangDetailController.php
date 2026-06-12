<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanBarangDetailController extends ControllerTemplate
{
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
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_permintaan',    'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',      'Barang'],
                [FORM_ONLY,  OPTIONAL, I::MODAL,    'id_barang',        'Barang',  ['modal' => 'modalBarang', 'display_column' => 'nama_barang', 'placeholder' => 'Klik cari barang...']],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_satuan',      'Satuan'],
                [SHOW,       OPTIONAL, I::TEXT,     'nama_barang_baru', 'Nama Barang Baru'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty',              'Qty'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'qty_disetujui',    'Qty Disetujui'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty_disetujui',    'Qty Disetujui'],
                [SHOW,       OPTIONAL, I::TEXT,     'catatan',          'Catatan'],
            ],
            parent_fk: 'id_permintaan',
        );
    }

}
