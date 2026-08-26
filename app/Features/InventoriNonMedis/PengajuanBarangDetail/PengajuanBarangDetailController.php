<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

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
                A::BACK,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_detail', 'ID Detail'],
                [HIDE, OPTIONAL, I::INDEX, 'id_pengajuan', 'ID Pengajuan'],
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
                [SHOW, OPTIONAL, I::MONEY, 'harga', 'Harga/Satuan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY, 'subtotal', 'Subtotal'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'subtotal', 'Subtotal'],
            ],
            parent_fk: 'id_pengajuan',
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

    // true jika status pengajuan bukan Draf (1) — detail tidak boleh diubah
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function is_locked(int $id_pengajuan): bool
    {
        if ($id_pengajuan <= 0)
            return false;
        $row = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.pengajuan_barang')
                ->select('id_status_pengajuan_barang')
                ->where('id_pengajuan', $id_pengajuan)
                ->get(),
        )->getRowArray();
        return is_array($row) && (int) ($row['id_status_pengajuan_barang'] ?? 0) !== 1;
    }

    // hitung subtotal
    #[\Override]
    protected function before_create(array &$postData): void
    {
        $postData['subtotal'] = (float) ($postData['harga'] ?? 0) * (float) ($postData['qty'] ?? 0);
    }

    // hitung ulang subtotal
    #[\Override]
    protected function before_update(array &$postData, int|string $id): void
    {
        $postData['subtotal'] = (float) ($postData['harga'] ?? 0) * (float) ($postData['qty'] ?? 0);
    }

    // lock check + duplikat check + update total_harga setelah tambah
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $id_pengajuan = (int) ($this->request->getPost('id_pengajuan') ?? 0);
        if ($this->is_locked($id_pengajuan)) {
            $this->home_params = ['id_pengajuan' => $id_pengajuan];
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat ditambah detailnya.');
            return $this->home();
        }

        $id_barang = (int) ($this->request->getPost('id_barang') ?? 0);
        if ($id_barang > 0 && $id_pengajuan > 0) {
            $exists = $this
                ->get_db()
                ->table('inventori_non_medis.pengajuan_barang_detail')
                ->where('id_pengajuan', $id_pengajuan)
                ->where('id_barang', $id_barang)
                ->countAllResults();
            if ($exists > 0) {
                $this->home_params = ['id_pengajuan' => $id_pengajuan];
                session()->setFlashdata(
                    'error',
                    'Barang tersebut sudah ada pada pengajuan ini. Ubah data yang sudah ada jika ingin mengubah jumlahnya.',
                );
                return $this->home();
            }
        }

        $result = parent::create();
        if ($result instanceof RedirectResponse && $id_pengajuan > 0) {
            $this->recalculate_total($id_pengajuan);
        }
        return $result;
    }

    // lock check + update total_harga setelah ubah
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $row          = $this->model->find($id);
        $id_pengajuan = is_array($row) ? (int) ($row['id_pengajuan'] ?? 0) : 0;
        if ($this->is_locked($id_pengajuan)) {
            $this->home_params = ['id_pengajuan' => $id_pengajuan];
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat diubah detailnya.');
            return $this->home();
        }
        $result = parent::update($id);
        if ($result instanceof RedirectResponse && $id_pengajuan > 0) {
            $this->recalculate_total($id_pengajuan);
        }
        return $result;
    }

    // lock check + update total_harga setelah hapus
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        $row          = $this->model->find($id);
        $id_pengajuan = is_array($row) ? (int) ($row['id_pengajuan'] ?? 0) : 0;
        if ($this->is_locked($id_pengajuan)) {
            $this->home_params = ['id_pengajuan' => $id_pengajuan];
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat dihapus detailnya.');
            return $this->home();
        }
        $result = parent::delete($id);
        if ($id_pengajuan > 0)
            $this->recalculate_total($id_pengajuan);
        return $result;
    }

    // jumlah subtotal → update total_harga pengajuan
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function recalculate_total(int $id_pengajuan): void
    {
        $db  = $this->get_db();
        $row = $this->guarded(
            $db
                ->table('inventori_non_medis.pengajuan_barang_detail')
                ->selectSum('subtotal')
                ->where('id_pengajuan', $id_pengajuan)
                ->get(),
        )->getRowArray();

        $db
            ->table('inventori_non_medis.pengajuan_barang')
            ->where('id_pengajuan', $id_pengajuan)
            ->set('total_harga', (float) ($row['subtotal'] ?? 0))
            ->update();
    }
}
