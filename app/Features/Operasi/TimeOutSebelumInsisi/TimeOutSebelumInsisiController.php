<?php
declare(strict_types=1);

namespace App\Features\Operasi\TimeOutSebelumInsisi;

use App\Core\Controller\ActionType as A;
use App\Core\Controller\ControllerTemplate;
use App\Core\Controller\InputType as I;
use CodeIgniter\HTTP\RedirectResponse;

final class TimeOutSebelumInsisiController extends ControllerTemplate
{
    public function __construct()
    {
        parent::__construct(
            new TimeOutSebelumInsisiModel(),
            [
                ['Operasi',                 'operasi'],
                ['Time Out Sebelum Insisi', 'time_out_sebelum_insisi'],
            ],
            'Time Out Sebelum Insisi',
            [
                A::READ,
                // A::CREATE,
                // A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,      OPTIONAL, I::INDEX,  'id_timeout',              'ID Time Out'],
                [SHOW,      REQUIRED, I::INDEX,  'id_jadwal',               'Jadwal Operasi'],
                [HIDE,      REQUIRED, I::INDEX,  'id_tindakan',             'Tindakan'],
                [HIDE,      REQUIRED, I::INDEX,  'id_sn_cn',                'SN/CN'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_bedah',         'Dokter Bedah'],
                [SHOW,      REQUIRED, I::INDEX,  'id_dokter_anestesi',      'Dokter Anestesi'],
                [HIDE,      REQUIRED, I::INDEX,  'id_perawat_ok',           'Perawat OK'],
                [SHOW,      REQUIRED, I::DTIME,  'waktu_timeout',           'Waktu Time Out'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_identitas_sesuai',     'Identitas Sesuai'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_tindakan_sesuai',      'Tindakan Sesuai'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_area_insisi_sesuai',   'Area Insisi Sesuai'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_penandaan_area',       'Penandaan Area'],
                [FORM_ONLY, REQUIRED, I::NUMBER, 'perkiraan_waktu_jam',     'Perkiraan Waktu (jam)'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_antibiotik',           'Antibiotik'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'nama_antibiotik',         'Nama Antibiotik'],
                [FORM_ONLY, REQUIRED, I::TIME,   'waktu_antibiotik',        'Waktu Antibiotik'],
                [FORM_ONLY, REQUIRED, I::TEXT,   'antisipasi_hilang_darah', 'Antisipasi Hilang Darah'],
                [FORM_ONLY, REQUIRED, I::INDEX,  'id_hal_khusus',           'Hal Khusus'],
                [FORM_ONLY, OPTIONAL, I::TEXT,   'keterangan_hal_khusus',   'Keterangan Hal Khusus'],
                [FORM_ONLY, REQUIRED, I::DATE,   'tanggal_steril',          'Tanggal Steril'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_steril_dikonfirmasi',  'Steril Dikonfirmasi'],
                [FORM_ONLY, REQUIRED, I::SELECT, 'is_verifikasi_preop',     'Verifikasi Pre Op'],
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
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
            ])
            ->join('operasi.permintaan_operasi po', 'po.id_permintaan = j.id_permintaan', 'left')
            ->join('registrasi.registrasi r', 'r.nomor_reg      = po.nomor_reg', 'left')
            ->join('role.pasien p', 'p.id_pasien      = r.id_pasien', 'left')
            ->join('person.orang op', 'op.id_orang      = p.id_orang', 'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan', 'left')
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

        $ketersediaanBuilder = $db->table('operasi.ref_ketersediaan_status')->select(
            'id_ketersediaan_status, nama_ketersediaan',
        );
        $jenisPenunjangBuilder = $db
            ->table('operasi.ref_jenis_penunjang')
            ->select('id_jenis_penunjang, nama_jenis')
            ->whereIn('nama_jenis', ['Radiologi', 'CT Scan', 'MRI']);
        $statusPenayanganBuilder = $db->table('operasi.ref_status_penayangan')->select(
            'id_status_penayangan, nama_status',
        );

        /** @var array<string, list<array<string, mixed>>> */
        return [
            'ketersediaan'      => $this->model->guarded_get($ketersediaanBuilder, 'fetchOptions')->getResultArray(),
            'jenis_penunjang'   => $this->model->guarded_get($jenisPenunjangBuilder, 'fetchOptions')->getResultArray(),
            'status_penayangan' => $this->model
                ->guarded_get($statusPenayanganBuilder, 'fetchOptions')
                ->getResultArray(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function fetchPenunjang(int $idTimeout): array
    {
        $builder = $this->model
            ->db
            ->table('operasi.time_out_sebelum_insisi_penunjang')
            ->select('id_jenis_penunjang, id_status')
            ->where('id_timeout', $idTimeout);

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
     * @param array<string, mixed> $rawPost
     * @return array<string, mixed>
     */
    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'               => (int) ($rawPost['id_jadwal'] ?? 0) ? (int) ($rawPost['id_jadwal'] ?? 0) : null,
            'id_tindakan'             => (int) ($rawPost['id_tindakan'] ?? 0)
                ? (int) ($rawPost['id_tindakan'] ?? 0)
                : null,
            'id_sn_cn'                => (int) ($rawPost['id_sn_cn'] ?? 0) ? (int) ($rawPost['id_sn_cn'] ?? 0) : null,
            'id_dokter_bedah'         => (int) ($rawPost['id_dokter_bedah'] ?? 0)
                ? (int) ($rawPost['id_dokter_bedah'] ?? 0)
                : null,
            'id_dokter_anestesi'      => (int) ($rawPost['id_dokter_anestesi'] ?? 0)
                ? (int) ($rawPost['id_dokter_anestesi'] ?? 0)
                : null,
            'id_perawat_ok'           => (int) ($rawPost['id_perawat_ok'] ?? 0)
                ? (int) ($rawPost['id_perawat_ok'] ?? 0)
                : null,
            'waktu_timeout'           => $rawPost['waktu_timeout'] ?? null,
            'is_identitas_sesuai'     => $rawPost['is_identitas_sesuai'] ?? null,
            'is_tindakan_sesuai'      => $rawPost['is_tindakan_sesuai'] ?? null,
            'is_area_insisi_sesuai'   => $rawPost['is_area_insisi_sesuai'] ?? null,
            'id_penandaan_area'       => (int) ($rawPost['id_penandaan_area'] ?? 0)
                ? (int) ($rawPost['id_penandaan_area'] ?? 0)
                : null,
            'perkiraan_waktu_jam'     => $rawPost['perkiraan_waktu_jam'] ?? null,
            'is_antibiotik'           => $rawPost['is_antibiotik'] ?? null,
            'nama_antibiotik'         => $rawPost['nama_antibiotik'] ?? null,
            'waktu_antibiotik'        => $rawPost['waktu_antibiotik'] ?? null ? $rawPost['waktu_antibiotik'] : null,
            'antisipasi_hilang_darah' => $rawPost['antisipasi_hilang_darah'] ?? null,
            'id_hal_khusus'           => (int) ($rawPost['id_hal_khusus'] ?? 0)
                ? (int) ($rawPost['id_hal_khusus'] ?? 0)
                : null,
            'keterangan_hal_khusus'   => $rawPost['keterangan_hal_khusus'] ?? null,
            'tanggal_steril'          => $rawPost['tanggal_steril'] ?? null,
            'is_steril_dikonfirmasi'  => $rawPost['is_steril_dikonfirmasi'] ?? null,
            'is_verifikasi_preop'     => $rawPost['is_verifikasi_preop'] ?? null,
        ];
    }

    /**
     * @param list<array<string, mixed>> $penunjangList
     * @throws \ReflectionException
     */
    private function insertPenunjangList(int $idTimeout, array $penunjangList): void
    {
        $batch = [];
        foreach ($penunjangList as $row) {
            $idJenis = (int) ($row['id_jenis_penunjang'] ?? 0);
            if ($idJenis === 0) {
                continue;
            }
            $batch[] = [
                'id_timeout'         => $idTimeout,
                'id_jenis_penunjang' => $idJenis,
                'id_status'          => (int) ($row['id_status'] ?? 0) ? (int) ($row['id_status'] ?? 0) : null,
            ];
        }
        if ($batch !== []) {
            (new \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangModel())->insertBatch(
                $batch,
            );
        }
    }

    /**
     * @param array<string, mixed> $jadwal
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    private function buildViewData(array $jadwal, array $record, string $formAction, null|int $idTimeout = null): array
    {
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
            'penunjang'   => $idTimeout !== null ? $this->fetchPenunjang($idTimeout) : [],
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

        return view('admin/operasi/tambah_time_out_sebelum_insisi', $this->buildViewData(
            $jadwal,
            [
                'id_jadwal'     => $idJadwal,
                'id_tindakan'   => $jadwal['id_tindakan'] ?? '',
                'nama_tindakan' => $jadwal['nama_tindakan'] ?? '',
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

        $idJadwal = (int) ($record['id_jadwal'] ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

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
        if (($idPo = (int) ($record['id_perawat_ok'] ?? 0)) > 0) {
            $record['nama_perawat_ok'] = $this->fetchNamaRole('petugas', 'id_petugas', $idPo);
        }

        return view('admin/operasi/tambah_time_out_sebelum_insisi', $this->buildViewData(
            $jadwal,
            $record,
            '/submitedit/' . $id,
            (int) $id,
        ));
    }

    // -------------------------------------------------------------------------
    // Create and Update
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        /** @var array<string, mixed> $post */
        $post = $this->request->getPost();
        /** @var list<array<string, mixed>> $penunjangList */
        $penunjangList = $this->request->getPost('penunjang') ?? [];

        $this->model->db->transStart();
        try {
            $this->model->insert($this->buildHeaderData($post));
            $idTimeout = $this->model->getInsertID();

            $this->insertPenunjangList((int) $idTimeout, $penunjangList);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan time out sebelum insisi.');
            }

            return $this->home();
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata(
                'error',
                $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                    ? $this->friendly_db_error($e)
                    : $e->getMessage(),
            );
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ((int) $id === 0) {
            return $this->home();
        }

        /** @var array<string, mixed> $post */
        $post = $this->request->getPost();
        /** @var list<array<string, mixed>> $penunjangList */
        $penunjangList = $this->request->getPost('penunjang') ?? [];

        $this->model->db->transStart();

        try {
            $this->model->update($id, $this->buildHeaderData($post));

            $modelPenunjang =
                new \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangModel();
            $modelPenunjang->where('id_timeout', $id)->delete();

            $this->insertPenunjangList((int) $id, $penunjangList);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal memperbarui time out sebelum insisi.');
            }

            return $this->home();
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata(
                'error',
                $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                    ? $this->friendly_db_error($e)
                    : $e->getMessage(),
            );
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
            (new \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangModel())
                ->where('id_timeout', $id)
                ->delete();

            $this->model->delete($id);

            $this->model->db->transComplete();

            if (!$this->model->db->transStatus()) {
                throw new \RuntimeException('Gagal menghapus time out sebelum insisi.');
            }

            session()->setFlashdata('success', 'Data ' . $this->title . ' berhasil dihapus.');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata(
                'error',
                $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException
                    ? $this->friendly_db_error($e)
                    : $e->getMessage(),
            );
        }

        return $this->home();
    }
}
