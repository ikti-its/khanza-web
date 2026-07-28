<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\TransaksiStok;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class TransaksiStokController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TransaksiStokModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Transaksi Stok',      'transaksi_stok'],
            ],
            'Transaksi Stok',
            [
                A::READ,
                A::DETAIL,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX, 'id_transaksi',             'ID'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,  'nama_tipe_transaksi_stok', 'Tipe'],
                [SHOW,       REQUIRED, I::DTIME, 'tanggal',                  'Tanggal'],
                [HIDE, OPTIONAL, I::TEXT,  'no_keluar',                'No. Keluar'],
                [HIDE, OPTIONAL, I::TEXT,  'no_masuk',                 'No. Masuk'],
                [SHOW,       OPTIONAL, I::TEXT,  'keterangan',               'Keterangan'],
            ],
        );
    }

    // data terbaru di atas
    protected function before_read(): void
    {
        $this->model->set_order('id_transaksi', 'DESC');
    }

    // halaman detail (readonly) — custom view konsisten dengan modul lain
    public function detail(int|string $id): string
    {
        if ($id == 0) return $this->index();

        $db = $this->get_db();

        $baris = $db->table('inventori_non_medis.transaksi_stok ts')
            ->join('inventori_non_medis.tipe_transaksi_stok tts', 'ts.id_tipe_transaksi_stok = tts.id_tipe_transaksi_stok', 'left')
            ->join('inventori_non_medis.penerimaan_barang pnb', 'ts.id_penerimaan = pnb.id_penerimaan', 'left')
            ->join('inventori_non_medis.permintaan_barang pmb', 'ts.id_permintaan = pmb.id_permintaan', 'left')
            ->select('ts.*, tts.nama_tipe_transaksi_stok, pnb.no_masuk, pmb.no_keluar')
            ->where('ts.id_transaksi', (int) $id)
            ->get()->getRowArray();

        if (empty($baris)) return $this->index();

        $detail_items = $db->table('inventori_non_medis.transaksi_stok_detail d')
            ->join('inventori_non_medis.barang b', 'd.id_barang = b.id_barang', 'left')
            ->join('inventori_non_medis.satuan s', 'b.id_satuan = s.id_satuan', 'left')
            ->select('d.id_barang, d.qty, d.harga_satuan, d.stok_sebelum, d.stok_sesudah, b.kode_barang, b.nama_barang, s.nama_satuan')
            ->where('d.id_transaksi', (int) $id)
            ->get()->getResultArray();

        return view('admin/inventorinonmedis/detail_transaksi_stok', [
            'judul'        => 'Detail ' . $this->title,
            'breadcrumbs'  => array_merge($this->breadcrumbs, [['title' => 'Detail', 'icon' => 'detail']]),
            'modul_path'   => $this->get_uri_path(),
            'baris'        => $baris,
            'detail_items' => $detail_items,
        ]);
    }
}
