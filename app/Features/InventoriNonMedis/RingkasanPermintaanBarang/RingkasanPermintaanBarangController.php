<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class RingkasanPermintaanBarangController extends ControllerTemplate
{
    private bool $pending_keluar = false;

    public function __construct()
    {
        parent::__construct(
            new RingkasanPermintaanBarangModel(),
            [
                ['Inventori Non Medis',         'inventori_non_medis'],
                ['Ringkasan Permintaan Barang', 'ringkasan_permintaan_barang'],
            ],
            'Ringkasan Permintaan Barang',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_permintaan',               'ID'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_permintaan',               'No. Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal',                     'Tanggal Permintaan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal',                     'Tanggal Permintaan'],
                [SHOW,       REQUIRED, I::SELECT,   'id_status_permintaan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal_diproses',            'Tanggal Diproses'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal_diproses',            'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas',                     'Pemohon'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'petugas_nama',                'Pemohon'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,   'master_ruangan',              'Ruangan'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_ruangan',                'Ruangan'],
                [SHOW,       OPTIONAL, I::MODAL,   'petugas_gudang',              'Pengelola', ['modal' => 'modalPemohon', 'display_column' => 'nama', 'placeholder' => 'Klik cari pengelola...']],
                [SHOW,       OPTIONAL, I::READONLY, 'no_keluar',                   'No. Keluar'],
            ],
            // child_path: '/inventori-non-medis/ringkasan-permintaan-barang-detail',
            // child_fk: 'id_permintaan',
        );
    }

    // hanya tampilkan Proses Permintaan (4), Disetujui (2), Ditolak (3) — bukan Draf
    protected function before_read(): void
    {
        $this->model->set_filter('id_status_permintaan_barang', [2, 3, 4]);
        $this->model->set_order('id_permintaan', 'DESC');
    }

    // hanya izinkan transisi ke Disetujui (2) atau Ditolak (3) dari Ringkasan
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
        $lastNo                = $this->get_last('inventori_non_medis.permintaan_barang', 'no_keluar', 'id_permintaan');
        $postData['no_keluar'] = generateNextNoKeluarBarang($lastNo);
        $this->pending_keluar  = true;
    }

    // halaman detail (readonly)
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.qty_disetujui, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_permintaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_ringkasan_permintaan_barang', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // form ubah: 1-page — redirect jika sudah diproses
    public function update_page(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $baris = $this->model->find_one($id);

        // redirect ke detail jika sudah Disetujui atau Ditolak
        $status = is_array($baris) ? (int) ($baris['id_status_permintaan_barang'] ?? 0) : 0;
        if (in_array($status, [2, 3], true)) {
            return $this->detail($id);
        }

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.qty_disetujui, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_permintaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/ubah_ringkasan_permintaan_barang', [
            'judul'        => 'Ubah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submitedit/' . $id,
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // validasi petugas, qty & stok sebelum approve, sync qty_disetujui, buat transaksi keluar setelah
    public function update(int|string $id): string|RedirectResponse
    {
        $new_status     = (int) ($this->request->getPost('id_status_permintaan_barang') ?? 0);
        $current        = $this->model->find((int) $id);
        $current_status = is_array($current) ? (int) ($current['id_status_permintaan_barang'] ?? 0) : 0;

        // blokir jika sudah Disetujui atau Ditolak
        if (in_array($current_status, [2, 3], true)) {
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah kembali.');
            return $this->home();
        }

        $is_new_approval = $new_status === 2 && $current_status !== 2;

        if ($is_new_approval) {
            if (empty($this->request->getPost('petugas_gudang'))) {
                session()->setFlashdata('error', 'Petugas gudang wajib diisi sebelum menyetujui permintaan.');
                return redirect()->back();
            }
        }

        // sync qty_disetujui from form
        $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
        $detail_qty_disetujui = $this->request->getPost('detail_qty_disetujui') ?? [];

        $db = $this->get_db();
        for ($i = 0; $i < count($detail_ids); $i++) {
            $id_barang = (int) ($detail_ids[$i] ?? 0);
            $qty_d = (int) ($detail_qty_disetujui[$i] ?? 0);
            if ($id_barang > 0) {
                $db->table('inventori_non_medis.permintaan_barang_detail')
                    ->where('id_permintaan', (int) $id)
                    ->where('id_barang', $id_barang)
                    ->update(['qty_disetujui' => $qty_d]);
            }
        }

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

            $error = $this->validate_stock((int) $id);
            if ($error !== null) {
                session()->setFlashdata('error', $error);
                return redirect()->back();
            }
        }

        // update header — hanya field yang relevan
        $postData = [
            'id_status_permintaan_barang' => $new_status,
            'petugas_gudang'              => $this->request->getPost('petugas_gudang') ?: null,
        ];

        // logic before_update: tanggal_diproses + no_keluar saat approve
        if (in_array($new_status, [2, 3], true) && $current_status !== 2) {
            $postData['tanggal_diproses'] = date('Y-m-d H:i:s');
        }

        if ($new_status === 2 && $current_status !== 2) {
            helper('autonomor');
            $lastNo                = $this->get_last('inventori_non_medis.permintaan_barang', 'no_keluar', 'id_permintaan');
            $postData['no_keluar'] = generateNextNoKeluarBarang($lastNo);
            $this->pending_keluar  = true;
        }

        try {
            $this->model->update($id, $postData);
            session()->setFlashdata('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
            return redirect()->back();
        }

        // buat transaksi stok keluar jika disetujui
        if ($this->pending_keluar) {
            $this->pending_keluar = false;
            $saved                = $this->model->find((int) $id);
            if (is_array($saved) && !empty($saved['no_keluar'])) {
                try {
                    $this->create_transaksi_stok_keluar((int) $id, (string) $saved['no_keluar']);
                } catch (\Throwable $e) {
                    log_message('error', '[Approval] create_transaksi_stok_keluar: ' . $e->getMessage());
                    session()->setFlashdata(
                        'error',
                        'Status berhasil disetujui, namun gagal membuat transaksi stok: ' . $e->getMessage(),
                    );
                }
            }
        }

        return $this->home();
    }

    // cek stok cukup untuk semua item yang disetujui
    private function validate_stock(int $id): null|string
    {
        $details = $this
            ->get_db()
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->select('d.id_barang, d.qty_disetujui, b.stok, b.nama_barang')
            ->where('d.id_permintaan', $id)
            ->where('d.id_barang >', 0)
            ->where('d.qty_disetujui >', 0)
            ->get()
            ->getResultArray();

        foreach ($details as $d) {
            if ((float) $d['qty_disetujui'] > (float) ($d['stok'] ?? 0)) {
                return "Stok {$d['nama_barang']} tidak cukup: tersedia {$d['stok']}, diminta {$d['qty_disetujui']}.";
            }
        }
        return null;
    }

    // catat transaksi keluar + kurangi stok barang
    private function create_transaksi_stok_keluar(int $id, string $no_keluar): void
    {
        $db = $this->get_db();

        $row = $db
            ->table('inventori_non_medis.permintaan_barang pb')
            ->join('ruangan.ruangan r', 'pb.master_ruangan = r.id_ruangan', 'left')
            ->join('role.petugas p_pemohon', 'pb.petugas = p_pemohon.id_petugas', 'left')
            ->join('person.orang o_pemohon', 'p_pemohon.id_orang = o_pemohon.id_orang', 'left')
            ->join('role.petugas p_pengelola', 'pb.petugas_gudang = p_pengelola.id_petugas', 'left')
            ->join('person.orang o_pengelola', 'p_pengelola.id_orang = o_pengelola.id_orang', 'left')
            ->select(
                'pb.no_permintaan, r.nama_ruangan, o_pemohon.nama AS nama_pemohon, o_pengelola.nama AS nama_pengelola',
            )
            ->where('pb.id_permintaan', $id)
            ->get()
            ->getRowArray();

        $keterangan = trim(implode(', ', array_filter([
            $row['no_permintaan'] ?? '',
            ($row['nama_ruangan'] ?? '') !== '' ? 'Ruangan ' . $row['nama_ruangan'] : '',
            ($row['nama_pemohon'] ?? '') !== '' ? 'Pemohon: ' . $row['nama_pemohon'] : '',
            ($row['nama_pengelola'] ?? '') !== '' ? 'Pengelola: ' . $row['nama_pengelola'] : '',
        ])));

        $details = $db
            ->table('inventori_non_medis.permintaan_barang_detail pbd')
            ->join('inventori_non_medis.barang b', 'pbd.id_barang = b.id_barang', 'left')
            ->select('pbd.id_barang, pbd.qty_disetujui, b.stok, b.harga_satuan')
            ->where('pbd.id_permintaan', $id)
            ->where('pbd.id_barang >', 0)
            ->where('pbd.qty_disetujui >', 0)
            ->get()
            ->getResultArray();

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 2,
            'tanggal'                => $now,
            'id_permintaan'          => $id,
            'keterangan'             => $keterangan,
        ]);
        $id_transaksi = (int) $db->insertID();

        foreach ($details as $d) {
            $qty          = (int) round((float) $d['qty_disetujui']);
            $stok_sebelum = (int) ($d['stok'] ?? 0);
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

        $db->transCommit();
    }
}
