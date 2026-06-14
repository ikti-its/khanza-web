<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpnameDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class StokOpnameDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokOpnameDetailModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Stok Opname',         'stok_opname'],
                ['Detail',              'detail'],
            ],
            'Detail Stok Opname',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',   'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_opname',   'ID Opname'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang', 'Barang'],
                [FORM_ONLY,  REQUIRED, I::MODAL,    'id_barang',   'Barang',  ['modal' => 'modalBarang', 'display_column' => 'nama_barang', 'placeholder' => 'Klik cari barang...']],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'kode_barang', 'Kode Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_satuan', 'Satuan'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'stok_sistem', 'Stok Sistem'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'stok_sistem', 'Stok Sistem'],
                [SHOW,       REQUIRED, I::NUMBER,   'stok_fisik',  'Stok Fisik'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'selisih',     'Selisih'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'selisih',     'Selisih'],
                [SHOW,       OPTIONAL, I::TEXT,     'catatan',     'Catatan'],
            ],
            parent_fk: 'id_opname',
        );
    }

    // ambil status opname induk
    private function get_parent_status(int $id_opname): int
    {
        $row = $this->get_db()
            ->table('inventori_non_medis.stok_opname')
            ->select('id_status_stok_opname')
            ->where('id_opname', $id_opname)
            ->get()->getRowArray();
        return (int) ($row['id_status_stok_opname'] ?? 0);
    }

    // blok kalau opname induk udah Selesai, atau barang sudah ada
    public function create(): string|RedirectResponse
    {
        $id_opname = (int) ($this->request->getPost('id_opname') ?? 0);
        if ($this->get_parent_status($id_opname) === 2) {
            $this->home_params = ['id_opname' => $id_opname];
            session()->setFlashdata('error', 'Tidak dapat menambah detail karena Stok Opname sudah Selesai.');
            return $this->home();
        }

        $id_barang = (int) ($this->request->getPost('id_barang') ?? 0);
        if ($id_barang > 0 && $id_opname > 0) {
            $exists = $this->get_db()
                ->table('inventori_non_medis.stok_opname_detail')
                ->where('id_opname', $id_opname)
                ->where('id_barang', $id_barang)
                ->countAllResults();
            if ($exists > 0) {
                $this->home_params = ['id_opname' => $id_opname];
                session()->setFlashdata('error', 'Barang tersebut sudah ada pada stok opname ini. Ubah data yang sudah ada jika ingin mengubah jumlah stoknya.');
                return $this->home();
            }
        }

        return parent::create();
    }

    // ambil stok_sistem dari barang, hitung selisih
    protected function before_create(array &$postData): void
    {
        $id_barang   = (int) ($postData['id_barang'] ?? 0);
        $stok_sistem = 0;

        if ($id_barang > 0) {
            $row = $this->get_db()
                ->table('inventori_non_medis.barang')
                ->select('stok')
                ->where('id_barang', $id_barang)
                ->get()->getRowArray();
            $stok_sistem = (int) ($row['stok'] ?? 0);
        }

        $postData['stok_sistem'] = $stok_sistem;
        $postData['selisih']     = (int) ($postData['stok_fisik'] ?? 0) - $stok_sistem;
    }

    // blok kalau opname induk udah Selesai
    public function update(int|string $id): string|RedirectResponse
    {
        $row       = $this->model->find((int) $id);
        $id_opname = (int) (is_array($row) ? ($row['id_opname'] ?? 0) : 0);

        if ($this->get_parent_status($id_opname) === 2) {
            $this->home_params = ['id_opname' => $id_opname];
            session()->setFlashdata('error', 'Tidak dapat mengubah detail karena Stok Opname sudah Selesai.');
            return $this->home();
        }

        return parent::update($id);
    }

    // pakai stok_sistem lama, hitung ulang selisih
    protected function before_update(array &$postData, int|string $id): void
    {
        $row         = $this->model->find((int) $id);
        $stok_sistem = (int) (is_array($row) ? ($row['stok_sistem'] ?? 0) : 0);

        $postData['stok_sistem'] = $stok_sistem;
        $postData['selisih']     = (int) ($postData['stok_fisik'] ?? 0) - $stok_sistem;
    }
}
