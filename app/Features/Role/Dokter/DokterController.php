<?php
declare(strict_types=1);

namespace App\Features\Role\Dokter;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Person\Orang\OrangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class DokterController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new DokterModel(),
            [
                ['Role', 'role'],
                ['Dokter', 'dokter'],
            ],
            'Dokter',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_dokter',   'ID Dokter'],
                [SHOW, REQUIRED, I::TEXT,  'kode_dokter', 'Kode Dokter'],
                [SHOW, OPTIONAL, I::INDEX, 'id_orang',    'ID Orang'],
                [SHOW, REQUIRED, I::TEXT,  'spesialis',   'Spesialis'],
            ],
        );
    }

    /** @return list<array<int|string, mixed>> */
    private function buildKonfigGabungan(): array
    {
        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        /** @var list<array<int|string, mixed>> $konfigOrang */
        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        /** @var list<array<int|string, mixed>> $konfigDokter */
        $konfigDokter = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];
        foreach ($konfigDokter as $field) {
            if (($field[2] ?? null) === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                continue;
            }
            $konfigGabungan[] = $field;
        }

        return $konfigGabungan;
    }

    #[\Override]
    public function create_page(): string
    {
        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/admin/role/tambah_dokter', [
            'judul'       => 'Tambah Dokter',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->buildKonfigGabungan(),
            'baris'       => [
                'kode_dokter'       => '',
                'spesialis'         => '',
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

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildDataOrangFromPost(OrangModel $orangModel, array $rawPost): array
    {
        $dataOrang = [];
        foreach ($orangModel->allowedFields as $field) {
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

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $orangModel = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();
        $dokterData = [
            'kode_dokter' => $rawPost['kode_dokter'] ?? null,
            'spesialis'   => $rawPost['spesialis'] ?? null,
        ];

        $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost);

        $db = $this->model->db;
        $db->transStart();

        try {
            if (!$orangModel->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }
            $id_orang = $orangModel->getInsertID();

            if (!$this->model->insert([...$dokterData, 'id_orang' => $id_orang])) {
                throw new \RuntimeException('Sistem gagal menyimpan data Dokter.');
            }

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data Dokter.');
            }

            session()->setFlashdata('success', 'Data Dokter berhasil disimpan.');
        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
            return $this->create_page();
        }

        $redirect_to = (string) ($this->request->getPost('redirect_to') ?? '');
        return $redirect_to !== '' ? redirect()->to($redirect_to) : $this->home();
    }

    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        if ($id == 0)
            return $this->index();

        $dataDokter = $this->model->find($id);
        if (!is_array($dataDokter)) {
            return $this->index();
        }

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataDokter['id_orang'])) {
            $orangModel = new OrangModel();
            $foundOrang = $orangModel->find((string) $dataDokter['id_orang']);
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

        $baris = array_merge($dataAlamat, $dataOrang, $dataDokter);
        foreach (array_keys($baris) as $key) {
            if ($baris[$key] === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [['title' => 'Ubah', 'icon', 'Ubah']];
        return view('/admin/role/tambah_dokter', [
            'judul'       => 'Ubah Dokter',
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

        $dataDokterLama = $this->model->find($id);
        if (!is_array($dataDokterLama)) {
            session()->setFlashdata('error', 'Data Dokter tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang    = is_int($dataDokterLama['id_orang'] ?? null) || is_string($dataDokterLama['id_orang'] ?? null)
            ? $dataDokterLama['id_orang']
            : null;
        $orangModel = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();

        $db = $this->model->db;
        $db->transStart();

        try {
            $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost);
            $orangModel->update($idOrang, $dataOrang);

            $this->model->update($id, [
                'kode_dokter' => $rawPost['kode_dokter'] ?? null,
                'spesialis'   => $rawPost['spesialis'] ?? null,
            ]);

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data Dokter.');
            }

            session()->setFlashdata('success', 'Data Dokter berhasil diperbarui.');
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

        $dokter = $this->model->find($id);
        if (!is_array($dokter)) {
            session()->setFlashdata('error', 'Data Dokter tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = (is_int($dokter['id_orang'] ?? null) || is_string($dokter['id_orang'] ?? null))
            ? $dokter['id_orang']
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
                throw new \RuntimeException('Gagal menghapus data Dokter.');
            }

            session()->setFlashdata('success', 'Data Dokter berhasil dihapus.');
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
        $builder = $this->model
            ->db
            ->table('role.dokter')
            ->select([
                'role.dokter.id_dokter',
                'role.dokter.kode_dokter',
                'person.orang.nama AS nama_dokter',
                'role.dokter.spesialis',
            ])
            ->join('person.orang', 'person.orang.id_orang = role.dokter.id_orang')
            ->orderBy('person.orang.nama', 'ASC');

        $rows = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }
}
