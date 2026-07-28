<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\RingkasanPengajuanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class RingkasanPengajuanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new RingkasanPengajuanBarangModel(),
            [
                ['Inventori Non Medis',        'inventori_non_medis'],
                ['Ringkasan Pengajuan Barang', 'ringkasan_pengajuan_barang'],
            ],
            'Ringkasan Pengajuan Barang',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengajuan',               'ID'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengajuan',               'No. Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal',                    'Tanggal Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang', 'Pemohon'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'total_harga',    'Total Harga'],
                [SHOW, REQUIRED, I::SELECT,   'id_status_pengajuan_barang', 'Status'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal_diproses',          'Tanggal Diproses'],
                [FORM_ONLY, REQUIRED, I::MODAL,   'atasan_logistik',            'Pengelola', ['modal' => 'modalPemohon', 'display_column' => 'atasan_logistik_nama', 'placeholder' => 'Klik cari pengelola...']],
            ],
            // child_path: '/inventori-non-medis/ringkasan-pengajuan-barang-detail',
            // child_fk: 'id_pengajuan',
        );
    }

    // hanya tampilkan Proses Pengajuan (4), Disetujui (2), Ditolak (3) — bukan Draf
    protected function before_read(): void
    {
        $this->model->set_filter('id_status_pengajuan_barang', [2, 3, 4]);
        $this->model->set_order('id_pengajuan', 'DESC');
    }

    // hanya izinkan transisi ke Disetujui (2) atau Ditolak (3) dari Ringkasan
    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_pengajuan_barang'] ?? 0);

        if (!in_array($new_status, [2, 3], true)) {
            $current                                = $this->model->find($id);
            $postData['id_status_pengajuan_barang'] = (int) ($current['id_status_pengajuan_barang'] ?? 4);
            return;
        }

        $current = $this->model->find($id);
        if (!is_array($current))
            return;
        if ((int) ($current['id_status_pengajuan_barang'] ?? 0) === 2)
            return;

        $postData['tanggal_diproses'] = date('Y-m-d H:i:s');

        if ($new_status !== 2)
            return;
    }

    // halaman detail (readonly)
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $baris = $this->model->find_one($id);

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.pengajuan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.qty_disetujui, d.harga, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengajuan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_ringkasan_pengajuan_barang', [
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
        $status = is_array($baris) ? (int) ($baris['id_status_pengajuan_barang'] ?? 0) : 0;
        if (in_array($status, [2, 3], true)) {
            return $this->detail($id);
        }

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.pengajuan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.qty_disetujui, d.harga, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_pengajuan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/ubah_ringkasan_pengajuan_barang', [
            'judul'        => 'Ubah ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'   => $this->get_uri_path(),
            'form_action'  => '/submitedit/' . $id,
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }

    // validasi atasan & qty sebelum approve, sync qty_disetujui
    public function update(int|string $id): string|RedirectResponse
    {
        $new_status     = (int) ($this->request->getPost('id_status_pengajuan_barang') ?? 0);
        $current        = $this->model->find((int) $id);
        $current_status = is_array($current) ? (int) ($current['id_status_pengajuan_barang'] ?? 0) : 0;

        // blokir jika sudah Disetujui atau Ditolak
        if (in_array($current_status, [2, 3], true)) {
            session()->setFlashdata('error', 'Pengajuan yang sudah diproses tidak dapat diubah kembali.');
            return $this->home();
        }

        $is_new_approval = $new_status === 2 && $current_status !== 2;

        if ($is_new_approval) {
            if (empty($this->request->getPost('atasan_logistik'))) {
                session()->setFlashdata('error', 'Atasan logistik wajib diisi sebelum menyetujui pengajuan.');
                return $this->home();
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
                $db->table('inventori_non_medis.pengajuan_barang_detail')
                    ->where('id_pengajuan', (int) $id)
                    ->where('id_barang', $id_barang)
                    ->update(['qty_disetujui' => $qty_d]);
            }
        }

        if ($is_new_approval) {
            $detail_items_check = $db->table('inventori_non_medis.pengajuan_barang_detail')
                ->select('id_barang, qty, qty_disetujui')
                ->where('id_pengajuan', (int) $id)
                ->where('id_barang >', 0)
                ->where('qty_disetujui >', 0)
                ->get()->getResultArray();

            $has_approved = !empty($detail_items_check);
            if (!$has_approved) {
                session()->setFlashdata('error', 'Isi qty disetujui pada detail pengajuan sebelum menyetujui.');
                return $this->home();
            }

            // Qty disetujui BOLEH melebihi qty yang diajukan (mis. atasan logistik
            // memutuskan konsolidasi pembelian untuk stok) — siapa & kapan sudah
            // otomatis tercatat lewat atasan_logistik & tanggal_diproses, dan qty
            // vs qty_disetujui sudah cukup transparan tanpa perlu alasan tertulis.
            // Ini satu-satunya titik otoritas yang boleh menaikkan qty — Pengadaan
            // hanya boleh mengeksekusi sebesar yang disetujui di sini.
        }

        // update header — hanya field yang relevan
        $postData = [
            'id_status_pengajuan_barang' => $new_status,
            'atasan_logistik'            => $this->request->getPost('atasan_logistik') ?: null,
        ];

        if (in_array($new_status, [2, 3], true) && $current_status !== 2) {
            $postData['tanggal_diproses'] = date('Y-m-d H:i:s');
        }

        try {
            $this->model->update($id, $postData);
            session()->setFlashdata('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
            return redirect()->back();
        }

        return $this->home();
    }
}
