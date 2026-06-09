<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class RingkasanPermintaanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RingkasanPermintaanBarangDetailModel(),
            [
                ['Inventori Non Medis',        'inventori_non_medis'],
                ['Ringkasan Permintaan Barang', 'ringkasan_permintaan_barang'],
                ['Detail',                     'detail'],
            ],
            'Ringkasan Permintaan Barang Detail',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan',    'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_keluar',        'No. Keluar'],
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_barang',        'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_barang_baru', 'Nama Barang Baru'],
                [TABLE_ONLY, REQUIRED, I::NUMBER, 'qty',              'Qty Diminta'],
                [SHOW,       OPTIONAL, I::NUMBER, 'qty_disetujui',    'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'catatan',          'Catatan'],
            ],
            parent_fk: 'id_permintaan',
        );
    }
}
