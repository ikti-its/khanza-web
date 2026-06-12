<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenerimaanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangDetailModel(),
            [
                ['Inventori Non Medis',  'inventori_non_medis'],
                ['Penerimaan Barang',    'penerimaan_barang'],
                ['Detail',               'detail'],
            ],
            'Penerimaan Barang Detail',
            [
                A::READ,
                // A::CREATE,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_detail',     'ID'],
                [HIDE, OPTIONAL, I::INDEX,  'id_penerimaan', 'ID Penerimaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang', 'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang', 'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_satuan',   'Satuan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_satuan',   'Satuan'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'qty_pengadaan', 'Qty Pengadaan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty_pengadaan', 'Qty Pengadaan'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty_diterima',  'Qty Diterima'],
                [SHOW,       OPTIONAL, I::MONEY,    'harga_satuan',  'Harga Satuan'],
            ],
            parent_fk: 'id_penerimaan',
        );
    }

    // qty_diterima ga boleh lebih dari qty yang dipesan
    public function update(int|string $id): string|RedirectResponse
    {
        $row = $this->model->find($id);
        if (is_array($row)) {
            $id_penerimaan = (int) ($row['id_penerimaan'] ?? 0);
            $id_barang     = (int) ($this->request->getPost('id_barang') ?? $row['id_barang'] ?? 0);
            $qty_diterima  = (float) ($this->request->getPost('qty_diterima') ?? 0);

            if ($id_penerimaan > 0 && $id_barang > 0 && $qty_diterima > 0) {
                $limit = $this->get_db()
                    ->table('inventori_non_medis.penerimaan_barang pb')
                    ->join('inventori_non_medis.pengadaan_barang_detail pbd',
                           'pb.id_pengadaan = pbd.id_pengadaan', 'left')
                    ->select('pbd.qty')
                    ->where('pb.id_penerimaan', $id_penerimaan)
                    ->where('pbd.id_barang', $id_barang)
                    ->get()->getRowArray();

                if (is_array($limit) && (float) ($limit['qty'] ?? 0) > 0) {
                    if ($qty_diterima > (float) $limit['qty']) {
                        session()->setFlashdata('error', "Qty diterima ({$qty_diterima}) tidak boleh melebihi qty yang dipesan ({$limit['qty']}).");
                        return $this->home();
                    }
                }
            }
        }

        return parent::update($id);
    }
}
