<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PengadaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengadaan Barang',    'pengadaan_barang'],
            ],
            'Pengadaan Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengadaan',                 'ID'],
                [HIDE,       OPTIONAL, I::READONLY, 'no_pengajuan',                 'No. Pengajuan'],
                [FORM_ONLY,  REQUIRED, I::SELECT,   'id_pengajuan',                 'Pengajuan'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengadaan',                 'No. Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'total_harga',                  'Total Harga'],
                [SHOW,       OPTIONAL, I::SELECT,   'id_suplier',                   'Suplier'],
                [SHOW,       REQUIRED, I::DTIME,    'tanggal',                      'Tanggal Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,   'id_status_pengadaan_barang',   'Status'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,     'catatan',                      'Catatan'],
            ],
            // child_path: '/inventori-non-medis/detail-pengadaan-barang',
            // child_fk: 'id_pengadaan',
        );
    }

    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('id_pengadaan', 'DESC');
    }

    /**
     * Endpoint modal/list:
     * - Tanpa param: return list pengadaan (untuk modal penerimaan)
     * - Dengan ?id_pengajuan=X: return detail items dari pengajuan (untuk preview saat buat pengadaan)
     * - Dengan ?id_pengadaan=X: return detail items dari pengadaan (untuk preview saat buat penerimaan)
     */
    public function list(): ResponseInterface
    {
        $id_pengajuan = (int) ($this->request->getGet('id_pengajuan') ?? 0);
        $id_pengadaan = (int) ($this->request->getGet('id_pengadaan') ?? 0);

        if ($id_pengajuan > 0) {
            // Return items dari pengajuan beserta sisa qty yang belum dipesan
            $data = $this->get_db()->query("
                SELECT
                    pjd.id_barang,
                    b.kode_barang,
                    b.nama_barang,
                    s.nama_satuan,
                    pjd.qty_disetujui,
                    pjd.harga,
                    COALESCE((
                        SELECT SUM(pbd.qty)
                        FROM inventori_non_medis.pengadaan_barang_detail pbd
                        JOIN inventori_non_medis.pengadaan_barang pb ON pbd.id_pengadaan = pb.id_pengadaan
                        WHERE pb.id_pengajuan = ? AND pbd.id_barang = pjd.id_barang
                    ), 0) AS qty_sudah_dipesan
                FROM inventori_non_medis.pengajuan_barang_detail pjd
                JOIN inventori_non_medis.barang b ON pjd.id_barang = b.id_barang
                LEFT JOIN inventori_non_medis.satuan s ON b.id_satuan = s.id_satuan
                WHERE pjd.id_pengajuan = ?
                  AND pjd.id_barang > 0
                  AND pjd.qty_disetujui > 0
                ORDER BY b.nama_barang ASC
            ", [$id_pengajuan, $id_pengajuan])->getResultArray();

            return $this->response->setJSON(['data' => $data]);
        }

        if ($id_pengadaan > 0) {
            // Return items dari pengadaan (untuk penerimaan) dengan info sudah diterima
            $data = $this->get_db()->query("
                SELECT d.id_barang, b.kode_barang, b.nama_barang, s.nama_satuan,
                       d.qty, d.harga_satuan,
                       COALESCE((
                           SELECT SUM(prbd.qty_diterima)
                           FROM inventori_non_medis.penerimaan_barang_detail prbd
                           JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                           WHERE prb.id_pengadaan = d.id_pengadaan
                             AND prbd.id_barang = d.id_barang
                             AND prb.id_status_penerimaan_barang = 2
                       ), 0) AS sudah_diterima
                FROM inventori_non_medis.pengadaan_barang_detail d
                LEFT JOIN inventori_non_medis.barang b ON d.id_barang = b.id_barang
                LEFT JOIN inventori_non_medis.satuan s ON b.id_satuan = s.id_satuan
                WHERE d.id_pengadaan = ?
                  AND d.id_barang > 0
                ORDER BY b.nama_barang ASC
            ", [$id_pengadaan])->getResultArray();

            // Hitung sisa per item
            foreach ($data as &$row) {
                $row['sisa'] = max(0, (int) $row['qty'] - (int) $row['sudah_diterima']);
            }

            return $this->response->setJSON(['data' => $data]);
        }

        // Default: return list pengadaan (untuk modal penerimaan)
        $mode = $this->request->getGet('mode') ?? '';

        if ($mode === 'available') {
            // Hanya pengadaan yang masih punya sisa qty belum diterima (status Proses/Selesai, bukan Dibatalkan)
            $data = $this->get_db()->query("
                SELECT pb.id_pengadaan, pb.no_pengadaan,
                       TO_CHAR(pb.tanggal, 'YYYY-MM-DD HH24:MI') AS tanggal,
                       COALESCE(s.nama_suplier, '-') AS nama_suplier,
                       pb.total_harga
                FROM inventori_non_medis.pengadaan_barang pb
                LEFT JOIN inventori_non_medis.suplier s ON pb.id_suplier = s.id_suplier
                WHERE pb.id_status_pengadaan_barang IN (1, 2)
                  AND EXISTS (
                    SELECT 1 FROM inventori_non_medis.pengadaan_barang_detail pbd
                    WHERE pbd.id_pengadaan = pb.id_pengadaan
                      AND pbd.id_barang > 0
                      AND pbd.qty > 0
                      AND (
                        SELECT COALESCE(SUM(prbd.qty_diterima), 0)
                        FROM inventori_non_medis.penerimaan_barang_detail prbd
                        JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                        WHERE prb.id_pengadaan = pbd.id_pengadaan
                          AND prbd.id_barang = pbd.id_barang
                          AND prb.id_status_penerimaan_barang = 2
                      ) < pbd.qty
                  )
                ORDER BY pb.id_pengadaan DESC
            ")->getResultArray();
        } else {
            $data = $this->get_db()->query("
                SELECT pb.id_pengadaan, pb.no_pengadaan,
                       TO_CHAR(pb.tanggal, 'YYYY-MM-DD HH24:MI') AS tanggal,
                       COALESCE(s.nama_suplier, '-') AS nama_suplier,
                       pb.total_harga
                FROM inventori_non_medis.pengadaan_barang pb
                LEFT JOIN inventori_non_medis.suplier s ON pb.id_suplier = s.id_suplier
                WHERE pb.id_status_pengadaan_barang IN (1, 2)
                ORDER BY pb.id_pengadaan DESC
            ")->getResultArray();
        }

        // Format total_harga for display
        foreach ($data as &$row) {
            $row['total_harga_fmt'] = 'Rp ' . number_format((float) ($row['total_harga'] ?? 0), 0, ',', '.');
        }

        return $this->response->setJSON(['data' => $data]);
    }

    // form tambah: 1-page header + detail preview
    #[\Override]
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_pengadaan_barang', [
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
            ->table('inventori_non_medis.pengadaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.harga_satuan, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengadaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_pengadaan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page header + detail existing (hanya saat Proses Pengadaan)
    #[\Override]
    public function update_page(int|string $id): string
    {
        $baris = $this->model->find_one($id);

        // redirect ke detail jika bukan Proses Pengadaan (1)
        if (is_array($baris) && (int) ($baris['id_status_pengadaan_barang'] ?? 0) !== 1) {
            return $this->detail($id);
        }

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.pengadaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.harga_satuan, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengadaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/tambah_pengadaan_barang', [
            'judul'        => 'Ubah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submitedit/' . $id,
            'baris'        => $baris,
            'detail_items' => $detail_items,
            'readonly'     => false,
        ]);
    }

    // hapus header + detail sekaligus
    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_pengadaan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Pengadaan yang sudah diproses tidak dapat dihapus.');
            return $this->home();
        }

        $db = $this->get_db();
        try {
            $db->transBegin();
            $db->table('inventori_non_medis.pengadaan_barang_detail')->where('id_pengadaan', (int) $id)->delete();
            $this->model->delete($id);
            $db->transCommit();
            session()->setFlashdata('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return $this->home();
    }

    // Qty pengadaan tidak boleh melebihi sisa qty_disetujui di Pengajuan terkait
    // (dikurangi yang sudah dipesan di pengadaan lain untuk pengajuan yang sama).
    // Pengadaan murni tahap eksekusi — kenaikan qty hanya sah lewat approval Pengajuan.
    private function validate_qty_terhadap_pengajuan(int $id_pengajuan, array $detail_ids, array $detail_qty, int $exclude_id_pengadaan = 0): ?string
    {
        if ($id_pengajuan <= 0) return null;

        $db = $this->get_db();
        for ($i = 0; $i < count($detail_ids); $i++) {
            $id_barang = (int) ($detail_ids[$i] ?? 0);
            $qty       = (int) ($detail_qty[$i] ?? 0);
            if ($id_barang <= 0 || $qty <= 0) continue;

            $pjd = $db->table('inventori_non_medis.pengajuan_barang_detail pjd')
                ->join('inventori_non_medis.barang b', 'pjd.id_barang = b.id_barang', 'left')
                ->select('pjd.qty_disetujui, b.nama_barang')
                ->where('pjd.id_pengajuan', $id_pengajuan)
                ->where('pjd.id_barang', $id_barang)
                ->get()->getRowArray();

            $qty_disetujui = (int) ($pjd['qty_disetujui'] ?? 0);

            $sudah_dipesan = (int) ($db->table('inventori_non_medis.pengadaan_barang_detail pbd')
                ->join('inventori_non_medis.pengadaan_barang pb', 'pbd.id_pengadaan = pb.id_pengadaan')
                ->selectSum('pbd.qty', 'total')
                ->where('pb.id_pengajuan', $id_pengajuan)
                ->where('pbd.id_barang', $id_barang)
                ->where('pb.id_pengadaan !=', $exclude_id_pengadaan)
                ->get()->getRowArray()['total'] ?? 0);

            $sisa = max(0, $qty_disetujui - $sudah_dipesan);

            if ($qty > $sisa) {
                $nama = $pjd['nama_barang'] ?? "Barang #{$id_barang}";
                return "Qty pengadaan untuk {$nama} ({$qty}) melebihi sisa yang disetujui di pengajuan (sisa: {$sisa}). Jika butuh lebih, naikkan qty disetujui di Pengajuan terlebih dahulu.";
            }
        }

        return null;
    }

    // simpan header + detail sekaligus
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $new_status = (int) ($this->request->getPost('id_status_pengadaan_barang') ?? 1);

        $id_pengajuan_raw = $this->request->getPost('id_pengajuan');
        $id_pengajuan     = (int) ($id_pengajuan_raw ?: 0);
        $detail_ids       = $this->request->getPost('detail_id_barang') ?? [];
        $detail_qty       = $this->request->getPost('detail_qty') ?? [];
        $detail_harga     = $this->request->getPost('detail_harga') ?? [];

        $qty_error = $this->validate_qty_terhadap_pengajuan($id_pengajuan, $detail_ids, $detail_qty);
        if ($qty_error !== null) {
            session()->setFlashdata('error', $qty_error);
            return redirect()->back();
        }

        $postData = [
            'tanggal'      => $this->request->getPost('tanggal'),
            'id_pengajuan' => $id_pengajuan_raw ?: null,
            'id_suplier'   => ((int) ($this->request->getPost('id_suplier') ?? 0)) > 0 ? (int) $this->request->getPost('id_suplier') : 0,
            'catatan'      => $this->request->getPost('catatan') ?: null,
        ];

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $postData['no_pengadaan']               = generateNextNoPengadaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_pengadaan_barang'] = $new_status;

        $db = $this->get_db();

        try {
            $db->transBegin();

            $inserted = $this->model->insert($postData);
            if ($inserted === false) {
                $db->transRollback();
                session()->setFlashdata('error', implode(' ', $this->model->errors()));
                return $this->home();
            }
            $id_pengadaan = (int) $db->insertID();

            // Simpan detail item (termasuk saat Dibatalkan — item tetap dipertahankan)
            $total_harga  = 0;

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang    = (int) ($detail_ids[$i] ?? 0);
                $qty          = (int) ($detail_qty[$i] ?? 0);
                $harga_satuan = (float) ($detail_harga[$i] ?? 0);
                if ($id_barang > 0 && $qty > 0) {
                    $subtotal = $qty * $harga_satuan;
                    $total_harga += $subtotal;
                    $db->table('inventori_non_medis.pengadaan_barang_detail')->insert([
                        'id_pengadaan' => $id_pengadaan,
                        'id_barang'    => $id_barang,
                        'qty'          => $qty,
                        'harga_satuan' => $harga_satuan > 0 ? $harga_satuan : null,
                        'subtotal'     => $subtotal > 0 ? $subtotal : null,
                    ]);
                }
            }

            if ($total_harga > 0) {
                $db->table('inventori_non_medis.pengadaan_barang')
                    ->where('id_pengadaan', $id_pengadaan)
                    ->update(['total_harga' => $total_harga]);
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data Pengadaan Barang berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && !in_array((int) ($current['id_status_pengadaan_barang'] ?? 0), [1], true)) {
            session()->setFlashdata('error', 'Pengadaan yang sudah selesai atau dibatalkan tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_pengadaan_barang') ?? 1);

        $id_pengajuan = (int) ($current['id_pengajuan'] ?? 0);
        $detail_ids   = $this->request->getPost('detail_id_barang') ?? [];
        $detail_qty   = $this->request->getPost('detail_qty') ?? [];
        $detail_harga = $this->request->getPost('detail_harga') ?? [];

        if ($new_status !== 3) {
            $qty_error = $this->validate_qty_terhadap_pengajuan($id_pengajuan, $detail_ids, $detail_qty, (int) $id);
            if ($qty_error !== null) {
                session()->setFlashdata('error', $qty_error);
                return redirect()->back();
            }
        }

        $postData = [
            'tanggal'                    => $this->request->getPost('tanggal'),
            'id_suplier'                 => ((int) ($this->request->getPost('id_suplier') ?? 0)) > 0 ? (int) $this->request->getPost('id_suplier') : 0,
            'catatan'                    => $this->request->getPost('catatan') ?: null,
            'id_status_pengadaan_barang' => $new_status,
        ];

        $db = $this->get_db();

        try {
            $db->transBegin();

            // Jika Dibatalkan (3), hanya update header — pertahankan detail item
            if ($new_status === 3) {
                // detail tetap ada, tidak dihapus
            } else {
                // sync detail
                $db->table('inventori_non_medis.pengadaan_barang_detail')
                    ->where('id_pengadaan', (int) $id)
                    ->delete();

                $total_harga  = 0;

                for ($i = 0; $i < count($detail_ids); $i++) {
                    $id_barang    = (int) ($detail_ids[$i] ?? 0);
                    $qty          = (int) ($detail_qty[$i] ?? 0);
                    $harga_satuan = (float) ($detail_harga[$i] ?? 0);
                    if ($id_barang > 0 && $qty > 0) {
                        $subtotal = $qty * $harga_satuan;
                        $total_harga += $subtotal;
                        $db->table('inventori_non_medis.pengadaan_barang_detail')->insert([
                            'id_pengadaan' => (int) $id,
                            'id_barang'    => $id_barang,
                            'qty'          => $qty,
                            'harga_satuan' => $harga_satuan > 0 ? $harga_satuan : null,
                            'subtotal'     => $subtotal > 0 ? $subtotal : null,
                        ]);
                    }
                }

                $postData['total_harga'] = $total_harga > 0 ? $total_harga : null;
            }

            $this->model->update($id, $postData);

            $db->transCommit();
            session()->setFlashdata('success', 'Data Pengadaan Barang berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
        }

        return $this->home();
    }

    // cetak surat pemesanan
    #[\Override]
    public function print(int|string $id): string
    {
        $db = $this->get_db();

        $header = $db->table('inventori_non_medis.pengadaan_barang pb')
            ->join('inventori_non_medis.suplier s', 'pb.id_suplier = s.id_suplier', 'left')
            ->join('finansial.rekening r', 's.id_rekening = r.id_rekening', 'left')
            ->join('finansial.bank bk', 'r.bank = bk.id_bank', 'left')
            ->join('inventori_non_medis.pengajuan_barang pj', 'pb.id_pengajuan = pj.id_pengajuan', 'left')
            ->join('role.petugas pt', 'pj.petugas_gudang = pt.id_petugas', 'left')
            ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
            ->select('pb.no_pengadaan, pb.tanggal, pb.catatan,
                      s.nama_suplier, s.no_telp, s.alamat,
                      r.nomor_rekening, r.nama_akun, bk.nama_bank,
                      pj.no_pengajuan, o.nama AS nama_petugas')
            ->where('pb.id_pengadaan', (int) $id)
            ->get()->getRowArray();

        if (!is_array($header)) {
            return $this->index();
        }

        $items = $db->table('inventori_non_medis.pengadaan_barang_detail pbd')
            ->join('inventori_non_medis.barang b', 'pbd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan sat', 'b.id_satuan = sat.id_satuan', 'left')
            ->select('b.nama_barang, sat.nama_satuan, pbd.qty, pbd.harga_satuan')
            ->where('pbd.id_pengadaan', (int) $id)
            ->where('pbd.id_barang >', 0)
            ->get()->getResultArray();

        return view('components/cetak/cetak_surat_pemesanan', [
            'header' => $header,
            'items'  => $items,
        ]);
    }
}
