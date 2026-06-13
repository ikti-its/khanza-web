<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPengajuanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class RingkasanPengajuanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RingkasanPengajuanBarangDetailModel(),
            [
                ['Inventori Non Medis',        'inventori_non_medis'],
                ['Ringkasan Pengajuan Barang', 'ringkasan_pengajuan_barang'],
                ['Detail',                     'detail'],
            ],
            'Ringkasan Pengajuan Barang Detail',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengajuan',     'ID Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_pengajuan',     'No. Pengajuan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'no_pengajuan',   'No. Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_barang',      'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang',    'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_barang_baru', 'Nama Barang Baru'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang_baru', 'Nama Barang Baru'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,  'harga',            'Harga/Satuan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'harga',          'Harga/Satuan'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER, 'qty',              'Qty Diajukan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty',            'Qty Diajukan'],
                [SHOW,       OPTIONAL, I::NUMBER, 'qty_disetujui',    'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,  'subtotal',         'Subtotal'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'subtotal',       'Subtotal'],
            ],
            parent_fk: 'id_pengajuan',
        );
    }

    // true jika pengajuan sudah Disetujui (2) atau Ditolak (3) — qty tidak bisa diubah lagi
    private function is_locked(int $id_pengajuan): bool
    {
        if ($id_pengajuan <= 0) return false;
        $row = $this->get_db()
            ->table('inventori_non_medis.pengajuan_barang')
            ->select('id_status_pengajuan_barang')
            ->where('id_pengajuan', $id_pengajuan)
            ->get()->getRowArray();
        return is_array($row) && in_array((int) ($row['id_status_pengajuan_barang'] ?? 0), [2, 3], true);
    }

    // hitung ulang subtotal pakai harga existing × qty_disetujui
    protected function before_update(array &$postData, int|string $id): void
    {
        $existing = $this->model->find($id);
        if (!is_array($existing)) return;

        $harga                = (float) ($existing['harga'] ?? 0);
        $qty_disetujui        = (float) ($postData['qty_disetujui'] ?? 0);
        $postData['subtotal'] = $harga * $qty_disetujui;
    }

    // lock check + validasi qty + update total setelah simpan
    public function update(int|string $id): string|RedirectResponse
    {
        $row          = $this->model->find($id);
        $id_pengajuan = is_array($row) ? (int) ($row['id_pengajuan'] ?? 0) : 0;

        if ($this->is_locked($id_pengajuan)) {
            $this->home_params = ['id_pengajuan' => $id_pengajuan];
            session()->setFlashdata('error', 'Pengajuan yang sudah diproses tidak dapat diubah detailnya.');
            return $this->home();
        }

        if (is_array($row)) {
            $qty_disetujui = (float) ($this->request->getPost('qty_disetujui') ?? 0);
            $qty_max       = (float) ($row['qty'] ?? 0);
            if ($qty_disetujui > $qty_max) {
                session()->setFlashdata('error', "Qty disetujui tidak boleh melebihi qty pengajuan ({$qty_max}).");
                return $this->home();
            }
        }

        $result = parent::update($id);
        if ($result instanceof RedirectResponse && $id_pengajuan > 0) {
            $this->recalculate_total($id_pengajuan);
        }
        return $result;
    }

    // jumlah subtotal → update total_harga pengajuan
    private function recalculate_total(int $id_pengajuan): void
    {
        $db  = $this->get_db();
        $row = $db->table('inventori_non_medis.pengajuan_barang_detail')
            ->selectSum('subtotal')
            ->where('id_pengajuan', $id_pengajuan)
            ->get()->getRowArray();

        $db->table('inventori_non_medis.pengajuan_barang')
            ->where('id_pengajuan', $id_pengajuan)
            ->set('total_harga', (float) ($row['subtotal'] ?? 0))
            ->update();
    }
}
