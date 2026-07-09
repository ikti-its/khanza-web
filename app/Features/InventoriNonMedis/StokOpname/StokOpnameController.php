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
                // A::AUDIT,
                A::UPDATE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_opname',             'ID Opname'],
                [SHOW, REQUIRED, I::DTIME,  'tanggal',               'Tanggal'],
                [SHOW, OPTIONAL, I::SELECT, 'id_status_stok_opname', 'Status'],
                [SHOW, REQUIRED, I::SELECT, 'id_petugas',            'Pelaksana'],
                [SHOW, REQUIRED, I::TEXT,   'catatan',               'Catatan'],
            ],
            child_path: '/inventori-non-medis/detail-stok-opname',
            child_fk: 'id_opname',
        );
    }

    // form tambah custom dengan modal search pelaksana
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

    // status awal = 1 (Proses)
    protected function before_create(array &$postData): void
    {
        $postData['id_status_stok_opname'] = 1;

        // convert empty FK modal fields to null
        if (isset($postData['id_petugas']) && $postData['id_petugas'] === '') {
            $postData['id_petugas'] = null;
        }
    }

    // blok kalau udah Selesai, validasi detail, buat transaksi kalau → Selesai
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (!is_array($current)) return $this->home();

        $current_status = (int) ($current['id_status_stok_opname'] ?? 0);

        if ($current_status === 2) {
            session()->setFlashdata('error', 'Stok Opname yang sudah Selesai tidak dapat diubah.');
            return $this->home();
        }

        $new_status      = (int) ($this->request->getPost('id_status_stok_opname') ?? 0);
        $is_new_selesai  = ($new_status === 2 && $current_status !== 2);

        if ($is_new_selesai) {
            $error = $this->validate_before_selesai((int) $id);
            if ($error !== null) {
                session()->setFlashdata('error', $error);
                return $this->home();
            }
        }

        $result = parent::update($id);

        if ($is_new_selesai && $result instanceof RedirectResponse) {
            try {
                $this->create_transaksi_stok_opname((int) $id);
            } catch (\Throwable $e) {
                log_message('error', '[StokOpname] create_transaksi_stok_opname: ' . $e->getMessage());
                session()->setFlashdata('error', 'Status berhasil diubah, namun gagal membuat transaksi stok: ' . $e->getMessage());
            }
        }

        return $result;
    }

    // minimal 1 detail harus ada sebelum bisa Selesai
    private function validate_before_selesai(int $id): ?string
    {
        $has_items = $this->get_db()
            ->table('inventori_non_medis.stok_opname_detail')
            ->where('id_opname', $id)
            ->where('id_barang >', 0)
            ->countAllResults() > 0;

        return $has_items
            ? null
            : 'Isi detail stok opname terlebih dahulu sebelum mengubah status menjadi Selesai.';
    }

    // catat penyesuaian stok, update stok barang ke nilai fisik (hanya yang ada selisih)
    private function create_transaksi_stok_opname(int $id_opname): void
    {
        $db = $this->get_db();

        $details = $db->table('inventori_non_medis.stok_opname_detail')
            ->select('id_barang, stok_sistem, stok_fisik, selisih')
            ->where('id_opname', $id_opname)
            ->where('id_barang >', 0)
            ->where('selisih !=', 0)
            ->get()->getResultArray();

        if (empty($details)) return;

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

            $db->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', $stok_fisik)
                ->update();
        }

        $db->transCommit();
    }
}
