<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

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
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'no_keluar',        'No. Keluar'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'no_keluar',      'No. Keluar'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',    'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang',    'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang_baru', 'Nama Barang Baru'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang_baru', 'Nama Barang Baru'],
                [TABLE_ONLY, REQUIRED, I::NUMBER,   'qty',            'Qty Diminta'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty',            'Qty Diminta'],
                [SHOW,       OPTIONAL, I::NUMBER,   'qty_disetujui',  'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'catatan',        'Catatan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'catatan',        'Catatan'],
            ],
            parent_fk: 'id_permintaan',
        );
    }

    // qty_disetujui ga boleh lebih dari qty yang diminta
    public function update(int|string $id): string|RedirectResponse
    {
        $row = $this->model->find($id);
        if (is_array($row)) {
            $qty_disetujui = (float) ($this->request->getPost('qty_disetujui') ?? 0);
            $qty_max       = (float) ($row['qty'] ?? 0);
            if ($qty_disetujui > $qty_max) {
                session()->setFlashdata('error', "Qty disetujui tidak boleh melebihi qty permintaan ({$qty_max}).");
                return $this->home();
            }
        }
        return parent::update($id);
    }
}
