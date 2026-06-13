<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

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

    // true jika status permintaan bukan Draf (1) — detail tidak boleh diubah
    private function is_locked(int $id_permintaan): bool
    {
        if ($id_permintaan <= 0) return false;
        $row = $this->get_db()
            ->table('inventori_non_medis.permintaan_barang')
            ->select('id_status_permintaan_barang')
            ->where('id_permintaan', $id_permintaan)
            ->get()->getRowArray();
        return is_array($row) && (int) ($row['id_status_permintaan_barang'] ?? 0) !== 1;
    }

    public function create(): string|RedirectResponse
    {
        $id_permintaan = (int) ($this->request->getPost('id_permintaan') ?? 0);
        if ($this->is_locked($id_permintaan)) {
            $this->home_params = ['id_permintaan' => $id_permintaan];
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat ditambah detailnya.');
            return $this->home();
        }
        return parent::create();
    }

    public function update(int|string $id): string|RedirectResponse
    {
        $detail        = $this->model->find((int) $id);
        $id_permintaan = is_array($detail) ? (int) ($detail['id_permintaan'] ?? 0) : 0;
        if ($this->is_locked($id_permintaan)) {
            $this->home_params = ['id_permintaan' => $id_permintaan];
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah detailnya.');
            return $this->home();
        }
        return parent::update($id);
    }

    public function delete(int|string $id): string|RedirectResponse
    {
        $detail        = $this->model->find((int) $id);
        $id_permintaan = is_array($detail) ? (int) ($detail['id_permintaan'] ?? 0) : 0;
        if ($this->is_locked($id_permintaan)) {
            $this->home_params = ['id_permintaan' => $id_permintaan];
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat dihapus detailnya.');
            return $this->home();
        }
        return parent::delete($id);
    }
}
