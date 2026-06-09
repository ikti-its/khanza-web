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
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengajuan',               'ID'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_pengajuan',               'No. Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::DATE,   'tanggal',                    'Tanggal'],
                [HIDE,       OPTIONAL, I::INDEX,  'petugas_gudang',             'Petugas'],
                [SHOW,       REQUIRED, I::SELECT, 'id_status_pengajuan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::SELECT, 'atasan_logistik',            'Atasan Logistik'],
                [TABLE_ONLY, OPTIONAL, I::DATE,   'tanggal_disetujui',          'Tgl. Disetujui'],
            ],
            child_path: '/inventori-non-medis/ringkasan-pengajuan-barang-detail',
            child_fk:   'id_pengajuan',
        );
    }

    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_pengajuan_barang'] ?? 0);
        if ($new_status !== 2) return;

        $current = $this->model->find($id);
        if (!is_array($current)) return;
        if ((int) ($current['id_status_pengajuan_barang'] ?? 0) === 2) return;

        $postData['tanggal_disetujui'] = date('Y-m-d H:i:s');
    }

    public function update(int|string $id): string|RedirectResponse
    {
        $new_status = (int) ($this->request->getPost('id_status_pengajuan_barang') ?? 0);
        $current    = $this->model->find((int) $id);
        $is_new_approval = $new_status === 2
            && is_array($current)
            && (int) ($current['id_status_pengajuan_barang'] ?? 0) !== 2;

        if ($is_new_approval) {
            if (empty($this->request->getPost('atasan_logistik'))) {
                session()->setFlashdata('error', 'Atasan logistik wajib diisi sebelum menyetujui pengajuan.');
                return $this->home();
            }

            $has_approved = $this->get_db()
                ->table('inventori_non_medis.pengajuan_barang_detail')
                ->where('id_pengajuan', (int) $id)
                ->where('qty_disetujui >', 0)
                ->countAllResults() > 0;
            if (!$has_approved) {
                session()->setFlashdata('error', 'Isi qty disetujui pada detail pengajuan sebelum menyetujui.');
                return $this->home();
            }
        }

        $result = parent::update($id);

        if ($result instanceof RedirectResponse && $is_new_approval) {
            try {
                $this->auto_create_pengadaan((int) $id);
            } catch (\Throwable $e) {
                log_message('error', '[RingkasanPengajuan] auto_create_pengadaan: ' . $e->getMessage());
                session()->setFlashdata('info', 'Pengajuan disetujui, namun gagal membuat data pengadaan otomatis: ' . $e->getMessage());
            }
        }

        return $result;
    }

    private function auto_create_pengadaan(int $id_pengajuan): void
    {
        $db = $this->get_db();

        $exists = $db->table('inventori_non_medis.pengadaan_barang')
            ->where('id_pengajuan', $id_pengajuan)
            ->countAllResults() > 0;
        if ($exists) return;

        helper('autonomor');
        $lastNo       = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $no_pengadaan = generateNextNoPengadaanBarang($lastNo);
        $now          = date('Y-m-d H:i:s');

        $db->table('inventori_non_medis.pengadaan_barang')->insert([
            'no_pengadaan'               => $no_pengadaan,
            'id_pengajuan'               => $id_pengajuan,
            'id_suplier'                 => 0,
            'tanggal'                    => $now,
            'id_status_pengadaan_barang' => 1,
        ]);
        $id_pengadaan = (int) $db->insertID();

        $items = $db->table('inventori_non_medis.pengajuan_barang_detail')
            ->where('id_pengajuan', $id_pengajuan)
            ->where('id_barang >', 0)
            ->where('qty_disetujui >', 0)
            ->get()->getResultArray();

        $total = 0.0;
        foreach ($items as $item) {
            $harga    = (float) ($item['harga'] ?? 0);
            $qty      = (float) ($item['qty_disetujui'] ?? 0);
            $subtotal = $harga * $qty;
            $total   += $subtotal;

            $db->table('inventori_non_medis.pengadaan_barang_detail')->insert([
                'id_pengadaan' => $id_pengadaan,
                'id_barang'    => (int) $item['id_barang'],
                'qty'          => $qty,
                'harga_satuan' => $harga > 0 ? $harga : null,
                'subtotal'     => $subtotal > 0 ? $subtotal : null,
            ]);
        }

        if ($total > 0) {
            $db->table('inventori_non_medis.pengadaan_barang')
                ->where('id_pengadaan', $id_pengadaan)
                ->set('total_harga', $total)
                ->update();
        }
    }
}
