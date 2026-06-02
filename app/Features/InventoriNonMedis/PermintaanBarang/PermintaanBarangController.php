<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PermintaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Permintaan Barang',   'permintaan_barang'],
            ],
            'Permintaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_permintaan',               'ID'],
                [SHOW, REQUIRED, I::DATE,   'tanggal',                     'Tanggal'],
                [SHOW, REQUIRED, I::SELECT, 'id_unit',                     'Unit Pemohon'],
                [HIDE, OPTIONAL, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [SHOW, OPTIONAL, I::TEXT,   'catatan',                     'Catatan'],
            ],
        );
    }

    /** @param array<string, scalar|null> $postData */
    protected function before_create(array &$postData): void
    {
        $postData['id_status_permintaan_barang'] = 1;
    }

    /** @param array<string, scalar|null> $postData */
    protected function before_update(int|string $id, array &$postData): void
    {
        unset($postData['id_status_permintaan_barang']);
    }
}
