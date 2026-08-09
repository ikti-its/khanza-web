<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Kecamatan;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class KecamatanController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new KecamatanModel(),
            [
                ['Lokasi',    'lokasi'],
                ['Kecamatan', 'kecamatan'],
            ],
            'Kecamatan',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_kecamatan',   'ID Kecamatan'],
                [SHOW, REQUIRED, I::SELECT, 'id_provinsi',    'Provinsi'],
                [SHOW, REQUIRED, I::SELECT, 'id_kota_lokal',  'Kota'],
                [SHOW, REQUIRED, I::SELECT, 'id_kec_lokal',   'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,   'nama_kecamatan', 'Kecamatan'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Halaman Utama Kecamatan
     *
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    public function index(): string
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $size = max(1, (int) ($this->request->getGet('size') ?? 10));

        $total_rows  = $this->model->exclude_zero_pk()->count_filtered();
        $total_pages = $total_rows > 0 ? (int) ceil($total_rows / $size) : 1;
        $page        = min($page, $total_pages);
        $offset      = ($page - 1) * $size;

        $konfig = [
            [1, 'Provinsi',   'nama_provinsi',  'teks', 0],
            [1, 'Kota',       'nama_kota',      'teks', 0],
            [1, 'Kode Lokal', 'id_kec_lokal',   'teks', 0],
            [1, 'Kecamatan',  'nama_kecamatan', 'teks', 0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => $page, 'size' => $size, 'total' => $total_pages],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => $this->primary_key,
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $this->model()->get_data_tabel($size, $offset),
            'row_alert'    => [],
            'child_link'   => null,
            'query_string' => '',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Form Kecamatan
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $mockBaris = [
            'id_provinsi'    => '',
            'id_kota_lokal'  => '',
            'nama_kota'      => '',
            'nama_provinsi'  => '',
            'id_kec_lokal'   => '',
            'nama_kecamatan' => '',
        ];

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        return view('/admin/lokasi/tambah_kecamatan', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Kecamatan
     *
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) {
            return $this->index();
        }

        $baris = $this->model()->find_data($id);
        if (!$baris) {
            $baris = [];
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/lokasi/tambah_kecamatan', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * Menampilkan data modal kecamatan
     *
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function list(): ResponseInterface
    {
        return $this->response->setJSON([
            'data' => $this->model()->get_data_tabel(),
        ]);
    }

    private function model(): KecamatanModel
    {
        assert($this->model instanceof KecamatanModel);
        return $this->model;
    }
}
