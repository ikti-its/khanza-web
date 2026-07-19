<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

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
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengadaan',                 'ID'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'no_pengajuan',                 'No. Pengajuan'],
                [FORM_ONLY,  REQUIRED, I::SELECT,   'id_pengajuan',                 'Pengajuan'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengadaan',                 'No. Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'total_harga',                  'Total Harga'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'total_harga',                  'Total Harga'],
                [SHOW,       OPTIONAL, I::SELECT,   'id_suplier',                   'Suplier'],
                [SHOW,       REQUIRED, I::DTIME,    'tanggal',                      'Tanggal Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,   'id_status_pengadaan_barang',   'Status'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_status_pengadaan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::TEXT,     'catatan',                      'Catatan'],
            ],
            // child_path: '/inventori-non-medis/detail-pengadaan-barang',
            // child_fk: 'id_pengadaan',
        );
    }

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
    public function list(): \CodeIgniter\HTTP\ResponseInterface
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
            // Return items dari pengadaan (untuk penerimaan)
            $data = $this->get_db()
                ->table('inventori_non_medis.pengadaan_barang_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->select('d.id_barang, b.kode_barang, b.nama_barang, s.nama_satuan, d.qty, d.harga_satuan')
                ->where('d.id_pengadaan', $id_pengadaan)
                ->where('d.id_barang >', 0)
                ->get()->getResultArray();

            return $this->response->setJSON(['data' => $data]);
        }

        // Default: return list pengadaan (untuk modal penerimaan)
        $data = $this->get_db()->query("
            SELECT pb.id_pengadaan, pb.no_pengadaan, TO_CHAR(pb.tanggal, 'YYYY-MM-DD HH24:MI') AS tanggal,
                   s.nama_suplier
            FROM inventori_non_medis.pengadaan_barang pb
            LEFT JOIN inventori_non_medis.suplier s ON pb.id_suplier = s.id_suplier
            WHERE pb.id_status_pengadaan_barang = 1
            ORDER BY pb.no_pengadaan DESC
        ")->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // form tambah: 1-page header + detail preview
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

    // form ubah: 1-page header + detail existing (hanya saat Diproses)
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find_one($id);

        // redirect ke detail jika bukan Diproses (1)
        if (is_array($baris) && (int) ($baris['id_status_pengadaan_barang'] ?? 0) !== 1) {
            return redirect()->to($this->get_uri_path() . '/' . $id);
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

    // simpan header + detail sekaligus
    public function create(): string|RedirectResponse
    {
        $postData = [
            'tanggal'      => $this->request->getPost('tanggal'),
            'id_pengajuan' => $this->request->getPost('id_pengajuan') ?: null,
            'id_suplier'   => $this->request->getPost('id_suplier') ?: null,
            'catatan'      => $this->request->getPost('catatan') ?: null,
        ];

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $postData['no_pengadaan']               = generateNextNoPengadaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_pengadaan_barang'] = 1;

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->insert($postData);
            $id_pengadaan = (int) $db->insertID();

            $detail_ids   = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty   = $this->request->getPost('detail_qty') ?? [];
            $detail_harga = $this->request->getPost('detail_harga') ?? [];
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
                $this->model->update($id_pengadaan, ['total_harga' => $total_harga]);
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
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_pengadaan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Pengadaan yang sudah diproses tidak dapat diubah.');
            return $this->home();
        }

        $postData = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'id_suplier' => $this->request->getPost('id_suplier') ?: null,
            'catatan'    => $this->request->getPost('catatan') ?: null,
        ];

        $db = $this->get_db();

        try {
            $db->transBegin();

            // sync detail
            $db->table('inventori_non_medis.pengadaan_barang_detail')
                ->where('id_pengadaan', (int) $id)
                ->delete();

            $detail_ids   = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty   = $this->request->getPost('detail_qty') ?? [];
            $detail_harga = $this->request->getPost('detail_harga') ?? [];
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
