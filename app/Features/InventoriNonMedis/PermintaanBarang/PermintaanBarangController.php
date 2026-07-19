<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PermintaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PermintaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Permintaan Barang',   'permintaan_barang'],
            ],
            'Permintaan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_permintaan', 'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_permintaan', 'No. Permintaan'],
                [SHOW, REQUIRED, I::DTIME, 'tanggal', 'Tanggal Permintaan'],
                [SHOW, REQUIRED, I::MODAL, 'petugas', 'Pemohon', ['modal' => 'modalPemohon', 'display_column' => 'nama', 'placeholder' => 'Klik cari pemohon...']],
                [SHOW, REQUIRED, I::MODAL, 'master_ruangan', 'Ruangan', ['modal' => 'modalPilihRuangan', 'display_column' => 'nama_ruangan', 'placeholder' => 'Klik cari ruangan...']],
                [SHOW, OPTIONAL, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME, 'tanggal_diproses', 'Tanggal Diproses'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'tanggal_diproses', 'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang', 'Pengelola'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang_nama', 'Pengelola'],
                [SHOW, OPTIONAL, I::READONLY, 'no_keluar', 'No. Keluar'],
            ],
            // child_path: '/inventori-non-medis/detail-permintaan-barang',
            // child_fk: 'id_permintaan',
        );
    }

    // data terbaru di atas
    protected function before_read(): void
    {
        $this->model->set_order('id_permintaan', 'DESC');
    }

    // form tambah: 1-page header + detail
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_permintaan_barang', [
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
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_permintaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_permintaan_barang', [
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
        if (is_array($baris) && (int) ($baris['id_status_permintaan_barang'] ?? 0) !== 1) {
            return $this->detail($id);
        }

        $detail_items = $this->get_db()
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_detail, d.id_barang, d.qty, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_permintaan', (int) $id)
            ->where('d.id_barang >', 0)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/tambah_permintaan_barang', [
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
            'petugas'        => $this->request->getPost('petugas') ?: null,
            'master_ruangan' => $this->request->getPost('master_ruangan') ?: null,
        ];

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.permintaan_barang', 'no_permintaan', 'id_permintaan');
        $postData['no_permintaan']               = generateNextNoPermintaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_permintaan_barang'] = (int) ($this->request->getPost('id_status_permintaan_barang') ?? 1);

        // validasi: jika status Proses Permintaan (4), item wajib ada
        $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
        if ($postData['id_status_permintaan_barang'] === 4 && empty($detail_ids)) {
            session()->setFlashdata('error', 'Tambahkan detail barang terlebih dahulu sebelum mengajukan permintaan.');
            return $this->home();
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->insert($postData);
            $id_permintaan = (int) $db->insertID();

            // simpan detail items
            $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
            $detail_qty = $this->request->getPost('detail_qty') ?? [];

            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang = (int) ($detail_ids[$i] ?? 0);
                $qty       = (int) ($detail_qty[$i] ?? 0);
                if ($id_barang > 0 && $qty > 0) {
                    $db->table('inventori_non_medis.permintaan_barang_detail')->insert([
                        'id_permintaan' => $id_permintaan,
                        'id_barang'     => $id_barang,
                        'qty'           => $qty,
                    ]);
                }
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data Permintaan Barang berhasil disimpan.');
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
        if (is_array($current) && (int) ($current['id_status_permintaan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat dihapus.');
            return $this->home();
        }

        $db = $this->get_db();
        try {
            $db->transBegin();
            $db->table('inventori_non_medis.permintaan_barang_detail')->where('id_permintaan', (int) $id)->delete();
            $this->model->delete($id);
            $db->transCommit();
            session()->setFlashdata('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return $this->home();
    }

    // update header + sync detail items
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_permintaan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah.');
            return $this->home();
        }

        $postData = [
            'tanggal'                       => $this->request->getPost('tanggal'),
            'petugas'                       => $this->request->getPost('petugas') ?: null,
            'master_ruangan'                => $this->request->getPost('master_ruangan') ?: null,
            'id_status_permintaan_barang'   => $this->request->getPost('id_status_permintaan_barang') ?? 1,
        ];

        // validasi status
        $new_status = (int) ($postData['id_status_permintaan_barang'] ?? 0);
        if (!in_array($new_status, [1, 4], true)) {
            $postData['id_status_permintaan_barang'] = (int) ($current['id_status_permintaan_barang'] ?? 1);
        }

        // cek detail sebelum proses permintaan
        $detail_ids = $this->request->getPost('detail_id_barang') ?? [];
        if ($new_status === 4 && empty($detail_ids)) {
            session()->setFlashdata('error', 'Tambahkan detail barang terlebih dahulu sebelum mengajukan permintaan.');
            return $this->home();
        }

        $db = $this->get_db();

        try {
            $db->transBegin();

            $this->model->update($id, $postData);

            // sync detail: hapus semua lalu insert ulang
            $db->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', (int) $id)
                ->delete();

            $detail_qty = $this->request->getPost('detail_qty') ?? [];
            for ($i = 0; $i < count($detail_ids); $i++) {
                $id_barang = (int) ($detail_ids[$i] ?? 0);
                $qty       = (int) ($detail_qty[$i] ?? 0);
                if ($id_barang > 0 && $qty > 0) {
                    $db->table('inventori_non_medis.permintaan_barang_detail')->insert([
                        'id_permintaan' => (int) $id,
                        'id_barang'     => $id_barang,
                        'qty'           => $qty,
                    ]);
                }
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Data Permintaan Barang berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui: ' . $e->getMessage());
        }

        return $this->home();
    }
}
