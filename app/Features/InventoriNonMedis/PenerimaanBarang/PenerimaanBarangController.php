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
                [HIDE,      OPTIONAL, I::INDEX,    'id_penerimaan',               'ID'],
                [SHOW,      OPTIONAL, I::READONLY, 'no_penerimaan',               'No. Penerimaan'],
                [SHOW,      REQUIRED, I::SELECT,   'id_pengadaan',                'No. Pengadaan'],
                [SHOW,      REQUIRED, I::DTIME,    'tanggal',                     'Tanggal Penerimaan'],
                [FORM_ONLY, OPTIONAL, I::SELECT,   'petugas',                     'Penerima'],
                [SHOW,      REQUIRED, I::SELECT,   'id_status_penerimaan_barang', 'Status'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'no_masuk',                    'No. Masuk'],
                [FORM_ONLY, OPTIONAL, I::TEXT,     'catatan',                     'Catatan'],
            ],
            // child_path: '/inventori-non-medis/penerimaan-barang-detail',
            // child_fk: 'id_penerimaan',
        );
    }

    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('id_penerimaan', 'DESC');
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    // form tambah: 1-page header + detail
    #[\Override]
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_penerimaan_barang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submittambah/',
        ]);
    }

    // halaman detail (readonly) — view terpisah tanpa form
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function detail(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.penerimaan_barang_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->select('d.id_barang, d.qty_diterima, b.kode_barang, b.nama_barang, s.nama_satuan')
                ->where('d.id_penerimaan', (int) $id)
                ->where('d.id_barang >', 0)
                ->get(),
        )->getResultArray();

        return view('admin/inventorinonmedis/detail_penerimaan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page header + detail existing
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find_one($id);

        // redirect ke detail jika Diterima (2) atau Ditolak (3)
        if (is_array($baris) && in_array((int) ($baris['id_status_penerimaan_barang'] ?? 0), [2, 3], true)) {
            return $this->detail($id);
        }

        // get no_pengadaan for display
        if (is_array($baris) && (bool) $baris['id_pengadaan']) {
            $pengadaan = $this->guarded(
                $this
                    ->get_db()
                    ->table('inventori_non_medis.pengadaan_barang')
                    ->select('no_pengadaan')
                    ->where('id_pengadaan', (int) $baris['id_pengadaan'])
                    ->get(),
            )->getRowArray();
            $baris['no_pengadaan'] = $pengadaan['no_pengadaan'] ?? '';
        }

        $id_pengadaan = (int) ($baris['id_pengadaan'] ?? 0);

        $detail_items = $this->guarded($this->get_db()->query('
            SELECT d.id_barang, d.qty_diterima, b.kode_barang, b.nama_barang, s.nama_satuan,
                   pbd.qty AS qty_dipesan,
                   COALESCE((
                       SELECT SUM(prbd.qty_diterima)
                       FROM inventori_non_medis.penerimaan_barang_detail prbd
                       JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                       WHERE prb.id_pengadaan = ?
                         AND prbd.id_barang = d.id_barang
                         AND prb.id_status_penerimaan_barang = 2
                         AND prb.id_penerimaan != ?
                   ), 0) AS sudah_diterima
            FROM inventori_non_medis.penerimaan_barang_detail d
            LEFT JOIN inventori_non_medis.barang b ON d.id_barang = b.id_barang
            LEFT JOIN inventori_non_medis.satuan s ON b.id_satuan = s.id_satuan
            LEFT JOIN inventori_non_medis.pengadaan_barang_detail pbd
                ON pbd.id_pengadaan = ? AND pbd.id_barang = d.id_barang
            WHERE d.id_penerimaan = ?
              AND d.id_barang > 0
            ORDER BY b.nama_barang ASC
        ', [
            $id_pengadaan,
            (int) $id,
            $id_pengadaan,
            (int) $id,
        ]))->getResultArray();

        return view('admin/inventorinonmedis/tambah_penerimaan_barang', [
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
    /** @throws \CodeIgniter\Files\Exceptions\FileNotFoundException */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $new_status = (int) ($this->request->getPost('id_status_penerimaan_barang') ?? 1);

        $postData = [
            'tanggal'      => $this->request->getPost('tanggal'),
            'id_pengadaan' => $this->request->getPost('id_pengadaan') ?: null,
            'petugas'      => $this->request->getPost('petugas') ?: null,
            'catatan'      => $this->request->getPost('catatan') ?: null,
            'status'       => '-',
        ];

        if (!$postData['petugas']) {
            session()->setFlashdata('error', 'Penerima wajib diisi.');
            return $this->home();
        }

        helper('autonomor');
        /** @var string|null $lastNo */
        $lastNo            = $this->get_last('inventori_non_medis.penerimaan_barang', 'no_penerimaan', 'id_penerimaan');
        $tanggalPenerimaan = $postData['tanggal'] ?? null;
        /** @var string|null $tanggalPenerimaan */
        $postData['no_penerimaan']               = generateNextNoPenerimaanBarang($lastNo, $tanggalPenerimaan);
        $postData['id_status_penerimaan_barang'] = $new_status;

        // Generate no_masuk saat langsung Diterima
        if ($new_status === 2) {
            /** @var string|null $lastNoMasuk */
            $lastNoMasuk          = $this->get_last(
                'inventori_non_medis.penerimaan_barang',
                'no_masuk',
                'id_penerimaan',
            );
            $postData['no_masuk'] = generateNextNoMasukBarang($lastNoMasuk);
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            $inserted = $this->model->insert($postData);
            if ($inserted === false) {
                $db->transRollback();
                session()->setFlashdata('error', implode(' ', $this->model->errors()));
                return $this->home();
            }
            $id_penerimaan = (int) $db->insertID();

            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            /** @var array<array-key, mixed> $detail_ids */
            $detail_qty = $this->request->getPost('detail_qty') ?? [];
            /** @var array<array-key, mixed> $detail_qty */

            // Validasi qty tidak melebihi sisa
            $id_pengadaan_val = (int) ($postData['id_pengadaan'] ?? 0);
            if ($id_pengadaan_val > 0) {
                for ($i = 0; $i < count($detail_ids); $i++) {
                    $id_barang = (int) ($detail_ids[$i] ?? 0);
                    $qty_input = (int) ($detail_qty[$i] ?? 0);
                    if ($id_barang > 0 && $qty_input > 0) {
                        $qty_dipesan = (int) (
                            $this->guarded(
                                $db
                                    ->table('inventori_non_medis.pengadaan_barang_detail')
                                    ->select('qty')
                                    ->where('id_pengadaan', $id_pengadaan_val)
                                    ->where('id_barang', $id_barang)
                                    ->get(),
                            )->getRowArray()['qty'] ?? 0
                        );

                        $sudah_terima = (int) (
                            $this->guarded($db->query(
                                '
                            SELECT COALESCE(SUM(prbd.qty_diterima), 0) AS total
                            FROM inventori_non_medis.penerimaan_barang_detail prbd
                            JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                            WHERE prb.id_pengadaan = ?
                              AND prbd.id_barang = ?
                              AND prb.id_status_penerimaan_barang = 2
                        ',
                                [$id_pengadaan_val, $id_barang],
                            ))->getRowArray()['total'] ?? 0
                        );

                        $sisa = $qty_dipesan - $sudah_terima;
                        if ($qty_input > $sisa) {
                            $db->transRollback();
                            session()->setFlashdata(
                                'error',
                                "Qty diterima melebihi sisa yang tersedia (maks: {$sisa}).",
                            );
                            return $this->home();
                        }
                    }
                }
            }

            // Validasi: harus ada item dengan qty > 0 saat Diterima
            if ($new_status === 2) {
                $has_items = false;
                for ($i = 0; $i < count($detail_ids); $i++) {
                    if ((int) ($detail_ids[$i] ?? 0) > 0 && (int) ($detail_qty[$i] ?? 0) > 0) {
                        $has_items = true;
                        break;
                    }
                }
                if (!$has_items) {
                    $db->transRollback();
                    session()->setFlashdata(
                        'error',
                        'Isi qty diterima minimal untuk satu item sebelum menerima barang.',
                    );
                    return $this->home();
                }
            }

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

            // Jika langsung Diterima: buat transaksi stok masuk + update pengadaan + fulfill pending
            if ($new_status === 2) {
                try {
                    $this->create_transaksi_stok_masuk($id_penerimaan, (string) $postData['no_masuk']);
                    $this->update_pengadaan_status((int) ($postData['id_pengadaan'] ?? 0));
                    $this->fulfill_pending_permintaan($id_penerimaan);
                } catch (\Throwable $e) {
                    log_message('error', '[Penerimaan::create] transaksi stok: ' . $e->getMessage());
                    session()->setFlashdata(
                        'error',
                        'Data tersimpan, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                    );
                    return $this->home();
                }
            }

            session()->setFlashdata('success', 'Data Penerimaan Barang berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail + handle status transitions
    /** @throws \CodeIgniter\Files\Exceptions\FileNotFoundException */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $current        = $this->model->find((int) $id);
        $current_status = is_array($current) ? (int) ($current['id_status_penerimaan_barang'] ?? 0) : 0;

        if (in_array($current_status, [2, 3], true)) {
            session()->setFlashdata('error', 'Penerimaan barang yang sudah diterima atau ditolak tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_penerimaan_barang') ?? 1);

        $postData = [
            'tanggal'                     => $this->request->getPost('tanggal'),
            'petugas'                     => $this->request->getPost('petugas') ?: null,
            'catatan'                     => $this->request->getPost('catatan') ?: null,
            'id_status_penerimaan_barang' => $new_status,
        ];

        if (!$postData['petugas']) {
            session()->setFlashdata('error', 'Penerima wajib diisi.');
            return $this->home();
        }

        // Generate no_masuk saat diterima
        if ($new_status === 2 && $current_status !== 2) {
            helper('autonomor');
            /** @var string|null $lastNo */
            $lastNo               = $this->get_last(
                'inventori_non_medis.penerimaan_barang',
                'no_masuk',
                'id_penerimaan',
            );
            $postData['no_masuk'] = generateNextNoMasukBarang($lastNo);
            $this->pending_masuk  = true;
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            // sync detail
            $db
                ->table('inventori_non_medis.penerimaan_barang_detail')
                ->where('id_penerimaan', (int) $id)
                ->delete();

            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            /** @var array<array-key, mixed> $detail_ids */
            $detail_qty = $this->request->getPost('detail_qty') ?? [];
            /** @var array<array-key, mixed> $detail_qty */

            // Validasi qty tidak melebihi sisa
            $id_pengadaan_val = (int) ($current['id_pengadaan'] ?? 0);
            if ($id_pengadaan_val > 0) {
                for ($i = 0; $i < count($detail_ids); $i++) {
                    $id_barang = (int) ($detail_ids[$i] ?? 0);
                    $qty_input = (int) ($detail_qty[$i] ?? 0);
                    if ($id_barang > 0 && $qty_input > 0) {
                        $qty_dipesan = (int) (
                            $this->guarded(
                                $db
                                    ->table('inventori_non_medis.pengadaan_barang_detail')
                                    ->select('qty')
                                    ->where('id_pengadaan', $id_pengadaan_val)
                                    ->where('id_barang', $id_barang)
                                    ->get(),
                            )->getRowArray()['qty'] ?? 0
                        );

                        $sudah_terima = (int) (
                            $this->guarded($db->query(
                                '
                            SELECT COALESCE(SUM(prbd.qty_diterima), 0) AS total
                            FROM inventori_non_medis.penerimaan_barang_detail prbd
                            JOIN inventori_non_medis.penerimaan_barang prb ON prbd.id_penerimaan = prb.id_penerimaan
                            WHERE prb.id_pengadaan = ?
                              AND prbd.id_barang = ?
                              AND prb.id_status_penerimaan_barang = 2
                              AND prb.id_penerimaan != ?
                        ',
                                [$id_pengadaan_val, $id_barang, (int) $id],
                            ))->getRowArray()['total'] ?? 0
                        );

                        $sisa = $qty_dipesan - $sudah_terima;
                        if ($qty_input > $sisa) {
                            $db->transRollback();
                            session()->setFlashdata(
                                'error',
                                "Qty diterima melebihi sisa yang tersedia (maks: {$sisa}).",
                            );
                            return $this->home();
                        }
                    }
                }
            }

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

            // validasi sebelum diterima
            if ($new_status === 2) {
                $has_items = $db
                    ->table('inventori_non_medis.penerimaan_barang_detail')
                    ->where('id_penerimaan', (int) $id)
                    ->where('qty_diterima >', 0)
                    ->countAllResults() > 0;
                if (!$has_items) {
                    $db->transRollback();
                    session()->setFlashdata(
                        'error',
                        'Isi qty diterima minimal untuk satu item sebelum menerima barang.',
                    );
                    return $this->home();
                }
            }

            $this->model->update($id, $postData);

            $db->transCommit();

            // buat transaksi stok masuk setelah commit
            if ($this->pending_masuk) {
                $this->pending_masuk = false;
                $saved               = $this->model->find((int) $id);
                if (is_array($saved) && (bool) $saved['no_masuk']) {
                    try {
                        $this->create_transaksi_stok_masuk((int) $id, (string) $saved['no_masuk']);
                        $this->update_pengadaan_status((int) ($saved['id_pengadaan'] ?? 0));
                        // Auto-fulfill pending permintaan barang baru
                        $this->fulfill_pending_permintaan((int) $id);
                    } catch (\Throwable $e) {
                        log_message('error', '[Penerimaan] transaksi stok: ' . $e->getMessage());
                        session()->setFlashdata(
                            'error',
                            'Status berhasil diubah, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                        );
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
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function create_transaksi_stok_masuk(int $id, string $no_masuk): void
    {
        $db = $this->get_db();

        $row = $this->guarded(
            $db
                ->table('inventori_non_medis.penerimaan_barang pb')
                ->join('inventori_non_medis.pengadaan_barang pg', 'pb.id_pengadaan = pg.id_pengadaan', 'left')
                ->join('inventori_non_medis.suplier s', 'pg.id_suplier = s.id_suplier', 'left')
                ->join('role.petugas pt', 'pb.petugas = pt.id_petugas', 'left')
                ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
                ->select('s.nama_suplier, pb.no_penerimaan, o.nama')
                ->where('pb.id_penerimaan', $id)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $row */

        $keterangan = trim(implode(', ', array_filter([
            (string) ($row['no_penerimaan'] ?? ''),
            (string) ($row['nama_suplier'] ?? '') !== '' ? 'Suplier ' . (string) $row['nama_suplier'] : '',
            (string) ($row['nama'] ?? '') !== '' ? 'Penerima: ' . (string) $row['nama'] : '',
        ])));

        $details = $this->guarded(
            $db
                ->table('inventori_non_medis.penerimaan_barang_detail pbd')
                ->join('inventori_non_medis.barang b', 'pbd.id_barang = b.id_barang', 'left')
                ->select('pbd.id_barang, pbd.qty_diterima, pbd.harga_satuan, b.stok')
                ->where('pbd.id_penerimaan', $id)
                ->where('pbd.id_barang >', 0)
                ->where('pbd.qty_diterima >', 0)
                ->get(),
        )->getResultArray();
        /** @var list<array<string, mixed>> $details */

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
                'harga_satuan' => isset($d['harga_satuan']) && (float) $d['harga_satuan'] > 0
                    ? $d['harga_satuan']
                    : null,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sebelum + $qty,
            ]);
            $db
                ->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', 'stok + ' . $qty, false)
                ->update();
        }

        $db->transCommit();
    }

    // kalau semua item sudah diterima penuh, set status pengadaan → Selesai (2)
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function update_pengadaan_status(int $id_pengadaan): void
    {
        if ($id_pengadaan === 0)
            return;
        $db = $this->get_db();

        $ordered = $this->guarded(
            $db
                ->table('inventori_non_medis.pengadaan_barang_detail')
                ->select('id_barang, qty')
                ->where('id_pengadaan', $id_pengadaan)
                ->where('id_barang >', 0)
                ->get(),
        )->getResultArray();
        /** @var list<array<string, mixed>> $ordered */

        if (count($ordered) === 0)
            return;

        foreach ($ordered as $item) {
            $total_received = (float) (
                $this->guarded(
                    $db
                        ->table('inventori_non_medis.penerimaan_barang pb')
                        ->join(
                            'inventori_non_medis.penerimaan_barang_detail pbd',
                            'pb.id_penerimaan = pbd.id_penerimaan',
                            'left',
                        )
                        ->selectSum('pbd.qty_diterima', 'total')
                        ->where('pb.id_pengadaan', $id_pengadaan)
                        ->where('pb.id_status_penerimaan_barang', 2)
                        ->where('pbd.id_barang', (int) $item['id_barang'])
                        ->get(),
                )->getRowArray()['total'] ?? 0
            );

            if ($total_received < (float) $item['qty'])
                return;
        }

        $db
            ->table('inventori_non_medis.pengadaan_barang')
            ->where('id_pengadaan', $id_pengadaan)
            ->set('id_status_pengadaan_barang', 2)
            ->update();
    }

    /**
     * Auto-fulfill pending permintaan barang baru setelah penerimaan dikonfirmasi.
     *
     * Trace: penerimaan → pengadaan → pengajuan (yang punya id_permintaan) → permintaan status 5.
     * Hanya item yang BELUM pernah stok keluar untuk permintaan ini yang di-fulfill.
     * Jika semua item pending terpenuhi → status permintaan → Selesai (6).
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    private function fulfill_pending_permintaan(int $id_penerimaan): void
    {
        $db = $this->get_db();

        // Trace: penerimaan → pengadaan
        $penerimaan = $this->guarded(
            $db
                ->table('inventori_non_medis.penerimaan_barang')
                ->select('id_pengadaan')
                ->where('id_penerimaan', $id_penerimaan)
                ->get(),
        )->getRowArray();
        $id_pengadaan = (int) ($penerimaan['id_pengadaan'] ?? 0);
        if ($id_pengadaan === 0) {
            log_message('debug', "[fulfill] Penerimaan {$id_penerimaan} tidak punya id_pengadaan");
            return;
        }

        // Trace: pengadaan → pengajuan
        $pengadaan = $this->guarded(
            $db
                ->table('inventori_non_medis.pengadaan_barang')
                ->select('id_pengajuan')
                ->where('id_pengadaan', $id_pengadaan)
                ->get(),
        )->getRowArray();
        $id_pengajuan = (int) ($pengadaan['id_pengajuan'] ?? 0);
        if ($id_pengajuan === 0) {
            log_message('debug', "[fulfill] Pengadaan {$id_pengadaan} tidak punya id_pengajuan");
            return;
        }

        // Trace: pengajuan → permintaan (hanya pengajuan auto-created yang punya id_permintaan)
        $pengajuan = $this->guarded(
            $db
                ->table('inventori_non_medis.pengajuan_barang')
                ->select('id_permintaan')
                ->where('id_pengajuan', $id_pengajuan)
                ->get(),
        )->getRowArray();
        $id_permintaan = (int) ($pengajuan['id_permintaan'] ?? 0);
        if ($id_permintaan === 0) {
            log_message('debug', "[fulfill] Pengajuan {$id_pengajuan} tidak punya id_permintaan (bukan auto-created)");
            return;
        }

        // Cek apakah permintaan ini masih status Menunggu Pengadaan (5)
        $permintaan = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang')
                ->select(
                    'id_permintaan, id_status_permintaan_barang, no_permintaan, no_keluar, master_ruangan, petugas, petugas_gudang',
                )
                ->where('id_permintaan', $id_permintaan)
                ->where('id_status_permintaan_barang', 5)
                ->get(),
        )->getRowArray();
        if ($permintaan === null) {
            log_message('debug', "[fulfill] Permintaan {$id_permintaan} bukan status 5 (Menunggu Pengadaan)");
            return;
        }

        // Ambil id_barang yang sudah pernah stok keluar untuk permintaan ini
        // (item existing yang sudah di-fulfill saat approval di Persetujuan)
        $already_fulfilled = $this->guarded($db->query('
            SELECT DISTINCT tsd.id_barang
            FROM inventori_non_medis.transaksi_stok_detail tsd
            JOIN inventori_non_medis.transaksi_stok ts ON tsd.id_transaksi = ts.id_transaksi
            WHERE ts.id_permintaan = ?
              AND ts.id_tipe_transaksi_stok = 2
        ', [$id_permintaan]))->getResultArray();
        /** @var list<array<string, mixed>> $already_fulfilled */
        $fulfilled_ids = array_map(fn($r) => (int) $r['id_barang'], $already_fulfilled);

        // Ambil detail item yang BELUM pernah stok keluar dan stok sekarang sudah ada
        $builder = $db
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->select('d.id_barang, d.qty_disetujui, b.stok, b.harga_satuan, b.nama_barang')
            ->where('d.id_permintaan', $id_permintaan)
            ->where('d.id_barang >', 0)
            ->where('d.qty_disetujui >', 0);

        if (count($fulfilled_ids) > 0) {
            $builder->whereNotIn('d.id_barang', $fulfilled_ids);
        }

        $pending_items = $this->guarded($builder->get())->getResultArray();
        /** @var list<array<string, mixed>> $pending_items */

        if (count($pending_items) === 0) {
            log_message('debug', "[fulfill] Permintaan {$id_permintaan} tidak ada item pending yang perlu di-fulfill");
            return;
        }

        // Filter hanya item yang stok-nya sudah cukup
        $items_to_fulfill = [];
        $items_not_ready  = [];
        foreach ($pending_items as $item) {
            if ((int) $item['stok'] >= (int) $item['qty_disetujui']) {
                $items_to_fulfill[] = $item;
            } else {
                $items_not_ready[] = $item;
            }
        }

        if (count($items_to_fulfill) === 0) {
            log_message('debug', "[fulfill] Permintaan {$id_permintaan} stok belum cukup untuk item pending");
            return;
        }

        // Buat transaksi stok keluar untuk item yang siap
        helper('autonomor');
        $lastNo = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang')
                ->select('no_keluar')
                ->where('no_keluar IS NOT NULL')
                ->orderBy('id_permintaan', 'DESC')
                ->limit(1)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $lastNo */
        $lastNoKeluar = $lastNo['no_keluar'] ?? null;
        /** @var string|null $lastNoKeluar */
        $no_keluar = generateNextNoKeluarBarang($lastNoKeluar);

        // Info untuk keterangan
        $row = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang pb')
                ->join('ruangan.ruangan r', 'pb.master_ruangan = r.id_ruangan', 'left')
                ->join('role.petugas pt', 'pb.petugas = pt.id_petugas', 'left')
                ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
                ->select('pb.no_permintaan, r.nama_ruangan, o.nama AS nama_pemohon')
                ->where('pb.id_permintaan', $id_permintaan)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $row */

        $keterangan = trim(implode(', ', array_filter([
            (string) ($row['no_permintaan'] ?? ''),
            (string) ($row['nama_ruangan'] ?? '') !== '' ? 'Ruangan ' . (string) $row['nama_ruangan'] : '',
            (string) ($row['nama_pemohon'] ?? '') !== '' ? 'Pemohon: ' . (string) $row['nama_pemohon'] : '',
            'Auto-fulfill barang baru',
        ])));

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 2, // keluar
            'tanggal'       => $now,
            'id_permintaan' => $id_permintaan,
            'keterangan'    => $keterangan,
        ]);
        $id_transaksi = (int) $db->insertID();

        foreach ($items_to_fulfill as $d) {
            $qty          = (int) $d['qty_disetujui'];
            $stok_sebelum = (int) $d['stok'];
            $db->table('inventori_non_medis.transaksi_stok_detail')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang'    => (int) $d['id_barang'],
                'qty'          => $qty,
                'harga_satuan' => isset($d['harga_satuan']) && (float) $d['harga_satuan'] > 0
                    ? $d['harga_satuan']
                    : null,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sebelum - $qty,
            ]);
            $db
                ->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', 'stok - ' . $qty, false)
                ->update();
        }

        // Tentukan status akhir permintaan
        if (count($items_not_ready) === 0) {
            // Semua item pending terpenuhi → Selesai (6)
            $db
                ->table('inventori_non_medis.permintaan_barang')
                ->where('id_permintaan', $id_permintaan)
                ->update([
                    'id_status_permintaan_barang' => 6,
                    'no_keluar'                   => $no_keluar,
                ]);
            log_message('info', "[fulfill] Permintaan {$id_permintaan} → Selesai (6)");
        } else {
            // Masih ada item yang belum terpenuhi — tetap status 5, tapi simpan no_keluar parsial
            // no_keluar belum di-set karena belum selesai sepenuhnya
            log_message(
                'info',
                "[fulfill] Permintaan {$id_permintaan} partial fulfill, masih ada "
                . count($items_not_ready)
                . ' item pending',
            );
        }

        $db->transCommit();
    }
}
