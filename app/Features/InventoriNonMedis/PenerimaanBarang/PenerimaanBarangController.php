<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PenerimaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PenerimaanBarangController extends ControllerTemplate
{
    private bool    $pending_masuk      = false;
    private ?string $new_no_penerimaan  = null;

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
                // A::AUDIT,
                A::UPDATE,
                // A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,    'id_penerimaan',               'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_penerimaan',               'No. Penerimaan'],
                [SHOW, REQUIRED, I::SELECT,   'id_pengadaan',                'No. Pengadaan'],
                [SHOW, REQUIRED, I::DTIME,    'tanggal',                     'Tanggal Terima'],
                [SHOW, REQUIRED, I::SELECT,   'id_status_penerimaan_barang', 'Status'],
                [SHOW, OPTIONAL, I::READONLY, 'no_masuk',                    'No. Masuk'],
                [SHOW, OPTIONAL, I::READONLY, 'tanggal_diterima',            'Tgl. Diterima'],
                [SHOW, OPTIONAL, I::TEXT,     'catatan',                     'Catatan'],
            ],
            child_path: '/inventori-non-medis/penerimaan-barang-detail',
            child_fk:   'id_penerimaan',
        );
    }

    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.penerimaan_barang', 'no_penerimaan', 'id_penerimaan');
        $postData['no_penerimaan']               = generateNextNoPenerimaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_penerimaan_barang'] = 1;
        $postData['status']                      = '-';
        $this->new_no_penerimaan                 = $postData['no_penerimaan'];
    }

    public function create(): string|RedirectResponse
    {
        $result = parent::create();

        if ($result instanceof RedirectResponse && $this->new_no_penerimaan !== null) {
            $row = $this->get_db()
                ->table('inventori_non_medis.penerimaan_barang')
                ->select('id_penerimaan')
                ->where('no_penerimaan', $this->new_no_penerimaan)
                ->get()->getRowArray();

            $this->new_no_penerimaan = null;

            if (is_array($row)) {
                $this->auto_populate_detail((int) $row['id_penerimaan']);
            }
        }

        return $result;
    }

    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_penerimaan_barang'] ?? 0);
        if ($new_status !== 2) return;

        $current = $this->model->find($id);
        if (!is_array($current)) return;
        if ((int) ($current['id_status_penerimaan_barang'] ?? 0) === 2) return;

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.penerimaan_barang', 'no_masuk', 'id_penerimaan');
        $postData['no_masuk']         = generateNextNoMasukBarang($lastNo);
        $postData['tanggal_diterima'] = date('Y-m-d H:i:s');
        $this->pending_masuk          = true;
    }

    public function update(int|string $id): string|RedirectResponse
    {
        $new_status     = (int) ($this->request->getPost('id_status_penerimaan_barang') ?? 0);
        $current        = $this->model->find((int) $id);
        $is_new_lengkap = $new_status === 2
            && is_array($current)
            && (int) ($current['id_status_penerimaan_barang'] ?? 0) !== 2;

        if ($is_new_lengkap) {
            $error = $this->validate_penerimaan((int) $id);
            if ($error !== null) {
                session()->setFlashdata('error', $error);
                return $this->home();
            }
        }

        $result = parent::update($id);

        if ($this->pending_masuk) {
            $this->pending_masuk = false;
            $saved = $this->model->find((int) $id);
            if (is_array($saved) && !empty($saved['no_masuk'])) {
                try {
                    $this->create_transaksi_stok_masuk((int) $id, (string) $saved['no_masuk']);
                    $this->update_pengadaan_status((int) ($saved['id_pengadaan'] ?? 0));
                } catch (\Throwable $e) {
                    log_message('error', '[Penerimaan] create_transaksi_stok_masuk: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Status berhasil diubah, namun gagal membuat transaksi stok: ' . $e->getMessage());
                }
            }
        }

        return $result;
    }

    private function validate_penerimaan(int $id): ?string
    {
        $has_items = $this->get_db()
            ->table('inventori_non_medis.penerimaan_barang_detail')
            ->where('id_penerimaan', $id)
            ->where('id_barang >', 0)
            ->where('qty_diterima >', 0)
            ->countAllResults() > 0;

        return $has_items ? null : 'Isi detail penerimaan terlebih dahulu sebelum mengubah status menjadi Lengkap.';
    }

    private function auto_populate_detail(int $id_penerimaan): void
    {
        $db = $this->get_db();

        $penerimaan = $db->table('inventori_non_medis.penerimaan_barang')
            ->select('id_pengadaan')
            ->where('id_penerimaan', $id_penerimaan)
            ->get()->getRowArray();

        if (!is_array($penerimaan)) return;

        $id_pengadaan = (int) ($penerimaan['id_pengadaan'] ?? 0);
        if ($id_pengadaan === 0) return;

        $items = $db->table('inventori_non_medis.pengadaan_barang_detail')
            ->select('id_barang, harga_satuan')
            ->where('id_pengadaan', $id_pengadaan)
            ->where('id_barang >', 0)
            ->get()->getResultArray();

        foreach ($items as $item) {
            $db->table('inventori_non_medis.penerimaan_barang_detail')->insert([
                'id_penerimaan' => $id_penerimaan,
                'id_barang'     => (int) $item['id_barang'],
                'qty_diterima'  => 0,
                'harga_satuan'  => $item['harga_satuan'] ?? null,
            ]);
        }
    }

    private function create_transaksi_stok_masuk(int $id, string $no_masuk): void
    {
        $db = $this->get_db();

        $row = $db->table('inventori_non_medis.penerimaan_barang pb')
            ->join('inventori_non_medis.pengadaan_barang pg', 'pb.id_pengadaan = pg.id_pengadaan', 'left')
            ->join('inventori_non_medis.suplier s',           'pg.id_suplier = s.id_suplier',       'left')
            ->select('s.nama_suplier')
            ->where('pb.id_penerimaan', $id)
            ->get()->getRowArray();

        $keterangan = trim(implode(', ', array_filter([
            $no_masuk,
            ($row['nama_suplier'] ?? '') !== '' ? 'Suplier ' . $row['nama_suplier'] : '',
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
