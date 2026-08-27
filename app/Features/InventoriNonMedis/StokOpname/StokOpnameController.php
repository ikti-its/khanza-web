<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\StokOpname;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class StokOpnameController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new StokOpnameModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Stok Opname',         'stok_opname'],
            ],
            'Stok Opname',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_opname',             'ID Opname'],
                [SHOW,       REQUIRED, I::DTIME,  'tanggal',               'Tanggal'],
                [SHOW,       OPTIONAL, I::SELECT, 'id_status_stok_opname', 'Status'],
                [SHOW,       REQUIRED, I::SELECT, 'id_petugas',            'Pelaksana'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'total_nominal',         'Total Nominal'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'catatan',               'Catatan'],
            ],
            // child_path: '/inventori-non-medis/detail-stok-opname',
            // child_fk: 'id_opname',
        );
    }

    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('id_opname', 'DESC');
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    // tambahkan kolom total nominal (selisih x harga_satuan) per opname untuk laporan bulanan
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    protected function after_read(array &$data_tabel): void
    {
        if (count($data_tabel) === 0)
            return;

        $db = $this->get_db();
        foreach ($data_tabel as &$row) {
            /** @var array<string, mixed> $row */
            $id = (int) ($row['id_opname'] ?? 0);
            if ($id === 0) {
                $row['total_nominal'] = '-';
                continue;
            }

            $total = (float) (
                $this->guarded($db->query('SELECT COALESCE(SUM(d.selisih * COALESCE(b.harga_satuan, 0)), 0) AS total
                 FROM inventori_non_medis.stok_opname_detail d
                 LEFT JOIN inventori_non_medis.barang b ON d.id_barang = b.id_barang
                 WHERE d.id_opname = ?', [$id]))->getRowArray()['total'] ?? 0
            );

            $row['total_nominal'] = $this->format_nominal($total);
        }
        unset($row);
    }

    // format nominal rupiah dengan pewarnaan: biru = kelebihan, merah = kekurangan, abu-abu = pas
    private function format_nominal(float $nominal): string
    {
        $formatted = 'Rp ' . number_format(abs($nominal), 0, ',', '.');

        if ($nominal > 0)
            return '<span class="text-blue-600 font-semibold">+' . $formatted . '</span>';
        if ($nominal < 0)
            return '<span class="text-red-600 font-semibold">-' . $formatted . '</span>';
        return '<span class="text-gray-400 font-semibold">' . $formatted . '</span>';
    }

    // form tambah: 1-page header + detail
    #[\Override]
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_stok_opname', [
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
                ->table('inventori_non_medis.stok_opname_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->select(
                    'd.id_barang, d.stok_sistem, d.stok_fisik, b.kode_barang, b.nama_barang, b.harga_satuan, s.nama_satuan',
                )
                ->where('d.id_opname', (int) $id)
                ->where('d.id_barang >', 0)
                ->get(),
        )->getResultArray();

        return view('admin/inventorinonmedis/detail_stok_opname', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page header + detail existing (hanya saat Proses)
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find_one($id);

        // redirect ke detail jika status Selesai (2)
        if (is_array($baris) && (int) ($baris['id_status_stok_opname'] ?? 0) === 2) {
            return $this->detail($id);
        }

        $detail_items = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.stok_opname_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->select(
                    'd.id_barang, d.stok_sistem, d.stok_fisik, b.kode_barang, b.nama_barang, b.harga_satuan, s.nama_satuan',
                )
                ->where('d.id_opname', (int) $id)
                ->where('d.id_barang >', 0)
                ->get(),
        )->getResultArray();

        return view('admin/inventorinonmedis/tambah_stok_opname', [
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
        if (is_array($current) && (int) ($current['id_status_stok_opname'] ?? 0) === 2) {
            session()->setFlashdata('error', 'Stok Opname yang sudah Selesai tidak dapat dihapus.');
            return $this->home();
        }

        $db = $this->get_db();
        try {
            $db->transBegin();
            $db
                ->table('inventori_non_medis.stok_opname_detail')
                ->where('id_opname', (int) $id)
                ->delete();
            $this->model->delete($id);
            $db->transCommit();
            session()->setFlashdata('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return $this->home();
    }

    // simpan header + detail sekaligus
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $new_status = (int) ($this->request->getPost('id_status_stok_opname') ?? 1);

        $postData = [
            'tanggal'               => $this->request->getPost('tanggal'),
            'id_petugas'            => $this->request->getPost('id_petugas') ?: null,
            'catatan'               => $this->request->getPost('catatan'),
            'id_status_stok_opname' => $new_status,
        ];

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->insert($postData);
            $id_opname = (int) $db->insertID();

            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            /** @var array<array-key, mixed> $detail_ids */
            $detail_stok = $this->request->getPost('detail_stok_fisik') ?? [];
            /** @var array<array-key, mixed> $detail_stok */

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang  = (int) ($detail_ids[$i] ?? 0);
                $stok_fisik = (int) ($detail_stok[$i] ?? 0);
                if ($id_barang > 0) {
                    // ambil stok sistem saat ini
                    $row = $this->guarded(
                        $db->table('inventori_non_medis.barang')->select('stok')->where('id_barang', $id_barang)->get(),
                    )->getRowArray();
                    $stok_sistem = (int) ($row['stok'] ?? 0);

                    $db->table('inventori_non_medis.stok_opname_detail')->insert([
                        'id_opname'   => $id_opname,
                        'id_barang'   => $id_barang,
                        'stok_sistem' => $stok_sistem,
                        'stok_fisik'  => $stok_fisik,
                        'selisih'     => $stok_fisik - $stok_sistem,
                    ]);
                }
            }

            $db->transCommit();

            // buat transaksi stok opname jika langsung disimpan sebagai Selesai
            if ($new_status === 2) {
                try {
                    $this->create_transaksi_stok_opname($id_opname);
                } catch (\Throwable $e) {
                    log_message('error', '[StokOpname::create] create_transaksi_stok_opname: ' . $e->getMessage());
                    session()->setFlashdata(
                        'error',
                        'Data tersimpan, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                    );
                    return $this->home();
                }
            }

            session()->setFlashdata('success', 'Data Stok Opname berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail + handle status Selesai
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (!is_array($current))
            return $this->home();

        $current_status = (int) ($current['id_status_stok_opname'] ?? 0);
        if ($current_status === 2) {
            session()->setFlashdata('error', 'Stok Opname yang sudah Selesai tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_stok_opname') ?? 1);

        $postData = [
            'tanggal'               => $this->request->getPost('tanggal'),
            'id_petugas'            => $this->request->getPost('id_petugas') ?: null,
            'catatan'               => $this->request->getPost('catatan'),
            'id_status_stok_opname' => $new_status,
        ];

        $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
        /** @var array<array-key, mixed> $detail_ids */
        $detail_stok = $this->request->getPost('detail_stok_fisik') ?? [];
        /** @var array<array-key, mixed> $detail_stok */

        // validasi sebelum Selesai
        if ($new_status === 2 && count($detail_ids) === 0) {
            session()->setFlashdata(
                'error',
                'Isi detail stok opname terlebih dahulu sebelum mengubah status menjadi Selesai.',
            );
            return $this->home();
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            // sync detail
            $db
                ->table('inventori_non_medis.stok_opname_detail')
                ->where('id_opname', (int) $id)
                ->delete();

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang  = (int) ($detail_ids[$i] ?? 0);
                $stok_fisik = (int) ($detail_stok[$i] ?? 0);
                if ($id_barang > 0) {
                    $row = $this->guarded(
                        $db->table('inventori_non_medis.barang')->select('stok')->where('id_barang', $id_barang)->get(),
                    )->getRowArray();
                    $stok_sistem = (int) ($row['stok'] ?? 0);

                    $db->table('inventori_non_medis.stok_opname_detail')->insert([
                        'id_opname'   => (int) $id,
                        'id_barang'   => $id_barang,
                        'stok_sistem' => $stok_sistem,
                        'stok_fisik'  => $stok_fisik,
                        'selisih'     => $stok_fisik - $stok_sistem,
                    ]);
                }
            }

            $this->model->update($id, $postData);

            $db->transCommit();

            // buat transaksi stok opname jika status → Selesai
            $is_new_selesai = $new_status === 2 && $current_status !== 2;
            if ($is_new_selesai) {
                try {
                    $this->create_transaksi_stok_opname((int) $id);
                } catch (\Throwable $e) {
                    log_message('error', '[StokOpname] create_transaksi_stok_opname: ' . $e->getMessage());
                    session()->setFlashdata(
                        'error',
                        'Status berhasil diubah, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                    );
                    return $this->home();
                }
            }

            session()->setFlashdata('success', 'Data Stok Opname berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
        }

        return $this->home();
    }

    // catat penyesuaian stok, update stok barang ke nilai fisik (hanya yang ada selisih)
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function create_transaksi_stok_opname(int $id_opname): void
    {
        $db = $this->get_db();

        $details = $this->guarded(
            $db
                ->table('inventori_non_medis.stok_opname_detail')
                ->select('id_barang, stok_sistem, stok_fisik, selisih')
                ->where('id_opname', $id_opname)
                ->where('id_barang >', 0)
                ->where('selisih !=', 0)
                ->get(),
        )->getResultArray();
        /** @var list<array<string, mixed>> $details */

        if (count($details) === 0)
            return;

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 3,
            'tanggal'                => $now,
            'id_opname'              => $id_opname,
            'keterangan'             => 'Stok Opname #' . $id_opname,
        ]);
        $id_transaksi = (int) $db->insertID();

        foreach ($details as $d) {
            $stok_sistem = (int) $d['stok_sistem'];
            $stok_fisik  = (int) $d['stok_fisik'];
            $selisih     = (int) $d['selisih'];

            $db->table('inventori_non_medis.transaksi_stok_detail')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang'    => (int) $d['id_barang'],
                'qty'          => abs($selisih),
                'stok_sebelum' => $stok_sistem,
                'stok_sesudah' => $stok_fisik,
            ]);

            $db
                ->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', $stok_fisik)
                ->update();
        }

        $db->transCommit();
    }
}
