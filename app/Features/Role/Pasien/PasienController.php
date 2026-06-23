<?php
declare(strict_types=1);

namespace App\Features\Role\Pasien;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Person\Orang\OrangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

final class PasienController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PasienModel(),
            [
                ['Role',   'role'],
                ['Pasien', 'pasien'],
            ],
            'Pasien',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_pasien', 'ID Pasien'],
                [SHOW, OPTIONAL, I::INDEX, 'id_orang',  'ID Orang'],
                [SHOW, REQUIRED, I::TEXT,  'nomor_rm',  'No. Rekam Medis'],
            ],
        );
    }

    public function create_page(): string
    {
        $orangModel = new OrangModel();
        $allOptions = $orangModel->get_all_options();

        $last = $this->model->db
            ->table('role.pasien')
            ->select('nomor_rm')
            ->orderBy('id_pasien', 'DESC')
            ->limit(1)
            ->get()->getRowArray()['nomor_rm'] ?? null;

        $nomor_rm = generateNextNoRM($last);

        $konfig = [
            [SHOW, 'No. Rekam Medis',   'nomor_rm',           'readonly', REQUIRED],
            [SHOW, 'NIK',               'nik',                'teks',     REQUIRED],
            [SHOW, 'Nama',              'nama',               'nama',     REQUIRED],
            [SHOW, 'Jenis Kelamin',     'id_jenis_kelamin',   'status',   REQUIRED, $allOptions['id_jenis_kelamin']  ?? []],
            [SHOW, 'Agama',             'id_agama',           'status',   REQUIRED, $allOptions['id_agama']          ?? []],
            [SHOW, 'Status Pernikahan', 'id_pernikahan',      'status',   REQUIRED, $allOptions['id_pernikahan']     ?? []],
            [SHOW, 'Golongan Darah',    'id_golongan_darah',  'status',   REQUIRED, $allOptions['id_golongan_darah'] ?? []],
            [SHOW, 'Alamat',            'id_alamat',          'status',   REQUIRED, $allOptions['id_alamat']         ?? []],
            [SHOW, 'Tempat Lahir',      'tempat_lahir_kota',  'status',   REQUIRED, $allOptions['tempat_lahir_kota'] ?? []],
            [SHOW, 'Tanggal Lahir',     'tanggal_lahir',      'tanggal',  REQUIRED],
        ];

        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/layouts/tambah_ubah', [
            'judul'       => 'Tambah Pasien',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfig,
            'baris'       => [
                'nomor_rm'          => $nomor_rm,
                'nik'               => '',
                'nama'              => '',
                'id_jenis_kelamin'  => '',
                'id_agama'          => '',
                'id_pernikahan'     => '',
                'id_golongan_darah' => '',
                'id_alamat'         => '',
                'tempat_lahir_kota' => '',
                'tanggal_lahir'     => '',
            ],
            'form_action' => '/submittambah/',
        ]);
    }

    public function create(): string|RedirectResponse
    {
        $orangData = [
            'nik'               => $this->request->getPost('nik'),
            'nama'              => $this->request->getPost('nama'),
            'id_jenis_kelamin'  => $this->request->getPost('id_jenis_kelamin')  ?: null,
            'id_agama'          => $this->request->getPost('id_agama')           ?: null,
            'id_pernikahan'     => $this->request->getPost('id_pernikahan')      ?: null,
            'id_golongan_darah' => $this->request->getPost('id_golongan_darah')  ?: null,
            'id_alamat'         => $this->request->getPost('id_alamat')          ?: null,
            'tempat_lahir_kota' => $this->request->getPost('tempat_lahir_kota')  ?: null,
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir')      ?: null,
        ];
        $nomor_rm = $this->request->getPost('nomor_rm');

        $db = $this->model->db;
        $db->transBegin();

        try {
            $cols   = implode(', ', array_keys($orangData));
            $marks  = implode(', ', array_fill(0, count($orangData), '?'));
            $result = $db->query(
                "INSERT INTO person.orang ({$cols}) VALUES ({$marks}) RETURNING id_orang",
                array_values($orangData),
            );
            if (!$result) {
                throw new DatabaseException('Gagal menyimpan data Orang.');
            }
            $id_orang = (int) $result->getRow()->id_orang;

            $this->model->insert(['id_orang' => $id_orang, 'nomor_rm' => $nomor_rm]);

            $db->transCommit();
            session()->setFlashdata('success', 'Data Pasien berhasil disimpan.');
        } catch (\ReflectionException|DatabaseException $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return $this->create_page();
        }

        $redirect_to = $this->request->getPost('redirect_to');
        return $redirect_to ? redirect()->to($redirect_to) : $this->home();
    }

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $builder = $this->model->db
            ->table('role.pasien p')
            ->select('p.id_pasien, p.nomor_rm, o.nama, o.nik, o.tanggal_lahir, jk.nama_jenis_kelamin as jenis_kelamin')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left');

        $data = $builder->get()->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }
}