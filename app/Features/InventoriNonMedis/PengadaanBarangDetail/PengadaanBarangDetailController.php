<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengadaanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengadaan Barang',    'pengadaan_barang'],
                ['Detail',              'detail'],
            ],
            'Detail Pengadaan Barang',
            [
                A::READ,
                A::BACK,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',     'ID'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengadaan',  'ID Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',   'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang',   'Barang'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'qty_disetujui', 'Qty Disetujui'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty_disetujui', 'Qty Disetujui'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty',           'Qty Pengadaan'],
                [SHOW,       OPTIONAL, I::MONEY,    'harga_satuan',  'Harga Satuan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'subtotal',      'Subtotal'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'subtotal',      'Subtotal'],
            ],
            parent_fk: 'id_pengadaan',
        );
    }

    // hitung ulang subtotal
    protected function before_update(array &$postData, int|string $id): void
    {
        $harga                = (float) ($postData['harga_satuan'] ?? 0);
        $qty                  = (float) ($postData['qty'] ?? 0);
        $postData['subtotal'] = $harga * $qty;
    }

    // validasi total qty seluruh pengadaan ≤ qty disetujui pengajuan, update total_harga pengadaan
    public function update(int|string $id): string|RedirectResponse
    {
        $row = $this->model->find($id);
        if (is_array($row)) {
            $id_pengadaan = (int) ($row['id_pengadaan'] ?? 0);
            $id_barang    = (int) ($row['id_barang'] ?? 0);
            if ($id_pengadaan > 0 && $id_barang > 0) {
                $db    = $this->get_db();
                $limit = $db
                    ->table('inventori_non_medis.pengadaan_barang pb')
                    ->join(
                        'inventori_non_medis.pengajuan_barang_detail pjd',
                        'pb.id_pengajuan = pjd.id_pengajuan',
                        'left',
                    )
                    ->select('pb.id_pengajuan, pjd.qty_disetujui')
                    ->where('pb.id_pengadaan', $id_pengadaan)
                    ->where('pb.id_pengajuan >', 0)
                    ->where('pjd.id_barang', $id_barang)
                    ->get()
                    ->getRowArray();

                if (is_array($limit)) {
                    $qty_disetujui = (float) ($limit['qty_disetujui'] ?? 0);
                    $id_pengajuan  = (int) ($limit['id_pengajuan'] ?? 0);
                    $qty_new       = (float) ($this->request->getPost('qty') ?? 0);

                    if ($qty_disetujui > 0 && $id_pengajuan > 0) {
                        // total qty di semua pengadaan lain untuk pengajuan+barang yang sama
                        $already = (float) (
                            $db
                                ->table('inventori_non_medis.pengadaan_barang_detail pbd')
                                ->join(
                                    'inventori_non_medis.pengadaan_barang pb2',
                                    'pbd.id_pengadaan = pb2.id_pengadaan',
                                    'left',
                                )
                                ->selectSum('pbd.qty', 'total')
                                ->where('pb2.id_pengajuan', $id_pengajuan)
                                ->where('pbd.id_barang', $id_barang)
                                ->where('pbd.id_detail !=', (int) $id)
                                ->get()
                                ->getRowArray()['total'] ?? 0
                        );

                        if (($qty_new + $already) > $qty_disetujui) {
                            $sisa = max(0, $qty_disetujui - $already);
                            session()->setFlashdata(
                                'error',
                                "Qty melebihi sisa yang disetujui. Maks: {$sisa} (dari total {$qty_disetujui} yang disetujui).",
                            );
                            return $this->home();
                        }
                    }
                }
            }
        }
        $result = parent::update($id);
        if ($result instanceof RedirectResponse && is_array($row)) {
            $id_pengadaan = (int) ($row['id_pengadaan'] ?? 0);
            if ($id_pengadaan > 0)
                $this->recalculate_total($id_pengadaan);
        }
        return $result;
    }

    // update total_harga pengadaan setelah baris dihapus
    public function delete(int|string $id): string|RedirectResponse
    {
        $row    = $this->model->find($id);
        $result = parent::delete($id);
        if ($result instanceof RedirectResponse && is_array($row)) {
            $id_pengadaan = (int) ($row['id_pengadaan'] ?? 0);
            if ($id_pengadaan > 0)
                $this->recalculate_total($id_pengadaan);
        }
        return $result;
    }

    // jumlah subtotal → update total_harga pengadaan
    private function recalculate_total(int $id_pengadaan): void
    {
        $db  = $this->get_db();
        $row = $db
            ->table('inventori_non_medis.pengadaan_barang_detail')
            ->selectSum('subtotal')
            ->where('id_pengadaan', $id_pengadaan)
            ->get()
            ->getRowArray();

        $db
            ->table('inventori_non_medis.pengadaan_barang')
            ->where('id_pengadaan', $id_pengadaan)
            ->set('total_harga', (float) ($row['subtotal'] ?? 0))
            ->update();
    }
}
