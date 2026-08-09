<?php
declare(strict_types=1);

namespace App\Features\Person\Orang;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class OrangController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new OrangModel(),
            [
                ['Person', 'person'],
                ['Orang', 'orang'],
            ],
            'Orang',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX,  'id_orang',          'ID Orang'],
                [SHOW, REQUIRED, I::TEXT,   'nik',               'NIK'],
                [SHOW, REQUIRED, I::NAME,   'nama',              'Nama'],
                [SHOW, REQUIRED, I::SELECT, 'id_jenis_kelamin',  'Jenis Kelamin'],
                [SHOW, REQUIRED, I::SELECT, 'id_agama',          'Agama'],
                [SHOW, REQUIRED, I::SELECT, 'id_pernikahan',     'Pernikahan'],
                [SHOW, REQUIRED, I::SELECT, 'id_golongan_darah', 'Golongan Darah'],
                [SHOW, REQUIRED, I::SELECT, 'id_alamat',         'Alamat'],
                [SHOW, REQUIRED, I::SELECT, 'tempat_lahir_kota', 'Tempat Lahir'],
                [SHOW, REQUIRED, I::DATE,   'tanggal_lahir',     'Tanggal Lahir'],
            ],
        );
    }

    /**
     * OVERRIDE: Menampilkan Form Orang (pakai modal Kota + Alamat, bukan dropdown mentah)
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
            if ($namaKolom === 'id_orang') {
                continue;
            }
            $mockBaris[$namaKolom] = '';
        }

        $mockBaris['redirect_to'] = $this->request->getGet('redirect_to') ?? '';

        return view('/admin/person/tambah_orang', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->get_fields_with_options(false, true),
            'baris'       => $mockBaris,
            'form_action' => '/submittambah',
        ]);
    }

    /**
     * OVERRIDE: Menampilkan Halaman Ubah Data Orang
     */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0) {
            return $this->index();
        }

        $baris = $this->model->find($id) ?? [];
        if (!is_array($baris)) {
            $baris = [];
        }

        if (!empty($baris['id_alamat'])) {
            $alamatModel = new \App\Features\Lokasi\Alamat\AlamatModel();
            $alamat      = $alamatModel->find((string) $baris['id_alamat']) ?? [];
            $baris['alamat_lengkap'] = is_array($alamat) ? ($alamat['alamat_lengkap'] ?? '') : '';
        }

        if (!empty($baris['tempat_lahir_kota'])) {
            $kotaModel = new \App\Features\Lokasi\Kota\KotaModel();
            $kotaLahir = $kotaModel->find((string) $baris['tempat_lahir_kota']);
            if (is_array($kotaLahir)) {
                $baris['nama_kota'] = $kotaLahir['nama_kota'] ?? '';
            }
        }

        $breadcrumbs = [
            ['title' => 'Ubah', 'icon' => 'Ubah'],
        ];

        return view('/admin/person/tambah_orang', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->get_fields_with_options(false, true),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $builder = $this->model
            ->db
            ->table('person.orang o')
            ->select('o.id_orang, o.nik, o.nama, o.id_alamat, a.alamat_lengkap')
            ->join('lokasi.alamat a', 'a.id_alamat = o.id_alamat', 'left')
            ->orderBy('o.nama');

        $data = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}
