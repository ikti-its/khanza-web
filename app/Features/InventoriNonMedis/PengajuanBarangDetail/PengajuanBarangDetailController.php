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
                A::CREATE,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengajuan',     'ID Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',      'Barang'],
                [FORM_ONLY,  OPTIONAL, I::MODAL,    'id_barang',        'Barang',  ['modal' => 'modalBarang', 'display_column' => 'nama_barang']],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_satuan',      'Satuan'],
                [SHOW,       OPTIONAL, I::TEXT,     'nama_barang_baru', 'Nama Barang Baru'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty',              'Qty'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'qty_disetujui',    'Qty Disetujui'],
                [SHOW,       OPTIONAL, I::MONEY,    'harga',            'Harga/Satuan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'subtotal',         'Subtotal'],
            ],
            parent_fk: 'id_pengajuan',
        );
    }

    protected function before_create(array &$postData): void
    {
        $postData['subtotal'] = (float) ($postData['harga'] ?? 0) * (float) ($postData['qty'] ?? 0);
    }

    protected function before_update(array &$postData, int|string $id): void
    {
        $postData['subtotal'] = (float) ($postData['harga'] ?? 0) * (float) ($postData['qty'] ?? 0);
    }

    public function create(): string|RedirectResponse
    {
        $result = parent::create();
        if ($result instanceof RedirectResponse) {
            $id_pengajuan = (int) $this->request->getPost('id_pengajuan');
            if ($id_pengajuan > 0) $this->recalculate_total($id_pengajuan);
        }
        return $result;
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

    public function delete(int|string $id): string|RedirectResponse
    {
        $row    = $this->model->find($id);
        $result = parent::delete($id);
        if (is_array($row)) {
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
