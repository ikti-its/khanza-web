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
                A::BACK,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_detail', 'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan', 'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT, 'nama_barang', 'Barang'],
                [
                    FORM_ONLY,
                    OPTIONAL,
                    I::MODAL,
                    'id_barang',
                    'Barang',
                    [
                        'modal'          => 'modalBarang',
                        'display_column' => 'nama_barang',
                        'placeholder'    => 'Klik cari barang...',
                    ],
                ],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'nama_satuan', 'Satuan'],
                [SHOW, OPTIONAL, I::TEXT, 'nama_barang_baru', 'Nama Barang Baru'],
                [SHOW, REQUIRED, I::NUMBER, 'qty', 'Qty'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER, 'qty_disetujui', 'Qty Disetujui'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'qty_disetujui', 'Qty Disetujui'],
                [SHOW, OPTIONAL, I::TEXT, 'catatan', 'Catatan'],
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

    // true jika status permintaan bukan Draf (1) — detail tidak boleh diubah
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
        return is_array($row) && (int) ($row['id_status_permintaan_barang'] ?? 0) !== 1;
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $id_permintaan = (int) ($this->request->getPost('id_permintaan') ?? 0);
        if ($this->is_locked($id_permintaan)) {
            $this->home_params = ['id_permintaan' => $id_permintaan];
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat ditambah detailnya.');
            return $this->home();
        }

        $id_barang = (int) ($this->request->getPost('id_barang') ?? 0);
        if ($id_barang > 0 && $id_permintaan > 0) {
            $exists = $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', $id_permintaan)
                ->where('id_barang', $id_barang)
                ->countAllResults();
            if ($exists > 0) {
                $this->home_params = ['id_permintaan' => $id_permintaan];
                session()->setFlashdata(
                    'error',
                    'Barang tersebut sudah ada pada permintaan ini. Ubah data yang sudah ada jika ingin mengubah jumlahnya.',
                );
                return $this->home();
            }
        }

        return parent::create();
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
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

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
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
