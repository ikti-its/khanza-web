<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengadaanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengadaanBarangController extends ControllerTemplate
{
    private ?string $new_no_pengadaan = null;

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
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengadaan',                'ID'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'no_pengajuan',                'No. Pengajuan'],
                [FORM_ONLY,  REQUIRED, I::SELECT,   'id_pengajuan',                'Pengajuan'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengadaan',                'No. Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'total_harga',                 'Total Harga'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'total_harga',                 'Total Harga'],
                [SHOW,       OPTIONAL, I::SELECT,   'id_suplier',                  'Suplier'],
                [SHOW,       REQUIRED, I::DTIME,    'tanggal',                     'Tanggal Pengadaan'],
                [TABLE_ONLY, OPTIONAL, I::SELECT,   'id_status_pengadaan_barang',   'Status'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'nama_status_pengadaan_barang', 'Status'],
                [SHOW,       OPTIONAL, I::TEXT,     'catatan',                     'Catatan'],
            ],
            child_path: '/inventori-non-medis/detail-pengadaan-barang',
            child_fk:   'id_pengadaan',
        );
    }

    // tampilkan status sebagai teks "Diproses" (readonly) pada form tambah
    public function create_page(): string
    {
        $konfig = $this->get_fields_with_options(false, true);
        $baris  = array_fill_keys(array_column($konfig, 2), null);
        $baris['nama_status_pengadaan_barang'] = 'Diproses';

        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon', 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig,
            'baris'       => $baris,
            'form_action' => '/submittambah/',
        ]);
    }

    // auto no_pengadaan + status awal = 1, simpan nomor buat populate detail
    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengadaan_barang', 'no_pengadaan', 'id_pengadaan');
        $postData['no_pengadaan']               = generateNextNoPengadaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_pengadaan_barang'] = 1;
        $this->new_no_pengadaan                 = $postData['no_pengadaan'];
    }

    // kalau create berhasil, populate detail dari item pengajuan
    public function create(): string|RedirectResponse
    {
        // blokir jika pengajuan masih punya barang baru yang belum dipetakan ke master
        $id_pengajuan = (int) ($this->request->getPost('id_pengajuan') ?? 0);
        if ($id_pengajuan > 0) {
            $unmapped = $this->get_db()
                ->table('inventori_non_medis.pengajuan_barang_detail')
                ->where('id_pengajuan', $id_pengajuan)
                ->where('id_barang IS NULL')
                ->where('qty_disetujui >', 0)
                ->countAllResults();
            if ($unmapped > 0) {
                session()->setFlashdata('error', 'Terdapat barang baru pada pengajuan yang belum dipetakan ke master barang. Petakan terlebih dahulu melalui menu Ringkasan Pengajuan Barang.');
                return $this->home();
            }
        }

        $result = parent::create();

        if ($result instanceof RedirectResponse && $this->new_no_pengadaan !== null) {
            $row = $this->get_db()
                ->table('inventori_non_medis.pengadaan_barang')
                ->select('id_pengadaan')
                ->where('no_pengadaan', $this->new_no_pengadaan)
                ->get()->getRowArray();

            $this->new_no_pengadaan = null;

            if (is_array($row)) {
                $this->auto_populate_detail((int) $row['id_pengadaan']);
            }
        }

        return $result;
    }

    // copy item dari pengajuan ke detail pengadaan baru (qty = 0, user isi manual)
    private function auto_populate_detail(int $id_pengadaan): void
    {
        $db = $this->get_db();

        $pengadaan = $db->table('inventori_non_medis.pengadaan_barang')
            ->select('id_pengajuan')
            ->where('id_pengadaan', $id_pengadaan)
            ->get()->getRowArray();

        if (!is_array($pengadaan)) return;

        $id_pengajuan = (int) ($pengadaan['id_pengajuan'] ?? 0);
        if ($id_pengajuan === 0) return;

        $items = $db->table('inventori_non_medis.pengajuan_barang_detail')
            ->select('id_barang, harga')
            ->where('id_pengajuan', $id_pengajuan)
            ->where('id_barang >', 0)
            ->where('qty_disetujui >', 0)
            ->get()->getResultArray();

        foreach ($items as $item) {
            $db->table('inventori_non_medis.pengadaan_barang_detail')->insert([
                'id_pengadaan' => $id_pengadaan,
                'id_barang'    => (int) $item['id_barang'],
                'qty'          => 0,
                'harga_satuan' => isset($item['harga']) && (float) $item['harga'] > 0 ? $item['harga'] : null,
                'subtotal'     => null,
            ]);
        }
    }

    // cetak surat pemesanan
    public function print(int|string $id): string
    {
        $db = $this->get_db();

        $header = $db->table('inventori_non_medis.pengadaan_barang pb')
            ->join('inventori_non_medis.suplier s',           'pb.id_suplier = s.id_suplier',       'left')
            ->join('finansial.rekening r',                    's.id_rekening = r.id_rekening',       'left')
            ->join('finansial.bank bk',                       'r.bank = bk.id_bank',                'left')
            ->join('inventori_non_medis.pengajuan_barang pj', 'pb.id_pengajuan = pj.id_pengajuan',   'left')
            ->join('role.petugas pt',                         'pj.petugas_gudang = pt.id_petugas',  'left')
            ->join('person.orang o',                          'pt.id_orang = o.id_orang',            'left')
            ->select('pb.no_pengadaan, pb.tanggal, pb.catatan,
                      s.nama_suplier, s.no_telp, s.alamat,
                      r.nomor_rekening, r.nama_akun, bk.nama_bank,
                      pj.no_pengajuan, o.nama AS nama_petugas')
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
