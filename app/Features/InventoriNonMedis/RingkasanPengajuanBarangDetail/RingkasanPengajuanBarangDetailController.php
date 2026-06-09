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
                [TABLE_ONLY, OPTIONAL, I::SELECT, 'id_barang',        'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nama_barang_baru', 'Nama Barang Baru'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,  'harga',            'Harga/Satuan'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER, 'qty',              'Qty Diajukan'],
                [SHOW,       OPTIONAL, I::NUMBER, 'qty_disetujui',    'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,  'subtotal',         'Subtotal'],
            ],
            parent_fk: 'id_pengajuan',
        );
    }

    protected function before_update(array &$postData, int|string $id): void
    {
        $existing = $this->model->find($id);
        if (!is_array($existing)) return;

        $harga             = (float) ($existing['harga'] ?? 0);
        $qty_disetujui     = (float) ($postData['qty_disetujui'] ?? 0);
        $postData['subtotal'] = $harga * $qty_disetujui;
    }

    public function update(int|string $id): string|RedirectResponse
    {
        $row    = $this->model->find($id);
        $result = parent::update($id);
        if ($result instanceof RedirectResponse && is_array($row)) {
            $id_pengajuan = (int) ($row['id_pengajuan'] ?? 0);
            if ($id_pengajuan > 0) $this->recalculate_total($id_pengajuan);
        }
        return $result;
    }

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
