<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenerimaanBarangController extends ControllerTemplate
{
    private bool $pending_masuk = false;

    public function __construct()
    {
        parent::__construct(
            new PenerimaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Penerimaan Barang',   'penerimaan_barang'],
            ],
            'Penerimaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,    'id_penerimaan',               'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_penerimaan',               'No. Penerimaan'],
                [SHOW, REQUIRED, I::SELECT,   'id_pengadaan',                'No. Pengadaan'],
                [SHOW, REQUIRED, I::DTIME,    'tanggal',                     'Tanggal Penerimaan'],
                [SHOW, OPTIONAL, I::SELECT,   'petugas',                     'Penerima'],
                [SHOW, REQUIRED, I::SELECT,   'id_status_penerimaan_barang', 'Status'],
                [SHOW, OPTIONAL, I::READONLY, 'no_masuk',                    'No. Masuk'],
                [SHOW, OPTIONAL, I::TEXT,     'catatan',                     'Catatan'],
            ],
            // child_path: '/inventori-non-medis/penerimaan-barang-detail',
            // child_fk: 'id_penerimaan',
        );
    }

    protected function before_read(): void
    {
        $this->model->set_order('id_penerimaan', 'DESC');
    }

    // form tambah: 1-page header + detail
    public function create_page(): string
    {
        $options = $this->model->get_all_options();

        return view('admin/inventorinonmedis/tambah_penerimaan_barang', [
            'judul'             => 'Tambah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'        => $this->get_uri_path(),
            'form_action'       => '/submittambah/',
            'options_pengadaan' => $options['id_pengadaan'] ?? [],
        ]);
    }

    // halaman detail (readonly) — view terpisah tanpa form
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.penerimaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty_diterima, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_penerimaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_penerimaan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page header + detail existing
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris   = $this->model->find_one($id);

        // redirect ke detail jika Dikonfirmasi (2) atau Dibatalkan (3)
        if (is_array($baris) && in_array((int) ($baris['id_status_penerimaan_barang'] ?? 0), [2, 3], true)) {
            return redirect()->to($this->get_uri_path() . '/' . $id);
        }

        $options = $this->model->get_all_options();

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.penerimaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->join('inventori_non_medis.pengadaan_barang_detail pbd', "pbd.id_pengadaan = {$this->get_db()->escape((int)($baris['id_pengadaan'] ?? 0))} AND pbd.id_barang = d.id_barang", 'left')
            ->select('d.id_barang, d.qty_diterima, b.kode_barang, b.nama_barang, s.nama_satuan, pbd.qty AS qty_dipesan')
            ->where('d.id_penerimaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/tambah_penerimaan_barang', [
            'judul'             => 'Ubah ' . $this->title,
            'breadcrumbs'       => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'        => $this->get_uri_path(),
            'form_action'       => '/submitedit/' . $id,
            'baris'             => $baris,
            'detail_items'      => $detail_items,
            'options_pengadaan' => $options['id_pengadaan'] ?? [],
            'readonly'          => false,
        ]);
    }

    // simpan header + detail sekaligus
    public function create(): string|RedirectResponse
    {
        $postData = [
            'tanggal'      => $this->request->getPost('tanggal'),
            'id_pengadaan' => $this->request->getPost('id_pengadaan') ?: null,
            'petugas'      => $this->request->getPost('petugas') ?: null,
            'catatan'      => $this->request->getPost('catatan') ?: null,
            'status'       => '-',
        ];

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.penerimaan_barang', 'no_penerimaan', 'id_penerimaan');
        $postData['no_penerimaan']               = generateNextNoPenerimaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_penerimaan_barang'] = 1;

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->insert($postData);
            $id_penerimaan = (int) $db->insertID();

            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty = $this->request->getPost('detail_qty') ?? [];

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang    = (int) ($detail_ids[$i] ?? 0);
                $qty_diterima = (int) ($detail_qty[$i] ?? 0);
                if ($id_barang > 0) {
                    $db->table('inventori_non_medis.penerimaan_barang_detail')->insert([
                        'id_penerimaan' => $id_penerimaan,
                        'id_barang'     => $id_barang,
                        'qty_diterima'  => $qty_diterima,
                    ]);
                }
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data Penerimaan Barang berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail + handle status transitions
    public function update(int|string $id): string|RedirectResponse
    {
        $current        = $this->model->find((int) $id);
        $current_status = is_array($current) ? (int) ($current['id_status_penerimaan_barang'] ?? 0) : 0;

        if (in_array($current_status, [2, 3], true)) {
            session()->setFlashdata('error', 'Penerimaan barang yang sudah dikonfirmasi atau dibatalkan tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_penerimaan_barang') ?? 1);

        $postData = [
            'tanggal'                     => $this->request->getPost('tanggal'),
            'petugas'                     => $this->request->getPost('petugas') ?: null,
            'catatan'                     => $this->request->getPost('catatan') ?: null,
            'id_status_penerimaan_barang' => $new_status,
        ];

        // Generate no_masuk saat dikonfirmasi
        if ($new_status === 2 && $current_status !== 2) {
            helper('autonomor');
            $lastNo             = $this->get_last('inventori_non_medis.penerimaan_barang', 'no_masuk', 'id_penerimaan');
            $postData['no_masuk'] = generateNextNoMasukBarang($lastNo);
            $this->pending_masuk  = true;
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            // sync detail
            $db->table('inventori_non_medis.penerimaan_barang_detail')
                ->where('id_penerimaan', (int) $id)
                ->delete();

            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty = $this->request->getPost('detail_qty') ?? [];

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang    = (int) ($detail_ids[$i] ?? 0);
                $qty_diterima = (int) ($detail_qty[$i] ?? 0);
                if ($id_barang > 0) {
                    $db->table('inventori_non_medis.penerimaan_barang_detail')->insert([
                        'id_penerimaan' => (int) $id,
                        'id_barang'     => $id_barang,
                        'qty_diterima'  => $qty_diterima,
                    ]);
                }
            }

            // validasi sebelum dikonfirmasi
            if ($new_status === 2) {
                $has_items = $db->table('inventori_non_medis.penerimaan_barang_detail')
                    ->where('id_penerimaan', (int) $id)
                    ->where('qty_diterima >', 0)
                    ->countAllResults() > 0;
                if (!$has_items) {
                    $db->transRollback();
                    session()->setFlashdata('error', 'Isi qty diterima minimal untuk satu item sebelum mengkonfirmasi.');
                    return $this->home();
                }
            }

            $this->model->update($id, $postData);

            $db->transCommit();

            // buat transaksi stok masuk setelah commit
            if ($this->pending_masuk) {
                $this->pending_masuk = false;
                $saved = $this->model->find((int) $id);
                if (is_array($saved) && !empty($saved['no_masuk'])) {
                    try {
                        $this->create_transaksi_stok_masuk((int) $id, (string) $saved['no_masuk']);
                        $this->update_pengadaan_status((int) ($saved['id_pengadaan'] ?? 0));
                    } catch (\Throwable $e) {
                        log_message('error', '[Penerimaan] transaksi stok: ' . $e->getMessage());
                        session()->setFlashdata('error', 'Status berhasil diubah, namun gagal membuat transaksi stok: ' . $e->getMessage());
                        return $this->home();
                    }
                }
            }

            session()->setFlashdata('success', 'Data Penerimaan Barang berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
        }

        return $this->home();
    }

    // catat transaksi masuk + tambah stok barang
    private function create_transaksi_stok_masuk(int $id, string $no_masuk): void
    {
        $db = $this->get_db();

        $row = $db->table('inventori_non_medis.penerimaan_barang pb')
            ->join('inventori_non_medis.pengadaan_barang pg', 'pb.id_pengadaan = pg.id_pengadaan', 'left')
            ->join('inventori_non_medis.suplier s', 'pg.id_suplier = s.id_suplier', 'left')
            ->join('role.petugas pt', 'pb.petugas = pt.id_petugas', 'left')
            ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
            ->select('s.nama_suplier, pb.no_penerimaan, o.nama')
            ->where('pb.id_penerimaan', $id)
            ->get()->getRowArray();

        $keterangan = trim(implode(', ', array_filter([
            $row['no_penerimaan'] ?? '',
            ($row['nama_suplier'] ?? '') !== '' ? 'Suplier ' . $row['nama_suplier'] : '',
            ($row['nama'] ?? '') !== '' ? 'Penerima: ' . $row['nama'] : '',
        ])));

        $details = $db->table('inventori_non_medis.penerimaan_barang_detail pbd')
            ->join('inventori_non_medis.barang b', 'pbd.id_barang = b.id_barang', 'left')
            ->select('pbd.id_barang, pbd.qty_diterima, pbd.harga_satuan, b.stok')
            ->where('pbd.id_penerimaan', $id)
            ->where('pbd.id_barang >', 0)
            ->where('pbd.qty_diterima >', 0)
            ->get()->getResultArray();

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 1,
            'tanggal'                => $now,
            'id_penerimaan'          => $id,
            'keterangan'             => $keterangan,
        ]);
        $id_transaksi = (int) $db->insertID();

        foreach ($details as $d) {
            $qty          = (int) round((float) $d['qty_diterima']);
            $stok_sebelum = (int) ($d['stok'] ?? 0);
            $db->table('inventori_non_medis.transaksi_stok_detail')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang'    => (int) $d['id_barang'],
                'qty'          => $qty,
                'harga_satuan' => isset($d['harga_satuan']) && (float) $d['harga_satuan'] > 0 ? $d['harga_satuan'] : null,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sebelum + $qty,
            ]);
            $db->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', 'stok + ' . $qty, false)
                ->update();
        }

        $db->transCommit();
    }

    // kalau semua item sudah diterima penuh, set status pengadaan → Diterima (2)
    private function update_pengadaan_status(int $id_pengadaan): void
    {
        if ($id_pengadaan === 0) return;
        $db = $this->get_db();

        $ordered = $db->table('inventori_non_medis.pengadaan_barang_detail')
            ->select('id_barang, qty')
            ->where('id_pengadaan', $id_pengadaan)
            ->where('id_barang >', 0)
            ->get()->getResultArray();

        if (empty($ordered)) return;

        foreach ($ordered as $item) {
            $total_received = (float) ($db->table('inventori_non_medis.penerimaan_barang pb')
                ->join('inventori_non_medis.penerimaan_barang_detail pbd', 'pb.id_penerimaan = pbd.id_penerimaan', 'left')
                ->selectSum('pbd.qty_diterima', 'total')
                ->where('pb.id_pengadaan', $id_pengadaan)
                ->where('pb.id_status_penerimaan_barang', 2)
                ->where('pbd.id_barang', (int) $item['id_barang'])
                ->get()->getRowArray()['total'] ?? 0);

            if ($total_received < (float) $item['qty']) return;
        }

        $db->table('inventori_non_medis.pengadaan_barang')
            ->where('id_pengadaan', $id_pengadaan)
            ->set('id_status_pengadaan_barang', 2)
            ->update();
    }
}
