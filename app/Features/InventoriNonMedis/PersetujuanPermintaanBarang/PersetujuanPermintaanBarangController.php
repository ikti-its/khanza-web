<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PersetujuanPermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PersetujuanPermintaanBarangController extends ControllerTemplate
{
    private bool $pending_keluar = false;

    public function __construct()
    {
        parent::__construct(
            new PersetujuanPermintaanBarangModel(),
            [
                ['Inventori Non Medis',           'inventori_non_medis'],
                ['Persetujuan Permintaan Barang', 'persetujuan_permintaan_barang'],
            ],
            'Persetujuan Permintaan Barang',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan', 'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_permintaan', 'No. Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::DTIME, 'tanggal', 'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'tanggal_diproses', 'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas', 'Pemohon'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'nama_ruangan', 'Ruangan'],
                [
                    FORM_ONLY,
                    OPTIONAL,
                    I::MODAL,
                    'petugas_gudang',
                    'Petugas Gudang',
                    [
                        'modal'          => 'modalPemohon',
                        'display_column' => 'nama',
                        'placeholder'    => 'Klik cari petugas gudang...',
                    ],
                ],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'no_keluar', 'No. Keluar'],
            ],
            // child_path: '/inventori-non-medis/persetujuan-permintaan-barang-detail',
            // child_fk: 'id_permintaan',
        );
    }

    // hanya tampilkan Proses Permintaan (4), Disetujui (2), Ditolak (3) — bukan Draf
    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_filter('id_status_permintaan_barang', [2, 3, 4, 5, 6]);
        $this->model->set_order('id_permintaan', 'DESC');
    }

    // narrows the query-result union (bool|Query|BaseResult) that mago infers
    // for ->get()/->query(), matching ModelTemplate::guarded_get() convention.
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function guarded(mixed $result): \CodeIgniter\Database\BaseResult
    {
        assert($result instanceof \CodeIgniter\Database\BaseResult, 'Query gagal dieksekusi.');
        return $result;
    }

    // hanya izinkan transisi ke Disetujui (2) atau Ditolak (3) dari Persetujuan
    /** @throws \CodeIgniter\Files\Exceptions\FileNotFoundException */
    #[\Override]
    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_permintaan_barang'] ?? 0);

        if (!in_array($new_status, [2, 3], true)) {
            $current                                 = $this->model->find($id);
            $postData['id_status_permintaan_barang'] = (int) ($current['id_status_permintaan_barang'] ?? 4);
            return;
        }

        $current = $this->model->find($id);
        if (!is_array($current))
            return;
        if ((int) ($current['id_status_permintaan_barang'] ?? 0) === 2)
            return;

        $postData['tanggal_diproses'] = date('Y-m-d H:i:s');

        if ($new_status !== 2)
            return;

        // generate no_keluar hanya saat Disetujui
        helper('autonomor');
        /** @var string|null $lastNo */
        $lastNo                = $this->get_last('inventori_non_medis.permintaan_barang', 'no_keluar', 'id_permintaan');
        $postData['no_keluar'] = generateNextNoKeluarBarang($lastNo);
        $this->pending_keluar  = true;
    }

    // halaman detail (readonly)
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function detail(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->join('inventori_non_medis.satuan s2', 'd.id_satuan_baru = s2.id_satuan', 'left')
                ->select(
                    'd.id_detail, d.id_barang, d.qty, d.qty_disetujui, d.nama_barang_baru, d.id_satuan_baru, d.id_jenis_barang_baru, b.kode_barang, b.nama_barang, b.stok, COALESCE(s.nama_satuan, s2.nama_satuan) AS nama_satuan',
                )
                ->where('d.id_permintaan', (int) $id)
                ->groupStart()
                ->where('d.id_barang >', 0)
                ->orWhere('d.nama_barang_baru IS NOT NULL')
                ->groupEnd()
                ->get(),
        )->getResultArray();

        return view('admin/inventorinonmedis/detail_persetujuan_permintaan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page — redirect jika sudah diproses
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $baris = $this->model->find_one($id);

        // redirect ke detail jika sudah Disetujui atau Ditolak
        $status = is_array($baris) ? (int) ($baris['id_status_permintaan_barang'] ?? 0) : 0;
        if (in_array($status, [2, 3, 5, 6], true)) {
            return $this->detail($id);
        }

        $detail_items = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
                ->join('inventori_non_medis.satuan s2', 'd.id_satuan_baru = s2.id_satuan', 'left')
                ->select(
                    'd.id_detail, d.id_barang, d.qty, d.qty_disetujui, d.nama_barang_baru, d.id_satuan_baru, d.id_jenis_barang_baru, b.kode_barang, b.nama_barang, b.stok, COALESCE(s.nama_satuan, s2.nama_satuan) AS nama_satuan',
                )
                ->where('d.id_permintaan', (int) $id)
                ->groupStart()
                ->where('d.id_barang >', 0)
                ->orWhere('d.nama_barang_baru IS NOT NULL')
                ->groupEnd()
                ->get(),
        )->getResultArray();

        return view('admin/inventorinonmedis/ubah_persetujuan_permintaan_barang', [
            'judul'        => 'Ubah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submitedit/' . $id,
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // validasi petugas, qty & stok sebelum approve, sync qty_disetujui, buat transaksi keluar setelah
    /**
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        $new_status     = (int) ($this->request->getPost('id_status_permintaan_barang') ?? 0);
        $current        = $this->model->find((int) $id);
        $current_status = is_array($current) ? (int) ($current['id_status_permintaan_barang'] ?? 0) : 0;

        // blokir jika sudah final (Disetujui/Ditolak/Menunggu Pengadaan/Selesai)
        if (in_array($current_status, [2, 3, 5, 6], true)) {
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah kembali.');
            return $this->home();
        }

        $is_new_approval = $new_status === 2 && $current_status !== 2;

        if ($is_new_approval) {
            if (!$this->request->getPost('petugas_gudang')) {
                session()->setFlashdata('error', 'Petugas gudang wajib diisi sebelum menyetujui permintaan.');
                return redirect()->back();
            }
        }

        // sync qty_disetujui from form (by id_detail for both existing and baru items)
        $detail_id_detail     = (array) ($this->request->getPost('detail_id_detail') ?? []);
        $detail_qty_disetujui = (array) ($this->request->getPost('detail_qty_disetujui') ?? []);

        $db = $this->get_db();
        for ($i = 0; $i < count($detail_qty_disetujui); $i++) {
            $id_detail = (int) ($detail_id_detail[$i] ?? 0);
            $qty_d     = (int) ($detail_qty_disetujui[$i] ?? 0);
            if ($id_detail > 0) {
                $db
                    ->table('inventori_non_medis.permintaan_barang_detail')
                    ->where('id_detail', $id_detail)
                    ->update(['qty_disetujui' => $qty_d]);
            }
        }

        // default: dipakai lagi di luar blok $is_new_approval di bawah tanpa
        // mengubah alur — mago tidak bisa membuktikan kedua blok if ($is_new_approval)
        // selalu berjalan bersamaan, jadi perlu nilai awal yang eksplisit.
        $has_existing   = false;
        $has_baru       = false;
        $baru_items     = [];
        $existing_items = [];

        if ($is_new_approval) {
            $has_approved_items = $db
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', (int) $id)
                ->where('qty_disetujui >', 0)
                ->countAllResults() > 0;
            if (!$has_approved_items) {
                session()->setFlashdata(
                    'error',
                    'Isi qty disetujui pada detail permintaan terlebih dahulu sebelum menyetujui.',
                );
                return redirect()->back();
            }

            // Register barang baru ke master
            $this->register_barang_baru((int) $id);

            // Determine: ada barang baru dan/atau existing?
            $all_details = $this->guarded(
                $db
                    ->table('inventori_non_medis.permintaan_barang_detail d')
                    ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                    ->select('d.id_detail, d.id_barang, d.qty_disetujui, b.stok, b.nama_barang')
                    ->where('d.id_permintaan', (int) $id)
                    ->where('d.id_barang >', 0)
                    ->where('d.qty_disetujui >', 0)
                    ->get(),
            )->getResultArray();
            /** @var list<array<string, mixed>> $all_details */

            $existing_items = [];
            $baru_items     = [];
            foreach ($all_details as $d) {
                if ((int) ($d['stok'] ?? 0) > 0) {
                    $existing_items[] = $d;
                } else {
                    $baru_items[] = $d; // stok=0 → barang baru yang just registered
                }
            }

            $has_existing = count($existing_items) > 0;
            $has_baru     = count($baru_items) > 0;

            // Validasi stok hanya untuk item existing
            if ($has_existing) {
                foreach ($existing_items as $d) {
                    if ((float) $d['qty_disetujui'] > (float) $d['stok']) {
                        session()->setFlashdata(
                            'error',
                            "Stok {$d['nama_barang']} tidak cukup: tersedia {$d['stok']}, diminta {$d['qty_disetujui']}.",
                        );
                        return redirect()->back();
                    }
                }
            }
        }

        // Determine final status
        $postData = [
            'petugas_gudang' => $this->request->getPost('petugas_gudang') ?: null,
        ];

        if ($is_new_approval) {
            $postData['tanggal_diproses'] = date('Y-m-d H:i:s');

            if ($has_baru && !$has_existing) {
                // ALL items are barang baru → Menunggu Pengadaan
                $postData['id_status_permintaan_barang'] = 5;
            } elseif ($has_baru && $has_existing) {
                // Mixed: proses existing sekarang, barang baru pending
                // Status tetap "Disetujui" untuk existing, tapi juga trigger pengajuan
                $postData['id_status_permintaan_barang'] = 5; // pending sampai semua terpenuhi
            } else {
                // All existing, tidak perlu pengadaan → langsung Selesai (6)
                $postData['id_status_permintaan_barang'] = 6;
            }

            // Generate no_keluar hanya jika ada existing items yang dikirim
            if ($has_existing) {
                helper('autonomor');
                /** @var string|null $lastNo */
                $lastNo                = $this->get_last(
                    'inventori_non_medis.permintaan_barang',
                    'no_keluar',
                    'id_permintaan',
                );
                $postData['no_keluar'] = generateNextNoKeluarBarang($lastNo);
                $this->pending_keluar  = true;
            }
        } elseif ($new_status === 3) {
            // Ditolak
            $postData['id_status_permintaan_barang'] = 3;
            $postData['tanggal_diproses']            = date('Y-m-d H:i:s');
        } else {
            $postData['id_status_permintaan_barang'] = $new_status;
        }

        try {
            $this->model->update($id, $postData);
            session()->setFlashdata('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
            return redirect()->back();
        }

        // Buat transaksi stok keluar untuk item existing
        if ($this->pending_keluar) {
            $this->pending_keluar = false;
            $saved                = $this->model->find((int) $id);
            if (is_array($saved) && (bool) $saved['no_keluar']) {
                try {
                    $this->create_transaksi_stok_keluar_existing(
                        (int) $id,
                        (string) $saved['no_keluar'],
                        $existing_items ?? [],
                    );
                } catch (\Throwable $e) {
                    log_message('error', '[Approval] create_transaksi_stok_keluar: ' . $e->getMessage());
                    session()->setFlashdata(
                        'error',
                        'Status berhasil disetujui, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                    );
                }
            }
        }

        // Auto-create Pengajuan untuk barang baru
        if ($is_new_approval && $has_baru) {
            try {
                $no_pengajuan = $this->auto_create_pengajuan((int) $id, $baru_items);
                session()->setFlashdata(
                    'success',
                    "Permintaan disetujui. Pengajuan {$no_pengajuan} otomatis dibuat untuk barang baru, menunggu persetujuan atasan logistik.",
                );
            } catch (\Throwable $e) {
                log_message('error', '[AutoPengajuan] ' . $e->getMessage());
                session()->setFlashdata(
                    'error',
                    'Disetujui, namun gagal membuat pengajuan otomatis: ' . $e->getMessage(),
                );
            }
        }

        return $this->home();
    }

    /**
     * Auto-insert barang baru ke master saat disetujui.
     * Update detail row: set id_barang = id baru, clear nama_barang_baru.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    private function register_barang_baru(int $id_permintaan): void
    {
        $db = $this->get_db();

        $baru_items = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->select('id_detail, nama_barang_baru, id_satuan_baru, id_jenis_barang_baru, qty_disetujui')
                ->where('id_permintaan', $id_permintaan)
                ->where('id_barang IS NULL')
                ->where('nama_barang_baru IS NOT NULL')
                ->where('qty_disetujui >', 0)
                ->get(),
        )->getResultArray();
        /** @var list<array<string, mixed>> $baru_items */

        if (count($baru_items) === 0)
            return;

        helper('autonomor');

        foreach ($baru_items as $item) {
            // Generate kode barang otomatis
            $lastKode = $this->guarded(
                $db
                    ->table('inventori_non_medis.barang')
                    ->select('kode_barang')
                    ->orderBy('id_barang', 'DESC')
                    ->limit(1)
                    ->get(),
            )->getRowArray();
            /** @var array<string, mixed>|null $lastKode */
            $kodeLama = $lastKode['kode_barang'] ?? null;
            /** @var string|null $kodeLama */
            $kode = generateNextKodeBarang($kodeLama);

            // Insert ke master barang
            $db->table('inventori_non_medis.barang')->insert([
                'kode_barang'     => $kode,
                'nama_barang'     => $item['nama_barang_baru'],
                'id_satuan'       => (int) ($item['id_satuan_baru'] ?? 0) > 0 ? (int) $item['id_satuan_baru'] : null,
                'id_jenis_barang' => (int) ($item['id_jenis_barang_baru'] ?? 0) > 0
                    ? (int) $item['id_jenis_barang_baru']
                    : null,
                'stok'            => 0,
                'stok_minimum'    => 0,
            ]);
            $new_id_barang = (int) $db->insertID();

            // Update detail row — link ke master baru
            $db
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_detail', (int) $item['id_detail'])
                ->update([
                    'id_barang'        => $new_id_barang,
                    'nama_barang_baru' => null,
                ]);
        }
    }

    // cek stok cukup untuk semua item yang disetujui (unused now, kept for reference)
    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function validate_stock(int $id): null|string
    {
        $details = $this->guarded(
            $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail d')
                ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
                ->select('d.id_barang, d.qty_disetujui, b.stok, b.nama_barang')
                ->where('d.id_permintaan', $id)
                ->where('d.id_barang >', 0)
                ->where('d.qty_disetujui >', 0)
                ->get(),
        )->getResultArray();
        /** @var list<array<string, mixed>> $details */

        if (count($details) === 0) {
            return null;
        }

        foreach ($details as $d) {
            if ((float) $d['qty_disetujui'] > (float) ($d['stok'] ?? 0)) {
                return "Stok {$d['nama_barang']} tidak cukup: tersedia {$d['stok']}, diminta {$d['qty_disetujui']}.";
            }
        }
        return null;
    }

    /**
     * Transaksi stok keluar HANYA untuk item existing (stok > 0).
     *
     * @param list<array<string, mixed>> $existing_items
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function create_transaksi_stok_keluar_existing(int $id, string $no_keluar, array $existing_items): void
    {
        if (count($existing_items) === 0)
            return;

        $db = $this->get_db();

        $row = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang pb')
                ->join('ruangan.ruangan r', 'pb.master_ruangan = r.id_ruangan', 'left')
                ->join('role.petugas p_pemohon', 'pb.petugas = p_pemohon.id_petugas', 'left')
                ->join('person.orang o_pemohon', 'p_pemohon.id_orang = o_pemohon.id_orang', 'left')
                ->join('role.petugas p_petugas_gudang', 'pb.petugas_gudang = p_petugas_gudang.id_petugas', 'left')
                ->join('person.orang o_petugas_gudang', 'p_petugas_gudang.id_orang = o_petugas_gudang.id_orang', 'left')
                ->select(
                    'pb.no_permintaan, r.nama_ruangan, o_pemohon.nama AS nama_pemohon, o_petugas_gudang.nama AS nama_petugas_gudang',
                )
                ->where('pb.id_permintaan', $id)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $row */

        $keterangan = trim(implode(', ', array_filter([
            (string) ($row['no_permintaan'] ?? ''),
            (string) ($row['nama_ruangan'] ?? '') !== '' ? 'Ruangan ' . (string) $row['nama_ruangan'] : '',
            (string) ($row['nama_pemohon'] ?? '') !== '' ? 'Pemohon: ' . (string) $row['nama_pemohon'] : '',
            (string) ($row['nama_petugas_gudang'] ?? '') !== ''
                ? 'Petugas Gudang: ' . (string) $row['nama_petugas_gudang']
                : '',
        ])));

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 2,
            'tanggal'                => $now,
            'id_permintaan'          => $id,
            'keterangan'             => $keterangan,
        ]);
        $id_transaksi = (int) $db->insertID();

        foreach ($existing_items as $d) {
            $qty          = (int) round((float) $d['qty_disetujui']);
            $stok_sebelum = (int) ($d['stok'] ?? 0);

            $harga = $this->guarded(
                $db
                    ->table('inventori_non_medis.barang')
                    ->select('harga_satuan')
                    ->where('id_barang', (int) $d['id_barang'])
                    ->get(),
            )->getRowArray();
            /** @var array<string, mixed>|null $harga */

            $db->table('inventori_non_medis.transaksi_stok_detail')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang'    => (int) $d['id_barang'],
                'qty'          => $qty,
                'harga_satuan' => isset($harga['harga_satuan']) && (float) $harga['harga_satuan'] > 0
                    ? $harga['harga_satuan']
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

        $db->transCommit();
    }

    /**
     * Auto-create Pengajuan Barang dari item barang baru yang disetujui.
     *
     * @param list<array<string, mixed>> $baru_items
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     */
    private function auto_create_pengajuan(int $id_permintaan, array $baru_items): string
    {
        if (count($baru_items) === 0)
            return '';

        $db = $this->get_db();
        helper('autonomor');

        $now = date('Y-m-d H:i:s');

        $row = $this->guarded(
            $db
                ->table('inventori_non_medis.pengajuan_barang')
                ->select('no_pengajuan')
                ->orderBy('id_pengajuan', 'DESC')
                ->limit(1)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $row */
        $lastNo = $row['no_pengajuan'] ?? null;
        /** @var string|null $lastNo */

        $no_pengajuan = generateNextNoPengajuanBarang($lastNo, $now);

        // Get petugas_gudang dari permintaan (yang approve)
        $permintaan = $this->guarded(
            $db
                ->table('inventori_non_medis.permintaan_barang')
                ->select('petugas_gudang')
                ->where('id_permintaan', $id_permintaan)
                ->get(),
        )->getRowArray();
        /** @var array<string, mixed>|null $permintaan */

        $db->transBegin();

        $db->table('inventori_non_medis.pengajuan_barang')->insert([
            'no_pengajuan'               => $no_pengajuan,
            'tanggal'                    => $now,
            'petugas_gudang'             => $permintaan['petugas_gudang'] ?? null,
            'id_status_pengajuan_barang' => 4, // Proses Pengajuan
            'id_permintaan' => $id_permintaan,
        ]);
        $id_pengajuan = (int) $db->insertID();

        foreach ($baru_items as $item) {
            $db->table('inventori_non_medis.pengajuan_barang_detail')->insert([
                'id_pengajuan' => $id_pengajuan,
                'id_barang'    => (int) $item['id_barang'],
                'qty'          => (int) $item['qty_disetujui'],
                'harga'        => null,
            ]);
        }

        $db->transCommit();

        return $no_pengajuan;
    }
}
