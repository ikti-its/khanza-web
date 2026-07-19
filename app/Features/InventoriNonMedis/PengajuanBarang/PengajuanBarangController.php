<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengajuanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengajuan Barang',    'pengajuan_barang'],
            ],
            'Pengajuan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengajuan',               'ID'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_permintaan',              'ID Permintaan'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengajuan',               'No. Pengajuan'],
                [SHOW,       REQUIRED, I::DTIME,    'tanggal',                    'Tanggal Pengajuan'],
                [SHOW,       REQUIRED, I::SELECT,   'petugas_gudang',             'Pemohon'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'total_harga',                'Total Harga'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'total_harga',                'Total Harga'],
                [SHOW,       OPTIONAL, I::SELECT,   'id_status_pengajuan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal_diproses',           'Tanggal Diproses'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal_diproses',           'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'atasan_logistik',            'Pengelola'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'atasan_logistik_nama',       'Pengelola'],
            ],
            // child_path: '/inventori-non-medis/detail-pengajuan-barang',
            // child_fk: 'id_pengajuan',
        );
    }

    protected function before_read(): void
    {
        $this->model->set_order('id_pengajuan', 'DESC');
    }

    // endpoint modal: pengajuan yang sudah disetujui dan masih punya sisa qty
    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->get_db()->query("
            SELECT DISTINCT pj.id_pengajuan, pj.no_pengajuan, TO_CHAR(pj.tanggal, 'YYYY-MM-DD HH24:MI') AS tanggal, o.nama
            FROM inventori_non_medis.pengajuan_barang pj
            JOIN inventori_non_medis.pengajuan_barang_detail pjd ON pj.id_pengajuan = pjd.id_pengajuan
            LEFT JOIN role.petugas pt ON pj.petugas_gudang = pt.id_petugas
            LEFT JOIN person.orang o ON pt.id_orang = o.id_orang
            WHERE pjd.id_barang > 0
              AND pjd.qty_disetujui > 0
              AND pj.id_status_pengajuan_barang = 2
              AND (
                SELECT COALESCE(SUM(pbd.qty), 0)
                FROM inventori_non_medis.pengadaan_barang_detail pbd
                JOIN inventori_non_medis.pengadaan_barang pb ON pbd.id_pengadaan = pb.id_pengadaan
                WHERE pb.id_pengajuan = pjd.id_pengajuan
                  AND pbd.id_barang = pjd.id_barang
              ) < pjd.qty_disetujui
              AND NOT EXISTS (
                SELECT 1
                FROM inventori_non_medis.pengajuan_barang_detail unmapped
                WHERE unmapped.id_pengajuan = pjd.id_pengajuan
                  AND unmapped.id_barang IS NULL
                  AND unmapped.qty_disetujui > 0
              )
            ORDER BY pj.no_pengajuan DESC
        ")->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // form tambah: 1-page header + detail
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_pengajuan_barang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submittambah/',
        ]);
    }

    // halaman detail (readonly) — view terpisah tanpa form
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.pengajuan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.harga, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengajuan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_pengajuan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page header + detail existing (hanya saat Draf)
    public function update_page(int|string $id): string
    {
        $baris = $this->model->find_one($id);

        // redirect ke detail jika bukan Draf
        if (is_array($baris) && (int) ($baris['id_status_pengajuan_barang'] ?? 0) !== 1) {
            return $this->detail($id);
        }

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.pengajuan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_detail, d.id_barang, d.qty, d.harga, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengajuan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/tambah_pengajuan_barang', [
            'judul'        => 'Ubah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submitedit/' . $id,
            'baris'        => $baris,
            'detail_items' => $detail_items,
            'readonly'     => false,
        ]);
    }

    // simpan header + detail sekaligus
    public function create(): string|RedirectResponse
    {
        $postData = [
            'tanggal'        => $this->request->getPost('tanggal'),
            'petugas_gudang' => $this->request->getPost('petugas_gudang') ?: null,
        ];

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengajuan_barang', 'no_pengajuan', 'id_pengajuan');
        $postData['no_pengajuan']               = generateNextNoPengajuanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_pengajuan_barang'] = (int) ($this->request->getPost('id_status_pengajuan_barang') ?? 1);

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->insert($postData);
            $id_pengajuan = (int) $db->insertID();

            $detail_ids   = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty   = $this->request->getPost('detail_qty') ?? [];
            $detail_harga = $this->request->getPost('detail_harga') ?? [];
            $total_harga  = 0;

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang = (int) ($detail_ids[$i] ?? 0);
                $qty       = (int) ($detail_qty[$i] ?? 0);
                $harga     = (float) ($detail_harga[$i] ?? 0);
                if ($id_barang > 0 && $qty > 0) {
                    $subtotal = $qty * $harga;
                    $total_harga += $subtotal;
                    $db->table('inventori_non_medis.pengajuan_barang_detail')->insert([
                        'id_pengajuan' => $id_pengajuan,
                        'id_barang'    => $id_barang,
                        'qty'          => $qty,
                        'harga'        => $harga > 0 ? $harga : null,
                        'subtotal'     => $subtotal > 0 ? $subtotal : null,
                    ]);
                }
            }

            // update total_harga di header
            if ($total_harga > 0) {
                $db->table('inventori_non_medis.pengajuan_barang')
                    ->where('id_pengajuan', $id_pengajuan)
                    ->update(['total_harga' => $total_harga]);
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data Pengajuan Barang berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return $this->home();
    }

    // hapus header + detail sekaligus
    public function delete(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_pengajuan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat dihapus.');
            return $this->home();
        }

        $db = $this->get_db();
        try {
            $db->transBegin();
            $db->table('inventori_non_medis.pengajuan_barang_detail')->where('id_pengajuan', (int) $id)->delete();
            $this->model->delete($id);
            $db->transCommit();
            session()->setFlashdata('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_pengajuan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat diubah.');
            return $this->home();
        }

        $postData = [
            'tanggal'                     => $this->request->getPost('tanggal'),
            'petugas_gudang'              => $this->request->getPost('petugas_gudang') ?: null,
            'id_status_pengajuan_barang'  => $this->request->getPost('id_status_pengajuan_barang') ?? 1,
        ];

        $new_status = (int) ($postData['id_status_pengajuan_barang'] ?? 0);
        if (!in_array($new_status, [1, 4], true)) {
            $postData['id_status_pengajuan_barang'] = (int) ($current['id_status_pengajuan_barang'] ?? 1);
        }

        $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
        if ($new_status === 4 && empty($detail_ids)) {
            session()->setFlashdata('error', 'Tambahkan detail barang terlebih dahulu sebelum mengajukan.');
            return $this->home();
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            // sync detail: hapus semua lalu insert ulang
            $db->table('inventori_non_medis.pengajuan_barang_detail')
                ->where('id_pengajuan', (int) $id)
                ->delete();

            $detail_qty   = $this->request->getPost('detail_qty') ?? [];
            $detail_harga = $this->request->getPost('detail_harga') ?? [];
            $total_harga  = 0;

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang = (int) ($detail_ids[$i] ?? 0);
                $qty       = (int) ($detail_qty[$i] ?? 0);
                $harga     = (float) ($detail_harga[$i] ?? 0);
                if ($id_barang > 0 && $qty > 0) {
                    $subtotal = $qty * $harga;
                    $total_harga += $subtotal;
                    $db->table('inventori_non_medis.pengajuan_barang_detail')->insert([
                        'id_pengajuan' => (int) $id,
                        'id_barang'    => $id_barang,
                        'qty'          => $qty,
                        'harga'        => $harga > 0 ? $harga : null,
                        'subtotal'     => $subtotal > 0 ? $subtotal : null,
                    ]);
                }
            }

            $postData['total_harga'] = $total_harga > 0 ? $total_harga : null;
            $this->model->update($id, $postData);

            $db->transCommit();
            session()->setFlashdata('success', 'Data Pengajuan Barang berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
        }

        return $this->home();
    }
}
