<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\PengajuanBarang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class PengajuanBarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PengajuanBarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Pengajuan Barang',    'pengajuan_barang'],
            ],
            'Pengajuan Barang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_pengajuan',               'ID'],
                [HIDE,       OPTIONAL, I::INDEX,    'id_permintaan',              'ID Permintaan'],
                [SHOW,       OPTIONAL, I::READONLY, 'no_pengajuan',               'No. Pengajuan'],
                [SHOW,       REQUIRED, I::DTIME,    'tanggal',                    'Tanggal Pengajuan'],
                [SHOW,       REQUIRED, I::SELECT,   'petugas_gudang',             'Pemohon'],
                [TABLE_ONLY, OPTIONAL, I::MONEY,    'total_harga',                'Total Harga'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'total_harga',                'Total Harga'],
                [SHOW,       OPTIONAL, I::SELECT,   'id_status_pengajuan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal_diproses',           'Tanggal Diproses'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal_diproses',           'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'atasan_logistik',            'Pengelola'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'atasan_logistik_nama',       'Pengelola'],
            ],
            child_path: '/inventori-non-medis/detail-pengajuan-barang',
            child_fk: 'id_pengajuan',
        );
    }

    // data terbaru di atas
    protected function before_read(): void
    {
        $this->model->set_order('id_pengajuan', 'DESC');
    }

    // endpoint modal: pengajuan yang sudah disetujui dan masih punya sisa qty
    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->get_db()->query("
            SELECT DISTINCT pj.id_pengajuan, pj.no_pengajuan, TO_CHAR(pj.tanggal, 'YYYY-MM-DD HH24:MI') AS tanggal, o.nama
            FROM inventori_non_medis.pengajuan_barang pj
            JOIN inventori_non_medis.pengajuan_barang_detail pjd ON pj.id_pengajuan = pjd.id_pengajuan
            LEFT JOIN role.petugas pt ON pj.petugas_gudang = pt.id_petugas
            LEFT JOIN person.orang o ON pt.id_orang = o.id_orang
            WHERE pjd.id_barang > 0
              AND pjd.qty_disetujui > 0
              AND pj.id_status_pengajuan_barang = 2
              AND (
                SELECT COALESCE(SUM(pbd.qty), 0)
                FROM inventori_non_medis.pengadaan_barang_detail pbd
                JOIN inventori_non_medis.pengadaan_barang pb ON pbd.id_pengadaan = pb.id_pengadaan
                WHERE pb.id_pengajuan = pjd.id_pengajuan
                  AND pbd.id_barang = pjd.id_barang
              ) < pjd.qty_disetujui
              AND NOT EXISTS (
                SELECT 1
                FROM inventori_non_medis.pengajuan_barang_detail unmapped
                WHERE unmapped.id_pengajuan = pjd.id_pengajuan
                  AND unmapped.id_barang IS NULL
                  AND unmapped.qty_disetujui > 0
              )
            ORDER BY pj.no_pengajuan DESC
        ")->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    // tampilkan form tambah custom dengan modal search
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_pengajuan_barang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submittambah/',
        ]);
    }

    // tampilkan form ubah custom (reuse view tambah)
    public function update_page(int|string $id): string
    {
        $baris = $this->model->find_one($id);

        return view('admin/inventorinonmedis/tambah_pengajuan_barang', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitedit/' . $id,
            'baris'       => $baris,
        ]);
    }

    // auto no_pengajuan + status awal Draf (1)
    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.pengajuan_barang', 'no_pengajuan', 'id_pengajuan');
        $postData['no_pengajuan']               = generateNextNoPengajuanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_pengajuan_barang'] = 1;

        // convert empty FK modal fields to null
        if (isset($postData['petugas_gudang']) && $postData['petugas_gudang'] === '') {
            $postData['petugas_gudang'] = null;
        }
    }

    // hanya izinkan transisi ke Draf (1) atau Proses Pengajuan (4)
    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_pengajuan_barang'] ?? 0);
        if (!in_array($new_status, [1, 4], true)) {
            $current                                = $this->model->find($id);
            $postData['id_status_pengajuan_barang'] = (int) ($current['id_status_pengajuan_barang'] ?? 1);
        }
    }

    // blokir perubahan jika bukan Draf (1), cegah pengajuan jika detail kosong
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_pengajuan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Pengajuan yang sudah diajukan tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_pengajuan_barang') ?? 0);
        if ($new_status === 4) {
            $has_detail = $this
                ->get_db()
                ->table('inventori_non_medis.pengajuan_barang_detail')
                ->where('id_pengajuan', (int) $id)
                ->countAllResults() > 0;
            if (!$has_detail) {
                session()->setFlashdata('error', 'Tambahkan detail barang terlebih dahulu sebelum mengajukan.');
                return $this->home();
            }
        }

        return parent::update($id);
    }
}
