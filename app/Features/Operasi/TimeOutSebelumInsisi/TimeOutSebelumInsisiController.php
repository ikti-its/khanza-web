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
                A::AUDIT,
                A::UPDATE,
                A::DELETE,
            ],
            [
                [HIDE,       OPTIONAL, I::INDEX,  'id_timeout',              'ID Time Out'],
                [SHOW,       REQUIRED, I::INDEX,  'id_jadwal',               'Jadwal Operasi'],
                [HIDE,       REQUIRED, I::INDEX,  'id_tindakan',             'Tindakan'],
                [HIDE,       REQUIRED, I::INDEX,  'id_sn_cn',                'SN/CN'],
                [TABLE_ONLY, REQUIRED, I::INDEX,  'id_dokter_bedah',         'Dokter Bedah'],
                [TABLE_ONLY, REQUIRED, I::INDEX,  'id_dokter_anestesi',      'Dokter Anestesi'],
                [HIDE,       REQUIRED, I::INDEX,  'id_perawat_ok',           'Perawat OK'],
                [SHOW,       REQUIRED, I::DTIME,  'waktu_timeout',           'Waktu Time Out'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_identitas_sesuai',     'Identitas Sesuai'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_tindakan_sesuai',      'Tindakan Sesuai'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_area_insisi_sesuai',   'Area Insisi Sesuai'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_penandaan_area',       'Penandaan Area'],
                [FORM_ONLY,  REQUIRED, I::NUMBER, 'perkiraan_waktu_jam',     'Perkiraan Waktu (jam)'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_antibiotik',           'Antibiotik'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'nama_antibiotik',         'Nama Antibiotik'],
                [FORM_ONLY,  REQUIRED, I::TIME,   'waktu_antibiotik',        'Waktu Antibiotik'],
                [FORM_ONLY,  REQUIRED, I::TEXT,   'antisipasi_hilang_darah', 'Antisipasi Hilang Darah'],
                [FORM_ONLY,  REQUIRED, I::INDEX,  'id_hal_khusus',           'Hal Khusus'],
                [FORM_ONLY,  OPTIONAL, I::TEXT,   'keterangan_hal_khusus',   'Keterangan Hal Khusus'],
                [FORM_ONLY,  REQUIRED, I::DATE,   'tanggal_steril',          'Tanggal Steril'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_steril_dikonfirmasi',  'Steril Dikonfirmasi'],
                [FORM_ONLY,  REQUIRED, I::SELECT, 'is_verifikasi_preop',     'Verifikasi Pre Op'],
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

    private function fetchJadwal(int $idJadwal): array
    {
        return $this->model->db
            ->table('operasi.jadwal_operasi j')
            ->select([
                'j.id_jadwal',
                'po.nomor_reg',
                'po.id_tindakan',
                'ti.nama_tindakan',
                'op.nama AS nama_pasien',
            ])
            ->join('operasi.permintaan_operasi po',   'po.id_permintaan = j.id_permintaan', 'left')
            ->join('rekam_medis.registrasi r',        'r.nomor_reg      = po.nomor_reg',    'left')
            ->join('role.pasien p',                   'p.id_pasien      = r.id_pasien',     'left')
            ->join('person.orang op',                 'op.id_orang      = p.id_orang',      'left')
            ->join('operasi.ref_tindakan_operasi ti', 'ti.id_tindakan   = po.id_tindakan',  'left')
            ->where('j.id_jadwal', $idJadwal)
            ->get()->getRowArray() ?? [];
    }

    private function fetchOptions(): array
    {
        $db = $this->model->db;
        return [
            'ketersediaan'      => $db->table('operasi.ref_ketersediaan_status')
                ->select('id_ketersediaan_status, nama_ketersediaan')
                ->get()->getResultArray(),
            'jenis_penunjang'   => $db->table('operasi.ref_jenis_penunjang')
                ->select('id_jenis_penunjang, nama_jenis')
                ->whereIn('nama_jenis', ['Radiologi', 'CT Scan', 'MRI'])
                ->get()->getResultArray(),
            'status_penayangan' => $db->table('operasi.ref_status_penayangan')
                ->select('id_status_penayangan, nama_status')
                ->get()->getResultArray(),
        ];
    }

    private function fetchPenunjang(int $idTimeout): array
    {
        return $this->model->db
            ->table('operasi.time_out_sebelum_insisi_penunjang')
            ->select('id_jenis_penunjang, id_status')
            ->where('id_timeout', $idTimeout)
            ->get()->getResultArray();
    }

    private function fetchNamaRole(string $tabel, string $idKolom, int $idValue): string
    {
        $row = $this->model->db
            ->table("role.{$tabel} t")
            ->select('o.nama')
            ->join('person.orang o', 'o.id_orang = t.id_orang', 'left')
            ->where("t.{$idKolom}", $idValue)
            ->get()->getRowArray();
        return $row['nama'] ?? '';
    }

    private function fetchTindakanName(int $idTindakan): string
    {
        $row = $this->model->db
            ->table('operasi.ref_tindakan_operasi')
            ->select('nama_tindakan')
            ->where('id_tindakan', $idTindakan)
            ->get()->getRowArray();
        return $row['nama_tindakan'] ?? '';
    }

    private function buildHeaderData(array $rawPost): array
    {
        return [
            'id_jadwal'               => (int) ($rawPost['id_jadwal'] ?? 0) ?: null,
            'id_tindakan'             => (int) ($rawPost['id_tindakan'] ?? 0) ?: null,
            'id_sn_cn'                => (int) ($rawPost['id_sn_cn'] ?? 0) ?: null,
            'id_dokter_bedah'         => (int) ($rawPost['id_dokter_bedah'] ?? 0) ?: null,
            'id_dokter_anestesi'      => (int) ($rawPost['id_dokter_anestesi'] ?? 0) ?: null,
            'id_perawat_ok'           => (int) ($rawPost['id_perawat_ok'] ?? 0) ?: null,
            'waktu_timeout'           => $rawPost['waktu_timeout'] ?? null,
            'is_identitas_sesuai'     => $rawPost['is_identitas_sesuai'] ?? null,
            'is_tindakan_sesuai'      => $rawPost['is_tindakan_sesuai'] ?? null,
            'is_area_insisi_sesuai'   => $rawPost['is_area_insisi_sesuai'] ?? null,
            'id_penandaan_area'       => (int) ($rawPost['id_penandaan_area'] ?? 0) ?: null,
            'perkiraan_waktu_jam'     => $rawPost['perkiraan_waktu_jam'] ?? null,
            'is_antibiotik'           => $rawPost['is_antibiotik'] ?? null,
            'nama_antibiotik'         => $rawPost['nama_antibiotik'] ?? null,
            'waktu_antibiotik'        => $rawPost['waktu_antibiotik'] ?? null,
            'antisipasi_hilang_darah' => $rawPost['antisipasi_hilang_darah'] ?? null,
            'id_hal_khusus'           => (int) ($rawPost['id_hal_khusus'] ?? 0) ?: null,
            'keterangan_hal_khusus'   => $rawPost['keterangan_hal_khusus'] ?? null,
            'tanggal_steril'          => $rawPost['tanggal_steril'] ?? null,
            'is_steril_dikonfirmasi'  => $rawPost['is_steril_dikonfirmasi'] ?? null,
            'is_verifikasi_preop'     => $rawPost['is_verifikasi_preop'] ?? null,
        ];
    }

    private function insertPenunjangList(int $idTimeout, array $penunjangList): void
    {
        $batch = [];
        foreach ($penunjangList as $row) {
            $idJenis = (int) ($row['id_jenis_penunjang'] ?? 0);
            if ($idJenis === 0) continue;
            $batch[] = [
                'id_timeout'         => $idTimeout,
                'id_jenis_penunjang' => $idJenis,
                'id_status'          => (int) ($row['id_status'] ?? 0) ?: null,
            ];
        }
        if (!empty($batch)) (new \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangModel())
            ->insertBatch($batch);
    }

    private function buildViewData(array $jadwal, array $record, string $formAction, ?int $idTimeout = null): array
    {
        $isCreate = $formAction === '/submittambah/';
        return [
            'judul'       => ($isCreate ? 'Tambah ' : 'Ubah ') . $this->title,
            'breadcrumbs' => array_merge($this->breadcrumbs, [['title' => $isCreate ? 'Tambah' : 'Ubah', 'icon' => '']]),
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

    #[\Override]
    public function create_page(): string
    {
        $idJadwal = (int) ($this->request->getGet('id_jadwal') ?? 0);
        $jadwal   = $idJadwal > 0 ? $this->fetchJadwal($idJadwal) : [];

        return view('admin/operasi/tambah_time_out_sebelum_insisi',
            $this->buildViewData($jadwal, [
                'id_jadwal'     => $idJadwal,
                'id_tindakan'   => $jadwal['id_tindakan']   ?? '',
                'nama_tindakan' => $jadwal['nama_tindakan'] ?? '',
            ], '/submittambah/'));
    }

    #[\Override]
    public function update_page(int|string $id): string
    {
        $record   = $this->model->find_one($id);
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

        return view('admin/operasi/tambah_time_out_sebelum_insisi',
            $this->buildViewData($jadwal, $record, '/submitedit/' . $id, (int) $id));
    }

    // -------------------------------------------------------------------------
    // Create and Update
    // -------------------------------------------------------------------------

    #[\Override]
    public function create(): string|RedirectResponse
    {
        $this->model->db->transStart();
        try {
            $this->model->insert($this->buildHeaderData($this->request->getPost()));
            $idTimeout = $this->model->getInsertID();

            $this->insertPenunjangList((int) $idTimeout, $this->request->getPost('penunjang') ?? []);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan time out sebelum insisi.');
            }

            return $this->home();

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #[\Override]
    public function update(int|string $id): string|RedirectResponse
    {
        if ($id == 0) return $this->home();

        $this->model->db->transStart();

        try {
            $this->model->update($id, $this->buildHeaderData($this->request->getPost()));

            $modelPenunjang = new \App\Features\Operasi\TimeOutSebelumInsisiPenunjang\TimeOutSebelumInsisiPenunjangModel();
            $modelPenunjang->where('id_timeout', $id)->delete();
            
            $this->insertPenunjangList((int) $id, $this->request->getPost('penunjang') ?? []);

            $this->model->db->transComplete();

            if ($this->model->db->transStatus() === false) {
                throw new \RuntimeException('Gagal memperbarui time out sebelum insisi.');
            }

            return $this->home();

        } catch (\Exception $e) {
            $this->model->db->transRollback();
            session()->setFlashdata('error', $e instanceof \CodeIgniter\Database\Exceptions\DatabaseException ? $this->friendly_db_error($e) : $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
