<?php
declare(strict_types=1);

namespace App\Features\Role\Petugas;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Person\Orang\OrangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PetugasController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PetugasModel(),
            [
                ['Role',    'role'],
                ['Petugas', 'petugas'],
            ],
            'Petugas',
            [
                A::READ,
                A::CREATE,
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE, OPTIONAL, I::INDEX, 'id_petugas', 'ID Petugas'],
                [SHOW, OPTIONAL, I::INDEX, 'id_orang',   'ID Orang'],
                [SHOW, OPTIONAL, I::TEXT,  'deskripsi',  'Deskripsi'],
            ],
        );
    }

    /** @return list<array<int|string, mixed>> */
    private function buildKonfigGabungan(): array
    {
        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        /** @var list<array<int|string, mixed>> $konfigOrang */
        $konfigOrang = $controllerOrang->get_fields_with_options(false, true);
        /** @var list<array<int|string, mixed>> $konfigPetugas */
        $konfigPetugas = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];
        foreach ($konfigPetugas as $field) {
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
    public function create_page(): string
    {
        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/admin/role/tambah_petugas', [
            'judul'       => 'Tambah Petugas',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $this->buildKonfigGabungan(),
            'baris'       => [
                'deskripsi'         => '',
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

    /** @throws DatabaseException */
    #[\Override]
    public function create(): string|RedirectResponse
    {
        $orangModel = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();
        $deskripsi  = (string) ($rawPost['deskripsi'] ?? '');
        $deskripsi  = $deskripsi !== '' ? $deskripsi : null;

        $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost);

        $db = $this->model->db;
        $db->transStart();

        try {
            if (!$orangModel->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }
            $id_orang = $orangModel->getInsertID();

            if (!$this->model->insert(['id_orang' => $id_orang, 'deskripsi' => $deskripsi])) {
                throw new \RuntimeException('Sistem gagal menyimpan data Petugas.');
            }

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal menyimpan data Petugas.');
            }

            session()->setFlashdata('success', 'Data Petugas berhasil disimpan.');
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

        $dataPetugas = $this->model->find($id);
        if (!is_array($dataPetugas)) {
            return $this->index();
        }

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataPetugas['id_orang'])) {
            $orangModel = new OrangModel();
            $foundOrang = $orangModel->find((string) $dataPetugas['id_orang']);
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

        $baris = array_merge($dataAlamat, $dataOrang, $dataPetugas);
        foreach (array_keys($baris) as $key) {
            if ($baris[$key] === null) {
                $baris[$key] = '';
            }
        }

        $breadcrumbs = [['title' => 'Ubah', 'icon', 'Ubah']];
        return view('/admin/role/tambah_petugas', [
            'judul'       => 'Ubah Petugas',
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

        $dataPetugasLama = $this->model->find($id);
        if (!is_array($dataPetugasLama)) {
            session()->setFlashdata('error', 'Data Petugas tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang    = (is_int($dataPetugasLama['id_orang'] ?? null) || is_string($dataPetugasLama['id_orang'] ?? null))
            ? $dataPetugasLama['id_orang']
            : null;
        $orangModel = new OrangModel();
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();

        $db = $this->model->db;
        $db->transStart();

        try {
            $dataOrang = $this->buildDataOrangFromPost($orangModel, $rawPost);
            $orangModel->update($idOrang, $dataOrang);

            $deskripsiBaru = (string) ($rawPost['deskripsi'] ?? '');
            $this->model->update($id, ['deskripsi' => $deskripsiBaru !== '' ? $deskripsiBaru : null]);

            $db->transComplete();
            if (!$db->transStatus() ) {
                throw new \RuntimeException('Gagal memperbarui data Petugas.');
            }

            session()->setFlashdata('success', 'Data Petugas berhasil diperbarui.');
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

        $petugas = $this->model->find($id);
        if (!is_array($petugas)) {
            session()->setFlashdata('error', 'Data Petugas tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = (is_int($petugas['id_orang'] ?? null) || is_string($petugas['id_orang'] ?? null))
            ? $petugas['id_orang']
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
                throw new \RuntimeException('Gagal menghapus data Petugas.');
            }

            session()->setFlashdata('success', 'Data Petugas berhasil dihapus.');
        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e instanceof DatabaseException ? $this->friendly_db_error($e) : $e->getMessage();
            session()->setFlashdata('error', $msg);
        }

        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * @throws \CodeIgniter\Exceptions\ModelException
     * @throws DatabaseException
     */
    public function list(): ResponseInterface
    {
        $builder = $this->model
            ->builder()
            ->select('
                role.petugas.id_petugas,
                role.petugas.deskripsi,
                person.orang.nama
            ')
            ->join('person.orang', 'person.orang.id_orang = role.petugas.id_orang', 'inner');

        $data = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}