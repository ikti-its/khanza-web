<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PenerimaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Penerimaan Barang',   'penerimaan_barang'],
            ],
            'Penerimaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_penerimaan', 'ID'],
                [SHOW, REQUIRED, I::SELECT, 'id_pengadaan',  'No. Pengadaan'],
                [SHOW, REQUIRED, I::DATE,   'tanggal',       'Tanggal Terima'],
                [SHOW, OPTIONAL, I::TEXT,   'catatan',       'Catatan'],
            ],
            child_path: '/inventori-non-medis/penerimaan-barang-detail',
            child_fk: 'id_penerimaan',
        );
    }
}
