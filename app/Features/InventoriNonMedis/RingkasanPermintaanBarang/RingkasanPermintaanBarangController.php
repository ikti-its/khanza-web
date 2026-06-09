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
                ['Inventori Non Medis',        'inventori_non_medis'],
                ['Ringkasan Permintaan Barang', 'ringkasan_permintaan_barang'],
            ],
            'Ringkasan Permintaan Barang',
            [
                A::READ,
                A::UPDATE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan',               'ID'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_permintaan',               'No. Permintaan'],
                [TABLE_ONLY, REQUIRED, I::DATE,   'tanggal',                     'Tanggal'],
                [TABLE_ONLY, REQUIRED, I::SELECT, 'master_ruangan',              'Ruangan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::SELECT, 'petugas_gudang',              'Petugas Gudang'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_keluar',                   'No. Keluar'],
                [TABLE_ONLY, OPTIONAL, I::DATE,   'tanggal_disetujui',           'Tgl. Disetujui'],
            ],
            child_path: '/inventori-non-medis/ringkasan-permintaan-barang-detail',
            child_fk:   'id_permintaan',
        );
    }

    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_permintaan_barang'] ?? 0);
        if ($new_status !== 2) return;

        $current = $this->model->find($id);
        if (!is_array($current)) return;
        if ((int) ($current['id_status_permintaan_barang'] ?? 0) === 2) return;

        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.permintaan_barang', 'no_keluar', 'id_permintaan');
        $postData['no_keluar']         = generateNextNoKeluarBarang($lastNo);
        $postData['tanggal_disetujui'] = date('Y-m-d H:i:s');
        $this->pending_keluar          = true;
    }

    public function update(int|string $id): string|RedirectResponse
    {
        $new_status = (int) ($this->request->getPost('id_status_permintaan_barang') ?? 0);
        $current    = $this->model->find((int) $id);
        $is_new_approval = $new_status === 2
            && is_array($current)
            && (int) ($current['id_status_permintaan_barang'] ?? 0) !== 2;

        if ($is_new_approval) {
            if (empty($this->request->getPost('petugas_gudang'))) {
                session()->setFlashdata('error', 'Petugas gudang wajib diisi sebelum menyetujui permintaan.');
                return $this->home();
            }

            $has_approved_items = $this->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', (int) $id)
                ->where('qty_disetujui >', 0)
                ->countAllResults() > 0;
            if (!$has_approved_items) {
                session()->setFlashdata('error', 'Isi qty disetujui pada detail permintaan terlebih dahulu sebelum menyetujui.');
                return $this->home();
            }

            $error = $this->validate_stock((int) $id);
            if ($error !== null) {
                session()->setFlashdata('error', $error);
                return $this->home();
            }
        }

        $result = parent::update($id);

        if ($this->pending_keluar) {
            $this->pending_keluar = false;
            $saved = $this->model->find((int) $id);
            if (is_array($saved) && !empty($saved['no_keluar'])) {
                try {
                    $this->create_transaksi_stok_keluar((int) $id, (string) $saved['no_keluar']);
                } catch (\Throwable $e) {
                    log_message('error', '[Approval] create_transaksi_stok_keluar: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Status berhasil disetujui, namun gagal membuat transaksi stok: ' . $e->getMessage());
                }
            }
        }

        return $result;
    }

    private function validate_stock(int $id): ?string
    {
        $details = $this->get_db()
            ->table('inventori_non_medis.permintaan_barang_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->select('d.id_barang, d.qty_disetujui, b.stok, b.nama_barang')
            ->where('d.id_permintaan', $id)
            ->where('d.id_barang >', 0)
            ->where('d.qty_disetujui >', 0)
            ->get()->getResultArray();

        foreach ($details as $d) {
            if ((float) $d['qty_disetujui'] > (float) ($d['stok'] ?? 0)) {
                return "Stok {$d['nama_barang']} tidak cukup: tersedia {$d['stok']}, diminta {$d['qty_disetujui']}.";
            }
        }
        return null;
    }

    private function create_transaksi_stok_keluar(int $id, string $no_keluar): void
    {
        $db = $this->get_db();

        $row = $db->table('inventori_non_medis.permintaan_barang pb')
            ->join('ruangan.ruangan r', 'pb.master_ruangan = r.id_ruangan', 'left')
            ->join('role.petugas p',    'pb.petugas_gudang = p.id_petugas', 'left')
            ->join('person.orang o',    'p.id_orang = o.id_orang',          'left')
            ->select('pb.no_permintaan, r.nama_ruangan, o.nama')
            ->where('pb.id_permintaan', $id)
            ->get()->getRowArray();

        $keterangan = trim(implode(', ', array_filter([
            $no_keluar,
            $row['no_permintaan']  ?? '',
            ($row['nama_ruangan'] ?? '') !== '' ? 'Ruangan ' . $row['nama_ruangan'] : '',
            ($row['nama'] ?? '')         !== '' ? 'oleh ' . $row['nama']            : '',
        ])));

        $details = $db->table('inventori_non_medis.permintaan_barang_detail')
            ->select('id_barang, qty_disetujui')
            ->where('id_permintaan', $id)
            ->where('id_barang >', 0)
            ->where('qty_disetujui >', 0)
            ->get()->getResultArray();

        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        $db->table('inventori_non_medis.transaksi_stok')->insert([
            'id_tipe_transaksi_stok' => 2,
            'tanggal'                => $now,
            'id_permintaan'          => $id,
            'keterangan'             => $keterangan,
        ]);
        $id_transaksi = $db->insertID();

        foreach ($details as $d) {
            $qty = (int) round((float) $d['qty_disetujui']);
            $db->table('inventori_non_medis.transaksi_stok_detail')->insert([
                'id_transaksi' => $id_transaksi,
                'id_barang'    => (int) $d['id_barang'],
                'qty'          => $qty,
            ]);
            $db->table('inventori_non_medis.barang')
                ->where('id_barang', (int) $d['id_barang'])
                ->set('stok', 'stok - ' . $qty, false)
                ->update();
        }

        $db->transCommit();
    }
}
