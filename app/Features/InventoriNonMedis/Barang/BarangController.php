<?php
declare(strict_types=1);

namespace App\Features\InventoriNonMedis\Barang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class BarangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new BarangModel(),
            [
                ['Inventori Non Medis', 'inventori_non_medis'],
                ['Barang',              'barang'],
            ],
            'Barang',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,    'id_barang',       'ID Barang'],
                [SHOW,       REQUIRED, I::TEXT,     'kode_barang',     'Kode Barang'],
                [SHOW,       REQUIRED, I::NAME,     'nama_barang',     'Nama Barang'],
                [SHOW,       REQUIRED, I::SELECT,   'id_satuan',       'Satuan'],
                [SHOW,       REQUIRED, I::SELECT,   'id_jenis_barang', 'Jenis'],
                [TABLE_ONLY, OPTIONAL, I::NUMBER,   'stok',            'Stok'],
                [FORM_ONLY,  OPTIONAL, I::READONLY, 'stok',            'Stok'],
                [FORM_ONLY,  OPTIONAL, I::NUMBER,   'stok_minimum',    'Stok Minimum'],
                [SHOW,       OPTIONAL, I::MONEY,    'harga_satuan',    'Harga Satuan'],
            ],
        );
    }

    protected array $row_alert = ['value' => 'stok', 'threshold' => 'stok_minimum'];

    // urut berdasarkan nama A-Z
    protected function before_read(): void
    {
        $this->model->set_order('nama_barang', 'ASC');
    }

    // form tambah custom dengan modal search satuan dan jenis barang
    public function create_page(): string
    {
        return view('admin/inventorinonmedis/tambah_barang', [
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

        return view('admin/inventorinonmedis/tambah_barang', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitedit/' . $id,
            'baris'       => $baris,
        ]);
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $rows = $this->model
            ->builder()
            ->join(
                'inventori_non_medis.satuan',
                'inventori_non_medis.satuan.id_satuan = inventori_non_medis.barang.id_satuan',
                'left',
            )
            ->select(
                'inventori_non_medis.barang.id_barang, inventori_non_medis.barang.kode_barang, inventori_non_medis.barang.nama_barang, inventori_non_medis.satuan.nama_satuan, inventori_non_medis.barang.stok, inventori_non_medis.barang.harga_satuan',
            )
            ->orderBy('inventori_non_medis.barang.nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }

    /** @param array<string, scalar|null> $postData */
    // stok awal = 0
    protected function before_create(array &$postData): void
    {
        $postData['stok'] = 0;

        // convert empty FK modal fields to null
        foreach (['id_satuan', 'id_jenis_barang'] as $fk) {
            if (isset($postData[$fk]) && $postData[$fk] === '') {
                $postData[$fk] = null;
            }
        }
    }

    /** @param array<string, scalar|null> $postData */
    // 'stok' bukan bagian dari form ubah (tampil readonly, tanpa name di form),
    // jadi get_post_data() akan mengirim null untuknya kalau tidak dibuang di sini —
    // dan itu melanggar constraint NOT NULL pada kolom stok.
    protected function before_update(array &$postData, int|string $id): void
    {
        unset($postData['stok']);

        foreach (['id_satuan', 'id_jenis_barang'] as $fk) {
            if (isset($postData[$fk]) && $postData[$fk] === '') {
                $postData[$fk] = null;
            }
        }
    }

    // form tambah gagal validasi: render ulang view custom yang sama, bukan layout generik
    protected function create_view(array $baris = []): string
    {
        return view('admin/inventorinonmedis/tambah_barang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submittambah/',
            'baris'       => $baris,
        ]);
    }

    // form ubah gagal validasi: render ulang view custom yang sama dengan input yang baru disubmit
    protected function update_error_view(int|string $id, string $msg, array $postData = []): string
    {
        session()->setFlashdata('error', $msg);
        $data  = $this->model->find_one($id);
        $baris = array_merge($data ?? [], $postData);

        return view('admin/inventorinonmedis/tambah_barang', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => '/submitedit/' . $id,
            'baris'       => $baris,
        ]);
    }
}
