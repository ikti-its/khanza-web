<?php
declare(strict_types=1);

namespace App\Features\Role\Petugas;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use App\Features\Person\Orang\OrangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

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

    #[\Override]
    public function create_page(): string
    {
        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang      = $controllerOrang->get_fields_with_options(false, true);
        $konfigPetugas    = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];
        foreach ($konfigPetugas as $field) {
            if ($field[2] === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                continue;
            }
            $konfigGabungan[] = $field;
        }

        $breadcrumbs = [['title' => 'Tambah', 'icon', 'tambah']];
        return view('/admin/role/tambah_petugas', [
            'judul'       => 'Tambah Petugas',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfigGabungan,
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

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $orangModel = new OrangModel();
        $rawPost    = $this->request->getPost();
        $deskripsi  = $rawPost['deskripsi'] ?: null;

        $dataOrang = [];
        foreach ($orangModel->allowedFields as $field) {
            $value = $rawPost[$field] ?? '';
            if ($value === '') {
                $value = null;
            } elseif (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                $value = (int) $value;
            }
            $dataOrang[$field] = $value;
        }

        $db = $this->model->db;
        $db->transStart();

        try {
            if (!$orangModel->insert($dataOrang)) {
                throw new \RuntimeException('Sistem gagal menyimpan identitas Orang.');
            }
            $id_orang = $orangModel->insertID();

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

        $redirect_to = $this->request->getPost('redirect_to');
        return $redirect_to ? redirect()->to($redirect_to) : $this->home();
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        if ($id == 0)
            return $this->index();

        $dataPetugas = $this->model->find($id);
        if (!$dataPetugas) {
            return $this->index();
        }

        $dataOrang  = [];
        $dataAlamat = [];

        if (!empty($dataPetugas['id_orang'])) {
            $orangModel = new OrangModel();
            $dataOrang  = $orangModel->find($dataPetugas['id_orang']) ?? [];

            if (!empty($dataOrang['id_alamat'])) {
                $alamatModel = new \App\Features\Lokasi\Alamat\AlamatModel();
                $alamat      = $alamatModel->find($dataOrang['id_alamat']) ?? [];
                $dataAlamat  = ['alamat_lengkap' => $alamat['alamat_lengkap'] ?? ''];
            }

            if (!empty($dataOrang['tempat_lahir_kota'])) {
                $kotaModel = new \App\Features\Lokasi\Kota\KotaModel();
                $kotaLahir = $kotaModel->find($dataOrang['tempat_lahir_kota']);
                if ($kotaLahir) {
                    $dataAlamat['nama_kota'] = $kotaLahir['nama_kota'] ?? '';
                }
            }
        }

        $baris = array_merge($dataAlamat, $dataOrang, $dataPetugas);
        foreach ($baris as $key => $value) {
            if ($value === null) {
                $baris[$key] = '';
            }
        }

        $controllerOrang = new \App\Features\Person\Orang\OrangController();
        $konfigOrang      = $controllerOrang->get_fields_with_options(false, true);
        $konfigPetugas    = $this->get_fields_with_options(false, true);

        $konfigGabungan = [];
        foreach ($konfigPetugas as $field) {
            if ($field[2] === 'id_orang') {
                $konfigGabungan = array_merge($konfigGabungan, $konfigOrang);
                continue;
            }
            $konfigGabungan[] = $field;
        }

        $breadcrumbs = [['title' => 'Ubah', 'icon', 'Ubah']];
        return view('/admin/role/tambah_petugas', [
            'judul'       => 'Ubah Petugas',
            'breadcrumbs' => array_merge($this->breadcrumbs, $breadcrumbs),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->primary_key,
            'konfig'      => $konfigGabungan,
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
        if (!$dataPetugasLama) {
            session()->setFlashdata('error', 'Data Petugas tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang    = $dataPetugasLama['id_orang'];
        $orangModel = new OrangModel();
        $rawPost    = $this->request->getPost();

        $db = $this->model->db;
        $db->transStart();

        try {
            $dataOrang = [];
            foreach ($orangModel->allowedFields as $field) {
                $value = $rawPost[$field] ?? '';
                if ($value === '') {
                    $value = null;
                } elseif (is_numeric($value) && (str_contains($field, 'id_') || $field === 'tempat_lahir_kota')) {
                    $value = (int) $value;
                }
                $dataOrang[$field] = $value;
            }
            $orangModel->update($idOrang, $dataOrang);

            $this->model->update($id, ['deskripsi' => $rawPost['deskripsi'] ?: null]);

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
        if (!$petugas) {
            session()->setFlashdata('error', 'Data Petugas tidak ditemukan.');
            return redirect()->to($this->get_uri_path() . '/data');
        }

        $idOrang = $petugas['id_orang'] ?? null;

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

    public function list(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->model
            ->builder()
            ->select('
                role.petugas.id_petugas,
                role.petugas.deskripsi,
                person.orang.nama
            ')
            ->join('person.orang', 'person.orang.id_orang = role.petugas.id_orang', 'inner')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }
}