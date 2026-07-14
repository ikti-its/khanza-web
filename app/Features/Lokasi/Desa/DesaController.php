<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Desa;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;

final class DesaController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DesaModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Desa', 'desa'],
            ],
            'Desa',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, REQUIRED, I::INDEX,   'id_desa',        'ID'],
                [SHOW, REQUIRED, I::SELECT,  'id_provinsi',    'Provinsi'],
                [SHOW, REQUIRED, I::SELECT,  'id_kota_lokal',  'Kota'],
                [SHOW, REQUIRED, I::SELECT,  'id_kec_lokal',   'Kecamatan'],
                [SHOW, REQUIRED, I::TEXT,    'id_desa_lokal',  'Kode Lokal'],
                [SHOW, REQUIRED, I::TEXT,    'nama_desa',      'Desa'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Desa
     */
    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [
            ['title' => 'Tambah', 'icon' => 'tambah'],
        ];

        $mockBaris = [];
        foreach ($this->fields as $field) {
            $namaKolom = $field[2];
            if ($namaKolom === 'id_desa') {
                continue;
            }
            $mockBaris[$namaKolom] = '';
        }

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        return view('/admin/lokasi/tambah_desa', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Desa
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) {
            return $this->index();
        }

        $baris = $this->model->find_one($id);
        if (!$baris) {
            $baris = [];
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/lokasi/tambah_desa', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /**
     * Menampilkan data modal wilayah gabungan
     */
    public function list()
    {
        $tabel = $this->model->table;

        $data = $this->model
            ->builder()
            ->select("
                {$tabel}.id_provinsi,
                {$tabel}.id_kota_lokal,
                {$tabel}.id_kec_lokal,
                {$tabel}.id_desa_lokal,
                {$tabel}.nama_desa AS nama_desa,
                kc.nama_kecamatan AS nama_kecamatan,
                kt.nama_kota AS nama_kota,
                pr.nama_provinsi AS nama_provinsi
            ")
            ->join(
                'lokasi.kecamatan kc',
                "kc.id_provinsi = {$tabel}.id_provinsi AND kc.id_kota_lokal = {$tabel}.id_kota_lokal AND kc.id_kec_lokal = {$tabel}.id_kec_lokal",
                'inner',
            )
            ->join(
                'lokasi.kota kt',
                "kt.id_provinsi = {$tabel}.id_provinsi AND kt.id_kota_lokal = {$tabel}.id_kota_lokal",
                'inner',
            )
            ->join('lokasi.provinsi pr', "pr.id_provinsi = {$tabel}.id_provinsi", 'inner')
            ->where("{$tabel}.id_desa >", 0)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}
