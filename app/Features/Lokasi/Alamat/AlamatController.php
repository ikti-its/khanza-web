<?php
declare(strict_types=1);

namespace App\Features\Lokasi\Alamat;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\ResponseInterface;

final class AlamatController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new AlamatModel(),
            [
                ['Lokasi', 'lokasi'],
                ['Alamat', 'alamat'],
            ],
            'Alamat',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, REQUIRED, I::INDEX,  'id_alamat',      'ID'],
                [SHOW, REQUIRED, I::SELECT, 'id_provinsi',    'Provinsi'],
                [SHOW, REQUIRED, I::SELECT, 'id_kota_lokal',  'Kota/Kabupaten'],
                [SHOW, REQUIRED, I::SELECT, 'id_kec_lokal',   'Kecamatan'],
                [SHOW, REQUIRED, I::SELECT, 'id_desa_lokal',  'Kelurahan/Desa'],
                [SHOW, REQUIRED, I::TEXT,   'rw',             'RW'],
                [SHOW, REQUIRED, I::TEXT,   'rt',             'RT'],
                [SHOW, REQUIRED, I::TEXT,   'alamat_lengkap', 'Alamat'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Alamat (pakai modal pencarian Wilayah)
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
            if ($namaKolom === 'id_alamat') {
                continue;
            }
            $mockBaris[$namaKolom] = '';
        }

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        return view('/admin/lokasi/tambah_alamat', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Alamat
     */
    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0) {
            return $this->index();
        }

        $baris = $this->model->get_detail_wilayah($id) ?? [];

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/lokasi/tambah_alamat', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    public function list(): ResponseInterface
    {
        $data = $this->model
            ->db
            ->table('lokasi.alamat a')
            ->select('a.id_alamat, k.nama_kota, a.alamat_lengkap')
            ->join('lokasi.kota k', 'k.id_provinsi = a.id_provinsi AND k.id_kota_lokal = a.id_kota_lokal', 'left')
            ->orderBy('a.id_alamat')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
