<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Barang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class BarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new BarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Barang',              'barang'],
            ],
            'Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_barang',       'ID Barang'],
                [SHOW,       REQUIRED, I::TEXT,   'kode_barang',     'Kode Barang'],
                [SHOW,       REQUIRED, I::NAME,   'nama_barang',     'Nama Barang'],
                [SHOW,       REQUIRED, I::SELECT, 'id_satuan',       'Satuan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_jenis_barang', 'Jenis'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'stok',         'Stok'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'stok',         'Stok'],
                [FORM_ONLY,  OPTIONAL, I::NUMBER,   'stok_minimum', 'Stok Minimum'],
                [SHOW,       OPTIONAL, I::MONEY,  'harga_satuan',    'Harga Satuan'],
            ],
        );
    }

    protected array $row_alert = ['value' => 'stok', 'threshold' => 'stok_minimum'];

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $rows = $this->model->builder()
            ->join('inventori_non_medis.satuan', 'inventori_non_medis.satuan.id_satuan = inventori_non_medis.barang.id_satuan', 'left')
            ->select('inventori_non_medis.barang.id_barang, inventori_non_medis.barang.kode_barang, inventori_non_medis.barang.nama_barang, inventori_non_medis.satuan.nama_satuan, inventori_non_medis.barang.stok, inventori_non_medis.barang.harga_satuan')
            ->orderBy('inventori_non_medis.barang.nama_barang', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }

    /** @param array<string, scalar|null> $postData */
    // stok awal = 0
    protected function before_create(array &$postData): void
    {
        $postData['stok'] = 0;
    }
}
