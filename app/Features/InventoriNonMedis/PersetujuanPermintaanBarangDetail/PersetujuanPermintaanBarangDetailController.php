<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PersetujuanPermintaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PersetujuanPermintaanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PersetujuanPermintaanBarangDetailModel(),
            [
                ['Inventori Non Medis',           'inventori_non_medis'],
                ['Persetujuan Permintaan Barang', 'persetujuan_permintaan_barang'],
                ['Detail',                        'detail'],
            ],
            'Persetujuan Permintaan Barang Detail',
            [
                A::READ,
                A::BACK,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_permintaan',    'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'no_keluar',        'No. Keluar'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'no_keluar',        'No. Keluar'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',      'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang',      'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang_baru', 'Nama Barang Baru'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang_baru', 'Nama Barang Baru'],
                [TABLE_ONLY, REQUIRED, I::NUMBER,   'qty',              'Qty Diminta'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty',              'Qty Diminta'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty_disetujui',    'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'catatan',          'Catatan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'catatan',          'Catatan'],
            ],
            parent_fk: 'id_permintaan',
        );
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    // true jika permintaan sudah final: Disetujui (2, legacy)/Ditolak (3)/Menunggu Pengadaan (5)/Selesai (6) — qty tidak bisa diubah lagi
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function is_locked(int $id_permintaan): bool
    {
        if ($id_permintaan <= 0)
            return false;
        $row = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang')
                ->select('id_status_permintaan_barang')
                ->where('id_permintaan', $id_permintaan)
                ->get(),
        )->getRowArray();
        return is_array($row) && in_array((int) ($row['id_status_permintaan_barang'] ?? 0), [2, 3, 5, 6], true);
    }

    // qty_disetujui tidak bisa diubah jika sudah diproses, dan tidak boleh melebihi qty diminta
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $row = $this->model->find($id);
        if (is_array($row)) {
            $id_permintaan = (int) ($row['id_permintaan'] ?? 0);
            if ($this->is_locked($id_permintaan)) {
                $this->home_params = ['id_permintaan' => $id_permintaan];
                session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah detailnya.');
                return $this->home();
            }

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
