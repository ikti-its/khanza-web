<?php
declare(strict_types=1);

namespace App\Features\Operasi\PermintaanOperasi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

final class PermintaanOperasiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new PermintaanOperasiModel(),
            [
                ['Operasi',            'operasi'],
                ['Permintaan Operasi', 'permintaan_operasi'],
            ],
            'Permintaan Operasi',
            [
                A::READ,
                A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
                A::JADWALKAN,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_permintaan', 'ID Permintaan'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'nomor_reg',     'No. Registrasi'],
                [TABLE_ONLY, OPTIONAL, I::TEXT,   'id_dokter',     'Dokter PJ'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'id_tindakan',   'Tindakan Operasi'],
                [SHOW,       REQUIRED, I::DATE,   'tanggal_minta', 'Tanggal Minta'],
                [SHOW,       OPTIONAL, I::BOOL,   'is_cito',       'CITO'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /** @return list<array<int|string, mixed>> */
    private function filterKonfig(): array
    {
        /** @var list<array<int|string, mixed>> */
        return array_values(array_filter(
            $this->get_fields_with_options(false, true),
            static fn(array $f) => !in_array(
                $f[2] ?? null,
                [
                    'id_permintaan',
                    'nomor_reg',
                    'id_dokter',
                    'tanggal_minta',
                    'is_cito',
                ],
                true,
            ),
        ));
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchRegistrasi(string $nomorReg): array
    {
        $builder = $this->model
            ->db
            ->table('registrasi.registrasi r')
            ->select([
                'r.nomor_reg',
                'p.nomor_rm',
                'o.nama AS nama_pasien',
                'd.id_dokter',
                'd.kode_dokter',
                'od.nama AS nama_dokter',
            ])
            ->join('role.pasien p', 'p.id_pasien = r.id_pasien')
            ->join('person.orang o', 'o.id_orang  = p.id_orang')
            ->join('role.dokter d', 'd.id_dokter = r.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang = d.id_orang', 'left')
            ->where('r.nomor_reg', $nomorReg);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchRegistrasi')->getRowArray() ?? [];
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): array
    {
        $builder = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select(['t.id_dokter', 't.kode_dokter', 'o.nama AS nama_dokter'])
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchNamaRole')->getRowArray() ?? [];
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchTindakan(int $idTindakan): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select(['id_tindakan', 'nama_tindakan'])
            ->where('id_tindakan', $idTindakan);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchTindakan')->getRowArray() ?? [];
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'nomor_reg'   => $rawPost['nomor_reg'] ?? '',
            'id_dokter'   => $rawPost['id_dokter'] ?? '',
            'id_tindakan' => $rawPost['id_tindakan'] ?? '',
            'is_cito'     => $rawPost['is_cito'] ?? 0,
        ];
    }

    /**
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderDataForCreate(array $rawPost): array
    {
        // tanggal_minta mencatat kapan permintaan dibuat, jadi diisi server
        // saat create dan tidak pernah diubah lagi lewat update.
        return array_merge($this->buildHeaderData($rawPost), ['tanggal_minta' => date('Y-m-d H:i:s')]);
    }

    // -------------------------------------------------------------------------
    // Hooks
    // -------------------------------------------------------------------------

    #[\Override]
    protected function before_read(): void
    {
        $this->model->set_order('is_cito', 'DESC');
    }

    /**
     * @param array<array-key, mixed> $data_tabel
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    #[\Override]
    protected function after_read(array &$data_tabel): void
    {
        if ($data_tabel === []) {
            return;
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = $data_tabel;
        $ids  = array_column($rows, 'id_permintaan');

        $builder = $this->model
            ->db
            ->table('operasi.jadwal_operasi')
            ->select(['id_permintaan', 'id_jadwal', 'id_status'])
            ->whereIn('id_permintaan', $ids);

        /** @var list<array<string, mixed>> $jadwals */
        $jadwals = $this->model->guarded_get($builder, 'after_read')->getResultArray();

        $map = [];
        foreach ($jadwals as $j) {
            $map[(int) ($j['id_permintaan'] ?? 0)] = $j;
        }

        foreach ($rows as $key => $row) {
            $j                = $map[(int) ($row['id_permintaan'] ?? 0)] ?? null;
            $row['id_jadwal'] = $j['id_jadwal'] ?? null;
            $row['id_status'] = $j['id_status'] ?? null;
            $data_tabel[$key] = $row;
        }
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    #[\Override]
    public function create_page(): string
    {
        return view('admin/operasi/tambah_permintaan_operasi', [
            'judul'       => 'Tambah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Tambah', 'icon' => 'tambah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->filterKonfig(),
            'baris'       => [],
            'form_action' => '/submittambah',
        ]);
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $baris = $this->model->find_one($id);
        if (!is_array($baris)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        if (($baris['nomor_reg'] ?? '') !== '') {
            $baris = array_merge($baris, $this->fetchRegistrasi((string) ($baris['nomor_reg'] ?? '')));
        }

        if ((int) ($baris['id_dokter'] ?? 0) > 0) {
            $baris = array_merge($baris, $this->fetchNamaRole('dokter', 'id_dokter', (int) ($baris['id_dokter'] ?? 0)));
        }

        if ((int) ($baris['id_tindakan'] ?? 0) > 0) {
            $baris = array_merge($baris, $this->fetchTindakan((int) ($baris['id_tindakan'] ?? 0)));
        }

        return view('admin/operasi/tambah_permintaan_operasi', [
            'judul'       => 'Ubah ' . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => 'Ubah', 'icon' => 'ubah']]),
            'modul_path'  => $this->get_uri_path(),
            'kolom_id'    => $this->model->primaryKey,
            'konfig'      => $this->filterKonfig(),
            'baris'       => $baris,
            'form_action' => '/submitedit/' . $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();
        $data    = $this->buildHeaderDataForCreate($rawPost);

        $this->model->db->transStart();

        try {
            $this->model->insert($data);
            $idPermintaan = $this->model->getInsertID();

            $jadwalModel = new \App\Features\Operasi\JadwalOperasi\JadwalOperasiModel();
            $jadwalModel->insert([
                'id_permintaan' => $idPermintaan,
                'id_status'     => 1,
            ]);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan permintaan operasi.');
            }

            session()->setFlashdata('success', 'Permintaan operasi berhasil disimpan.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        /** @var array<string, mixed> $rawPost */
        $rawPost = $this->request->getPost();
        $data    = $this->buildHeaderData($rawPost);

        try {
            $this->model->update($id, $data);

            session()->setFlashdata('success', 'Permintaan operasi berhasil diperbarui.');
            return redirect()->to($this->get_uri_path() . '/data');
        } catch (\Exception $e) {
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    public function list(): ResponseInterface
    {
        $builder = $this->model
            ->db
            ->table('operasi.permintaan_operasi po')
            ->select([
                'po.id_permintaan',
                'po.nomor_reg',
                'po.tanggal_minta',
                'po.is_cito',
                'p.nomor_rm',
                'o.nama',
                'ti.nama_tindakan',
                'od.nama AS nama_dokter',
                'jo.id_jadwal',
                'jo.id_status',
            ])
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang o', 'o.id_orang       = p.id_orang', 'left')
            ->join('role.dokter d', 'd.id_dokter      = po.id_dokter', 'left')
            ->join('person.orang od', 'od.id_orang      = d.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('operasi.jadwal_operasi jo', 'jo.id_permintaan = po.id_permintaan', 'left')
            ->orderBy('po.is_cito', 'DESC')
            ->orderBy('po.tanggal_minta', 'DESC');

        $rows = $this->model->guarded_get($builder, 'list')->getResultArray();

        return $this->response->setJSON(['data' => $rows]);
    }

    #[\Override]
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            $jadwalModel = new \App\Features\Operasi\JadwalOperasi\JadwalOperasiModel();
            $jadwalModel->where('id_permintaan', $id)->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus permintaan operasi.');
            }

            session()->setFlashdata('success', 'Permintaan operasi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            $errorMsg = $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                ? $this->friendly_db_error($e)
                : $e->getMessage();
            session()->setFlashdata('error', $errorMsg);
        }

        return $this->home();
    }
}
