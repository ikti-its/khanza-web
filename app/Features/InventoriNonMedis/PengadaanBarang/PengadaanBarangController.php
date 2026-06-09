<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class PengadaanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengadaanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengadaan Barang',    'pengadaan_barang'],
            ],
            'Pengadaan Barang',
            [
                A::READ,
                A::CREATE,
                A::UPDATE,
                A::DELETE,
                A::PRINT,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengadaan',               'ID Pengadaan'],
                [HIDE,       OPTIONAL, I::INDEX,  'id_pengajuan',               'ID Pengajuan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'no_pengadaan',               'No. Pengadaan'],
                [SHOW,       REQUIRED, I::SELECT, 'id_suplier',                 'Suplier'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal',                    'Tanggal'],
                [SHOW,       OPTIONAL, I::SELECT, 'id_status_pengadaan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::TEXT,   'catatan',                    'Catatan'],
            ],
            child_path: '/inventori-non-medis/detail-pengadaan-barang',
            child_fk:   'id_pengadaan',
        );
    }

    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $postData['no_pengadaan'] = generateNextNoPengadaanBarang($lastNo, $postData['tanggal'] ?? null);
    }

    public function print(int|string $id): string
    {
        $db = $this->get_db();

        $header = $db->table('inventori_non_medis.pengadaan_barang pb')
            ->join('inventori_non_medis.suplier s',           'pb.id_suplier = s.id_suplier',       'left')
            ->join('inventori_non_medis.pengajuan_barang pj', 'pb.id_pengajuan = pj.id_pengajuan',   'left')
            ->select('pb.no_pengadaan, pb.tanggal, pb.catatan, s.nama_suplier, s.no_telp, s.alamat, pj.no_pengajuan')
            ->where('pb.id_pengadaan', (int) $id)
            ->get()->getRowArray();

        if (!is_array($header)) {
            return $this->index();
        }

        $items = $db->table('inventori_non_medis.pengadaan_barang_detail pbd')
            ->join('inventori_non_medis.barang b',  'pbd.id_barang = b.id_barang',   'left')
            ->join('inventori_non_medis.satuan sat', 'b.id_satuan = sat.id_satuan',  'left')
            ->select('b.nama_barang, sat.nama_satuan, pbd.qty, pbd.harga_satuan')
            ->where('pbd.id_pengadaan', (int) $id)
            ->where('pbd.id_barang >', 0)
            ->get()->getResultArray();

        return view('components/cetak/cetak_surat_pemesanan', [
            'header' => $header,
            'items'  => $items,
        ]);
    }
}
