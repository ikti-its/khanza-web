<?php
declare(strict_types=1);

namespace App\Features\Operasi\ChecklistPostop;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class ChecklistPostopController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new ChecklistPostopModel(),
            [
                ['Operasi',                'operasi'],
                ['Checklist Post Operasi', 'checklist_postop'],
            ],
            'Checklist Post Operasi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_checklist_post',    'ID Checklist Post'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',            'Jadwal Operasi'],
                [HIDE,      REQUIRED, I::INDEX,  'id_tindakan',          'Tindakan'],
                [HIDE,      REQUIRED, I::INDEX,  'id_sn_cn',             'SN/CN'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_bedah',      'Dokter Bedah'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_anestesi',   'Dokter Anestesi'],
                [SHOW,      REQUIRED, I::DTIME,  'waktu_checklist',      'Waktu Checklist'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_kesadaran_pascaop', 'Kesadaran Pasca Op'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'jenis_cairan_infus',   'Jenis Cairan Infus'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_jaringan_pa_vc',    'Jaringan PA/VC'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_kateter_urine',     'Kateter Urine'],
                [FORM_ONLY, REQUIRED, I::DTIME,  'waktu_pasang_kateter', 'Waktu Pasang Kateter'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_warna_urine',       'Warna Urine'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'jumlah_urine_cc',      'Jumlah Urine (cc)'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'catatan_luka_operasi', 'Catatan Luka Operasi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_petugas_anestesi',  'Petugas Anestesi'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_petugas_ok',        'Petugas OK'],
            ],
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    #[\Override]
    protected function home(): RedirectResponse
    {
        $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? 0);
        if ($idJadwal > 0) {
            return redirect()->to('/operasi/lembar-operasi/data?id_jadwal=' . $idJadwal);
        }
        return redirect()->to($this->get_uri_path() . '/data');
    }

    /**
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchJadwal(int $idJadwal): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'j.id_dokter_bedah',
                'j.id_dokter_anestesi',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
                'ob.nama AS nama_dokter_bedah',
                'oa.nama AS nama_dokter_anestesi',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
            ->join('role.dokter db', 'db.id_dokter     = j.id_dokter_bedah', 'left')
            ->join('person.orang ob', 'ob.id_orang      = db.id_orang', 'left')
            ->join('role.dokter da', 'da.id_dokter     = j.id_dokter_anestesi', 'left')
            ->join('person.orang oa', 'oa.id_orang      = da.id_orang', 'left')
            ->where('j.id_jadwal', $idJadwal);

        /** @var array<string, mixed> */
        return $this->model->guarded_get($builder, 'fetchJadwal')->getRowArray() ?? [];
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchOptions(): array
    {
        $db = $this->model->db;

        $kesadaranBuilder    = $db->table('operasi.ref_kesadaran_pascaop')->select('id_kesadaran, nama_kesadaran');
        $ketersediaanBuilder = $db->table('operasi.ref_ketersediaan_status')->select(
            'id_ketersediaan_status, nama_ketersediaan',
        );
        $warnaBuilder          = $db->table('operasi.ref_warna_urine')->select('id_warna_urine, nama_warna');
        $jenisPenunjangBuilder = $db->table('operasi.ref_jenis_penunjang')->select('id_jenis_penunjang, nama_jenis');

        /** @var array<string, list<array<string, mixed>>> */
        return [
            'kesadaran_pascaop' => $this->model->guarded_get($kesadaranBuilder, 'fetchOptions')->getResultArray(),
            'ketersediaan'      => $this->model->guarded_get($ketersediaanBuilder, 'fetchOptions')->getResultArray(),
            'warna_urine'       => $this->model->guarded_get($warnaBuilder, 'fetchOptions')->getResultArray(),
            'jenis_penunjang'   => $this->model->guarded_get($jenisPenunjangBuilder, 'fetchOptions')->getResultArray(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchDrains(int $idChecklistPost): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.checklist_postop_drain d')
            ->select('d.id_drain, d.id_ketersediaan, d.jumlah, d.letak, d.warna, k.nama_ketersediaan')
            ->join('operasi.ref_ketersediaan_status k', 'k.id_ketersediaan_status = d.id_ketersediaan', 'left')
            ->where('d.id_checklist_post', $idChecklistPost);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchDrains')->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchPenunjang(int $idChecklistPost): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.checklist_postop_penunjang p')
            ->select('p.id_penunjang, p.id_jenis_penunjang, p.id_ketersediaan, p.keterangan')
            ->where('p.id_checklist_post', $idChecklistPost);

        /** @var list<array<string, mixed>> */
        return $this->model->guarded_get($builder, 'fetchPenunjang')->getResultArray();
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $builder = $this->model
            ->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue);

        $row = $this->model->guarded_get($builder, 'fetchNamaRole')->getRowArray();
        return (string) ($row['nama'] ?? '');
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    private function fetchTindakanName(int $idTindakan): string
    {
        $builder = $this->model
            ->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan);

        $row = $this->model->guarded_get($builder, 'fetchTindakanName')->getRowArray();
        return (string) ($row['nama_tindakan'] ?? '');
    }

    /**
     * Data Mapper for header.
     *
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'            => (int) ($rawPost['id_jadwal'] ?? 0) ? (int) ($rawPost['id_jadwal'] ?? 0) : null,
            'id_tindakan'          => (int) ($rawPost['id_tindakan'] ?? 0)
                ? (int) ($rawPost['id_tindakan'] ?? 0)
                : null,
            'id_sn_cn'             => (int) ($rawPost['id_sn_cn'] ?? 0) ? (int) ($rawPost['id_sn_cn'] ?? 0) : null,
            'id_dokter_bedah'      => (int) ($rawPost['id_dokter_bedah'] ?? 0)
                ? (int) ($rawPost['id_dokter_bedah'] ?? 0)
                : null,
            'id_dokter_anestesi'   => (int) ($rawPost['id_dokter_anestesi'] ?? 0)
                ? (int) ($rawPost['id_dokter_anestesi'] ?? 0)
                : null,
            'waktu_checklist'      => $rawPost['waktu_checklist'] ?? null,
            'id_kesadaran_pascaop' => (int) ($rawPost['id_kesadaran_pascaop'] ?? 0)
                ? (int) ($rawPost['id_kesadaran_pascaop'] ?? 0)
                : null,
            'jenis_cairan_infus'   => $rawPost['jenis_cairan_infus'] ?? null,
            'id_jaringan_pa_vc'    => (int) ($rawPost['id_jaringan_pa_vc'] ?? 0)
                ? (int) ($rawPost['id_jaringan_pa_vc'] ?? 0)
                : null,
            'id_kateter_urine'     => (int) ($rawPost['id_kateter_urine'] ?? 0)
                ? (int) ($rawPost['id_kateter_urine'] ?? 0)
                : null,
            'waktu_pasang_kateter' => $rawPost['waktu_pasang_kateter'] ?? null,
            'id_warna_urine'       => (int) ($rawPost['id_warna_urine'] ?? 0)
                ? (int) ($rawPost['id_warna_urine'] ?? 0)
                : null,
            'jumlah_urine_cc'      => $rawPost['jumlah_urine_cc'] ?? null,
            'catatan_luka_operasi' => $rawPost['catatan_luka_operasi'] ?? null,
            'id_petugas_anestesi'  => (int) ($rawPost['id_petugas_anestesi'] ?? 0)
                ? (int) ($rawPost['id_petugas_anestesi'] ?? 0)
                : null,
            'id_petugas_ok'        => (int) ($rawPost['id_petugas_ok'] ?? 0)
                ? (int) ($rawPost['id_petugas_ok'] ?? 0)
                : null,
        ];
    }

    /**
     * Batch Insert helper for drain and penunjang.
     *
     * @param list<array<string, mixed>> $drainList
     * @param list<array<string, mixed>> $penunjangList
     * @throws \ReflectionException
     */
    private function insertDrainAndPenunjang(int $idChecklistPost, array $drainList, array $penunjangList): void
    {
        $batchDrain = [];
        foreach ($drainList as $row) {
            $idKetersediaan = (int) ($row['id_ketersediaan'] ?? 0);
            if ($idKetersediaan === 0) {
                continue;
            }

            $batchDrain[] = [
                'id_checklist_post' => $idChecklistPost,
                'id_ketersediaan'   => $idKetersediaan,
                'jumlah'            => (int) ($row['jumlah'] ?? 0),
                'letak'             => $row['letak'] ?? '',
                'warna'             => $row['warna'] ?? '',
            ];
        }
        if (!empty($batchDrain)) {
            (new \App\Features\Operasi\ChecklistPostopDrain\ChecklistPostopDrainModel())->insertBatch($batchDrain);
        }

        $batchPenunjang = [];
        foreach ($penunjangList as $row) {
            $idJenis = (int) ($row['id_jenis_penunjang'] ?? 0);
            if ($idJenis === 0) {
                continue;
            }

            $batchPenunjang[] = [
                'id_checklist_post'  => $idChecklistPost,
                'id_jenis_penunjang' => $idJenis,
                'id_ketersediaan'    => (int) ($row['id_ketersediaan'] ?? 0)
                    ? (int) ($row['id_ketersediaan'] ?? 0)
                    : null,
                'keterangan'         => $row['keterangan'] ?? '',
            ];
        }
        if (!empty($batchPenunjang)) {
            (new \App\Features\Operasi\ChecklistPostopPenunjang\ChecklistPostopPenunjangModel())->insertBatch(
                $batchPenunjang,
            );
        }
    }

    /**
     * @param array<string, mixed> $jadwal
     * @param array<string, mixed> $record
     * @param list<array<string, mixed>> $drain
     * @param list<array<string, mixed>> $penunjang
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function buildViewData(
        array $jadwal,
        array $record,
        string $formAction,
        array $drain = [],
        array $penunjang = [],
    ): array {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'       => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [[
                'title' => $isCreate ? 'Tambah' : 'Ubah',
                'icon'  => '',
            ]]),
            'modul_path'  => $this->get_uri_path(),
            'form_action' => $formAction,
            'baris'       => $record,
            'jadwal'      => $jadwal,
            'options'     => $this->fetchOptions(),
            'drain'       => $drain,
            'penunjang'   => $penunjang,
        ];
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        return view('admin/operasi/tambah_checklist_postop', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'            => $idJadwal,
                'id_tindakan'          => $jadwal['id_tindakan'] ?? '',
                'nama_tindakan'        => $jadwal['nama_tindakan'] ?? '',
                'id_dokter_bedah'      => $jadwal['id_dokter_bedah'] ?? '',
                'nama_dokter_bedah'    => $jadwal['nama_dokter_bedah'] ?? '',
                'id_dokter_anestesi'   => $jadwal['id_dokter_anestesi'] ?? '',
                'nama_dokter_anestesi' => $jadwal['nama_dokter_anestesi'] ?? '',
            ],
            '/submittambah/',
        ));
    }

    /** @throws \CodeIgniter\Database\Exceptions\DatabaseException */
    #[\Override]
    public function update_page(int|string $id): string|RedirectResponse
    {
        $record = $this->model->find_one($id);
        if (!is_array($record)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return $this->index();
        }

        $idJadwal        = (int) ($record['id_jadwal'] ?? 0);
        $jadwal          = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];
        $idChecklistPost = (int) $id;

        if (($idDb = (int) ($record['id_dokter_bedah'] ?? 0)) > 0) {
            $record['nama_dokter_bedah'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDb);
        }
        if (($idDa = (int) ($record['id_dokter_anestesi'] ?? 0)) > 0) {
            $record['nama_dokter_anestesi'] = $this->fetchNamaRole('dokter', 'id_dokter', $idDa);
        }
        if (($idT = (int) ($record['id_tindakan'] ?? 0)) > 0) {
            $record['nama_tindakan'] = $this->fetchTindakanName($idT);
        }
        if (($idSn = (int) ($record['id_sn_cn'] ?? 0)) > 0) {
            $record['nama_sn_cn'] = $this->fetchNamaRole('petugas', 'id_petugas', $idSn);
        }
        if (($idPa = (int) ($record['id_petugas_anestesi'] ?? 0)) > 0) {
            $record['nama_petugas_anestesi'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPa);
        }
        if (($idPo = (int) ($record['id_petugas_ok'] ?? 0)) > 0) {
            $record['nama_petugas_ok'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPo);
        }

        return view('admin/operasi/tambah_checklist_postop', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
            $this->fetchDrains($idChecklistPost),
            $this->fetchPenunjang($idChecklistPost),
        ));
    }

    // -------------------------------------------------------------------------
    // Create and Update
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $rawPost */
        $rawPost    = $this->request->getPost();
        $dataHeader = $this->buildHeaderData($rawPost);
        /** @var list<array<string, mixed>> $drainList */
        $drainList = $rawPost['drain'] ?? [];
        /** @var list<array<string, mixed>> $penunjangList */
        $penunjangList = $rawPost['penunjang'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->insert($dataHeader);
            $idChecklistPost = $this->model->getInsertID();

            $this->insertDrainAndPenunjang((int) $idChecklistPost, $drainList, $penunjangList);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan checklist post operasi.');
            }

            return $this->home();
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
        $rawPost    = $this->request->getPost();
        $dataHeader = $this->buildHeaderData($rawPost);
        /** @var list<array<string, mixed>> $drainList */
        $drainList = $rawPost['drain'] ?? [];
        /** @var list<array<string, mixed>> $penunjangList */
        $penunjangList = $rawPost['penunjang'] ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $dataHeader);

            (new \App\Features\Operasi\ChecklistPostopDrain\ChecklistPostopDrainModel())
                ->where('id_checklist_post', $id)
                ->delete();
            (new \App\Features\Operasi\ChecklistPostopPenunjang\ChecklistPostopPenunjangModel())
                ->where('id_checklist_post', $id)
                ->delete();

            $this->insertDrainAndPenunjang((int) $id, $drainList, $penunjangList);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal memperbarui checklist post operasi.');
            }

            return $this->home();
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
    public function delete(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        $this->model->db->transStart();

        try {
            (new \App\Features\Operasi\ChecklistPostopDrain\ChecklistPostopDrainModel())
                ->where('id_checklist_post', $id)
                ->delete();
            (new \App\Features\Operasi\ChecklistPostopPenunjang\ChecklistPostopPenunjangModel())
                ->where('id_checklist_post', $id)
                ->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus checklist post operasi.');
            }

            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
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
