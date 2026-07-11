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
                [
                    SHOW,
                    REQUIRED,
                    I::MODAL,
                    'petugas',
                    'Pemohon',
                    ['modal' => 'modalPemohon', 'display_column' => 'nama', 'placeholder' => 'Klik cari pemohon...'],
                ],
                [
                    SHOW,
                    REQUIRED,
                    I::MODAL,
                    'master_ruangan',
                    'Ruangan',
                    [
                        'modal'          => 'modalPilihRuangan',
                        'display_column' => 'nama_ruangan',
                        'placeholder'    => 'Klik cari ruangan...',
                    ],
                ],
                [SHOW, OPTIONAL, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME, 'tanggal_diproses', 'Tanggal Diproses'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'tanggal_diproses', 'Tanggal Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang', 'Pengelola'],
                [FORM_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang_nama', 'Pengelola'],
                [SHOW, OPTIONAL, I::READONLY, 'no_keluar', 'No. Keluar'],
            ],
            child_path: '/inventori-non-medis/detail-permintaan-barang',
            child_fk: 'id_permintaan',
        );
    }

    // data terbaru di atas
    protected function before_read(): void
    {
        $this->model->set_order('id_permintaan', 'DESC');
    }

    // tampilkan form tambah custom (seperti pattern Triase)
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_permintaan_barang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submittambah/',
        ]);
    }

    // auto no_permintaan + status awal Draf (1)
    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.permintaan_barang', 'no_permintaan', 'id_permintaan');
        $postData['no_permintaan'] = generateNextNoPermintaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_permintaan_barang'] = 1;

        // convert empty FK modal fields to null agar tidak kirim '' ke kolom integer
        foreach (['petugas', 'master_ruangan'] as $fk) {
            if (isset($postData[$fk]) && $postData[$fk] === '') {
                $postData[$fk] = null;
            }
        }
    }

    // hanya izinkan transisi ke Draf (1) atau Proses Permintaan (4)
    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_permintaan_barang'] ?? 0);
        if (!in_array($new_status, [1, 4], true)) {
            $current                                 = $this->model->find($id);
            $postData['id_status_permintaan_barang'] = (int) ($current['id_status_permintaan_barang'] ?? 1);
        }
    }

    // blokir semua perubahan jika status bukan Draf (1),
    // dan cegah pengajuan jika detail masih kosong
    public function update(int|string $id): string|RedirectResponse
    {
        $current = $this->model->find((int) $id);
        if (is_array($current) && (int) ($current['id_status_permintaan_barang'] ?? 0) !== 1) {
            session()->setFlashdata('error', 'Permintaan yang sudah diproses tidak dapat diubah.');
            return $this->home();
        }

        $new_status = (int) ($this->request->getPost('id_status_permintaan_barang') ?? 0);
        if ($new_status === 4) {
            $has_detail = $this
                ->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', (int) $id)
                ->countAllResults() > 0;
            if (!$has_detail) {
                session()->setFlashdata(
                    'error',
                    'Tambahkan detail barang terlebih dahulu sebelum mengajukan permintaan.',
                );
                return $this->home();
            }
        }

        return parent::update($id);
    }
}
