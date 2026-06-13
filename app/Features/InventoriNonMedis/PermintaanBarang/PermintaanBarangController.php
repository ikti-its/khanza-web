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
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,    'id_permintaan',                 'ID'],
                [SHOW, OPTIONAL, I::READONLY, 'no_permintaan',                 'No. Permintaan'],
                [SHOW, REQUIRED, I::DTIME,    'tanggal',                       'Tgl. Permintaan'],
                [SHOW, REQUIRED, I::SELECT,   'petugas',                       'Pemohon'],
                [SHOW, REQUIRED, I::SELECT,   'master_ruangan',                'Ruangan'],
                [SHOW, OPTIONAL, I::SELECT, 'id_status_permintaan_barang', 'Status'],
                [TABLE_ONLY, OPTIONAL, I::DTIME,    'tanggal_diproses',             'Tgl. Diproses'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'tanggal_diproses',             'Tgl. Diproses'],
                [TABLE_ONLY, OPTIONAL, I::READONLY, 'petugas_gudang',      'Pengelola'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'petugas_gudang_nama', 'Pengelola'],
                [SHOW, OPTIONAL, I::READONLY, 'no_keluar',                     'No. Keluar'],
            ],
            child_path: '/inventori-non-medis/detail-permintaan-barang',
            child_fk:   'id_permintaan',
        );
    }

    // tampilkan status sebagai teks "Draf" (readonly) pada form tambah
    public function create_page(): string
    {
        $konfig = $this->get_fields_with_options(false, true);
        foreach ($konfig as &$field) {
            if (($field[2] ?? '') === 'id_status_permintaan_barang') {
                $field[2] = 'nama_status_permintaan_barang';
                $field[3] = 'readonly';
                unset($field[5]);
            }
        }
        unset($field);

        // view mensyaratkan semua kolom konfig ada di $baris jika $baris non-empty
        $baris = array_fill_keys(array_column($konfig, 2), null);
        $baris['nama_status_permintaan_barang'] = 'Draf';

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

    // auto no_permintaan + status awal Draf (1)
    protected function before_create(array &$postData): void
    {
        helper('autonomor');
        $lastNo = $this->get_last('inventori_non_medis.permintaan_barang', 'no_permintaan', 'id_permintaan');
        $postData['no_permintaan']               = generateNextNoPermintaanBarang($lastNo, $postData['tanggal'] ?? null);
        $postData['id_status_permintaan_barang'] = 1;
    }

    // hanya izinkan transisi ke Draf (1) atau Proses Permintaan (4)
    protected function before_update(array &$postData, int|string $id): void
    {
        $new_status = (int) ($postData['id_status_permintaan_barang'] ?? 0);
        if (!in_array($new_status, [1, 4], true)) {
            $current = $this->model->find($id);
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
            $has_detail = $this->get_db()
                ->table('inventori_non_medis.permintaan_barang_detail')
                ->where('id_permintaan', (int) $id)
                ->countAllResults() > 0;
            if (!$has_detail) {
                session()->setFlashdata('error', 'Tambahkan detail barang terlebih dahulu sebelum mengajukan permintaan.');
                return $this->home();
            }
        }

        return parent::update($id);
    }
}
