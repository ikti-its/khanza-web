<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PersetujuanPengajuanBarangDetail;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PersetujuanPengajuanBarangDetailController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PersetujuanPengajuanBarangDetailModel(),
            [
                ['Inventori Non Medis',          'inventori_non_medis'],
                ['Persetujuan Pengajuan Barang', 'persetujuan_pengajuan_barang'],
                ['Detail',                       'detail'],
            ],
            'Persetujuan Pengajuan Barang Detail',
            [
                A::READ,
                A::BACK,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_detail',        'ID Detail'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengajuan',     'ID Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'no_pengajuan',     'No. Pengajuan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'no_pengajuan',     'No. Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang',      'Barang'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang',      'Barang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,     'nama_barang_baru', 'Nama Barang Baru'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_barang_baru', 'Nama Barang Baru'],
                [FORM_ONLY,  OPTIONAL, I::SELECT,   'id_barang',        'Petakan ke Barang (Master)'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'harga',            'Harga/Satuan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'harga',            'Harga/Satuan'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'qty',              'Qty Diajukan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'qty',              'Qty Diajukan'],
                [SHOW,       REQUIRED, I::NUMBER,   'qty_disetujui',    'Qty Disetujui'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'subtotal',         'Subtotal'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'subtotal',         'Subtotal'],
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

    // true jika pengajuan sudah Disetujui (2) atau Ditolak (3) — qty tidak bisa diubah lagi
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
        return is_array($row) && in_array((int) ($row['id_status_pengajuan_barang'] ?? 0), [2, 3], true);
    }

    // jika id_barang sudah terpetakan, tampilkan nama_barang (readonly) bukan SELECT pemetaan
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $data   = $this->model->find_one((int) $id);
        $konfig = $this->get_fields_with_options(false, true);

        if (is_array($data) && (int) ($data['id_barang'] ?? 0) > 0) {
            foreach ($konfig as &$field) {
                /** @var array<int, mixed> $field */
                if (($field[2] ?? '') === 'id_barang') {
                    $field[2] = 'nama_barang';
                    $field[3] = 'readonly';
                    unset($field[5]);
                }
            }
            unset($field);
        }

        return view('/layouts/tambah_ubah', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon', 'Ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig,
            'baris'       => $data,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // hitung ulang subtotal pakai harga existing × qty_disetujui
    // konversi id_barang SELECT kosong (0) → NULL, jangan overwrite jika sudah terpetakan
    #[\Override]
    protected function before_update(array &$postData, int|string $id): void
    {
        $existing = $this->model->find($id);
        if (!is_array($existing))
            return;

        // jika id_barang sudah terpetakan, form tidak mengirim field ini — jangan overwrite
        if ((int) ($existing['id_barang'] ?? 0) > 0) {
            unset($postData['id_barang']);
        } elseif (array_key_exists('id_barang', $postData) && (int) $postData['id_barang'] === 0) {
            // SELECT kosong → NULL (barang belum dipetakan)
            $postData['id_barang'] = null;
        }

        $harga                = (float) ($existing['harga'] ?? 0);
        $qty_disetujui        = (float) ($postData['qty_disetujui'] ?? 0);
        $postData['subtotal'] = $harga * $qty_disetujui;
    }

    // lock check: baris yang belum dipetakan (id_barang IS NULL) dikecualikan dari lock
    // update total setelah simpan
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $row          = $this->model->find($id);
        $id_pengajuan = is_array($row) ? (int) ($row['id_pengajuan'] ?? 0) : 0;
        $is_unmapped  = is_array($row) && $row['id_barang'] === null;

        if ($this->is_locked($id_pengajuan) && !$is_unmapped) {
            $this->home_params = ['id_pengajuan' => $id_pengajuan];
            session()->setFlashdata('error', 'Pengajuan yang sudah diproses tidak dapat diubah detailnya.');
            return $this->home();
        }

        // Catatan: qty_disetujui boleh melebihi qty pengajuan (mis. konsolidasi
        // pembelian untuk stok) — validasi alasan dilakukan di aksi approve
        // pada PersetujuanPengajuanBarangController, bukan di editor per-baris ini.

        $result = parent::update($id);
        if ($result instanceof RedirectResponse && $id_pengajuan > 0) {
            $this->recalculate_total($id_pengajuan);
        }
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
