<?php
declare(strict_types=1);

namespace App\Features\Role\Pasien;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Person\Orang\OrangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PasienController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PasienModel(),
            [
                ['Role', 'role'],
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

    /** @return list<array<int|string, mixed>> */
    private function buildKonfigGabungan(): array
    {
        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        /** @var list<array<int|string, mixed>> $konfigOrang */
        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        /** @var list<array<int|string, mixed>> $konfigPasien */
        $konfigPasien = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];
        foreach ($konfigPasien as $field) {
            if (($field[2] ?? null) === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                continue;
            }
            $konfigGabungan[] = $field;
        }

        return $konfigGabungan;
    }

    /**
     * @param array<string, mixed> $rawPost
     * @param list<string> $skipFields
     * @return array<string, mixed>
     */
    private function buildDataOrangFromPost(OrangModel $orangModel, array $rawPost, array $skipFields = []): array
    {
        $dataOrang = [];
        foreach ($orangModel->allowedFields as $field) {
            if (in_array($field, $skipFields, true)) {
                continue;
            }
            $value = (string) ($rawPost[$field] ?? '');
            if ($value === '') {
                $value = null;
            } elseif (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                $value = (int) $value;
            }
            $dataOrang[$field] = $value;
        }

        return $dataOrang;
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create_page(): string
    {
        $builder = $this->model
            ->db
            ->table('role.pasien')
            ->select('nomor_rm')
            ->orderBy('id_pasien', 'DESC')
            ->limit(1);

        $lastRow = $this->model->guarded_get($builder, 'create_page')->getRowArray();

        $nomor_rm = generateNextNoRM(is_string($lastRow['nomor_rm'] ?? null) ? $lastRow['nomor_rm'] : null);

        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/admin/role/tambah_pasien', [
            'judul'       => 'Tambah Pasien',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->buildKonfigGabungan(),
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
                'redirect_to'       => $this->request->getGet('redirect_to') ?? '',
            ],
            'form_action' => '/submittambah/',
        ]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $alamatModel = new \App\Features\Lokasi\Alamat\AlamatModel();
        $orangModel  = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost     = $this->request->getPost();
        $nomor_rm    = (string) ($rawPost['nomor_rm'] ?? '');

        $db = $this->model->db;
        $db->transStart();

        try {
            $dataAlamat = [
                'alamat_lengkap' => $rawPost['alamat_lengkap'] ?? null,
                'id_provinsi'    => isset($rawPost['id_provinsi']) ? (int) $rawPost['id_provinsi'] : null,
                'id_kota_lokal'  => isset($rawPost['id_kota_lokal']) ? (int) $rawPost['id_kota_lokal'] : null,
                'id_kec_lokal'   => isset($rawPost['id_kec_lokal']) ? (int) $rawPost['id_kec_lokal'] : null,
                'id_desa_lokal'  => isset($rawPost['id_desa_lokal']) ? (int) $rawPost['id_desa_lokal'] : null,
            ];
            if (!$alamatModel->insert($dataAlamat)) {
                throw new \RuntimeException('Sistem gagal menyimpan data Alamat.');
            }
            $id_alamat = $alamatModel->getInsertID();

            $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost, ['id_alamat']);
            $dataOrang['id_alamat'] = $id_alamat;

            if (!$orangModel->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }
            $id_orang = $orangModel->getInsertID();

            if (!$this->model->insert(['id_orang' => $id_orang, 'nomor_rm' => $nomor_rm])) {
                throw new \RuntimeException('Sistem gagal menyimpan data Pasien.');
            }

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data Pasien.');
            }

            session()->setFlashdata('success', 'Data Pasien berhasil disimpan.');
        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return $this->create_page();
        }

        $redirect_to = (string) ($this->request->getPost('redirect_to') ?? '');
        return $redirect_to !== '' ? redirect()->to($redirect_to) : $this->home();
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $dataPasien = $this->model->find($id);
        if (!is_array($dataPasien)) {
            return $this->index();
        }

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataPasien['id_orang'])) {
            $orangModel = new OrangModel();
            $foundOrang = $orangModel->find((string) $dataPasien['id_orang']);
            $dataOrang  = is_array($foundOrang) ? $foundOrang : [];

            if (!empty($dataOrang['id_alamat'])) {
                $alamatModel = new \App\Features\Lokasi\Alamat\AlamatModel();
                $foundAlamat = $alamatModel->find((string) $dataOrang['id_alamat']);
                $alamat      = is_array($foundAlamat) ? $foundAlamat : [];
                $dataAlamat  = ['alamat_lengkap' => $alamat['alamat_lengkap'] ?? ''];
            }

            if (!empty($dataOrang['tempat_lahir_kota'])) {
                $kotaModel = new \App\Features\Lokasi\Kota\KotaModel();
                $kotaLahir = $kotaModel->find((string) $dataOrang['tempat_lahir_kota']);
                if (is_array($kotaLahir)) {
                    $dataAlamat['nama_kota'] = $kotaLahir['nama_kota'] ?? '';
                }
            }
        }

        $baris = array_merge($dataAlamat, $dataOrang, $dataPasien);
        foreach (array_keys($baris) as $key) {
            if ($baris[$key] === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [['title' => 'Ubah', 'icon', 'Ubah']];
        return view('/admin/role/tambah_pasien', [
            'judul'       => 'Ubah Pasien',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->buildKonfigGabungan(),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $dataPasienLama = $this->model->find($id);
        if (!is_array($dataPasienLama)) {
            session()->setFlashdata('error', 'Data Pasien tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang    = (is_int($dataPasienLama['id_orang'] ?? null) || is_string($dataPasienLama['id_orang'] ?? null))
            ? $dataPasienLama['id_orang']
            : null;
        $orangModel = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();

        $db = $this->model->db;
        $db->transStart();

        try {
            $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost);
            $orangModel->update($idOrang, $dataOrang);

            $this->model->update($id, ['nomor_rm' => $rawPost['nomor_rm'] ?? null]);

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data Pasien.');
            }

            session()->setFlashdata('success', 'Data Pasien berhasil diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->home();

        $pasien = $this->model->find($id);
        if (!is_array($pasien)) {
            session()->setFlashdata('error', 'Data Pasien tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = (is_int($pasien['id_orang'] ?? null) || is_string($pasien['id_orang'] ?? null))
            ? $pasien['id_orang']
            : null;

        $db = $this->model->db;
        $db->transStart();

        try {
            $this->model->delete($id);

            // Alamat tidak dihapus: satu Alamat bisa dipakai bersama oleh banyak Orang.
            if (!empty($idOrang)) {
                (new OrangModel())->delete($idOrang);
            }

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal menghapus data Pasien.');
            }

            session()->setFlashdata('success', 'Data Pasien berhasil dihapus.');
        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $data = $this->model->guarded_get($this->pasien_query(), 'list')->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * OVERRIDE: generic index() melabeli kolom pertama hasil join (nik) dengan
     * label field induknya ("ID Orang"); query eksplisit di sini menghindari itu.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    public function index(): string
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $size = max(1, (int) ($this->request->getGet('size') ?? $this->meta_data['size'] ?? 10));

        $total_rows  = $this->model->count_filtered();
        $total_pages = $total_rows > 0 ? (int) ceil($total_rows / $size) : 1;
        $page        = min($page, $total_pages);

        $builder = $this
            ->pasien_query()
            ->orderBy('p.id_pasien')
            ->limit($size, ($page - 1) * $size);

        $data_tabel = $this->model->guarded_get($builder, 'index')->getResultArray();

        $konfig = [
            [SHOW, 'No. Rekam Medis', 'nomor_rm',      'teks',    0],
            [SHOW, 'NIK',             'nik',           'teks',    0],
            [SHOW, 'Nama',            'nama',          'teks',    0],
            [SHOW, 'Jenis Kelamin',   'jenis_kelamin', 'teks',    0],
            [SHOW, 'Tanggal Lahir',   'tanggal_lahir', 'tanggal', 0],
        ];

        return view('/layouts/data', [
            'judul'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs,
            'meta_data'    => ['page' => $page, 'size' => $size, 'total' => $total_pages],
            'modul_path'   => $this->get_uri_path(),
            'kolom_id'     => $this->primary_key,
            'konfig'       => $konfig,
            'aksi'         => $this->actions,
            'tabel'        => $data_tabel,
            'row_alert'    => $this->row_alert,
            'child_link'   => null,
            'query_string' => '',
        ]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function pasien_query(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->model
            ->db
            ->table('role.pasien p')
            ->select('p.id_pasien, p.nomor_rm, o.nik, o.nama, o.tanggal_lahir, jk.nama_jenis_kelamin AS jenis_kelamin')
            ->join('person.orang o', 'o.id_orang = p.id_orang')
            ->join('person.jenis_kelamin jk', 'jk.id_jenis_kelamin = o.id_jenis_kelamin', 'left');
    }
}
